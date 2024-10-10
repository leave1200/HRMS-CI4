<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\Controller;
use App\Models\EmployeeModel;
use App\Models\FileModel;
use App\Libraries\CIAuth;

class UserController extends Controller
{
    protected $helpers =['url','form', 'CIMail', 'CIFunctions', 'EmployeeModel','AttendanceModel'];

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User(); // Load the UserModel
        $this->session = session();    // Initialize session
    }

    public function add()
    {
        $employeeModel = new EmployeeModel();
        $userStatus = session()->get('userStatus');
        $data = [
            'employees' => $employeeModel->getEmployeeNames(), // Fetch employee names with email
            'pageTitle' => 'Add User',
            'userStatus' => $userStatus,
            'validation' => \Config\Services::validation()
        ];
        
        return view('backend/pages/adduser', $data);
    }
    public function userlist()
    {
        $userStatus = session()->get('userStatus');
        $userModel = new User();
        $users = $userModel->findAll();
    
        $data = [
            'pageTitle' => 'User List',
            'users' => $users,
            'userStatus' => $userStatus
        ];
    
        return view('backend/pages/userlist', $data);
    }
    protected function isLoggedIn()
    {
        return $this->session->has('isLoggedIn') && $this->session->get('isLoggedIn') === true;
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
    
        $employee_id = $this->request->getPost('employee_id');
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        $bio = $this->request->getPost('bio');
        $status = $this->request->getPost('status');
    
        $employeeModel = new \App\Models\EmployeeModel();
        $employee = $employeeModel->find($employee_id);
    
        if (!$employee) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Employee not found']);
        }
    
        $userData = [
            'name' => $employee['firstname'] . ' ' . $employee['lastname'],
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'bio' => $bio,
            'status' => $status
        ];
    
        if (!$builder->insert($userData)) {
            $error = $db->error(); // Get database error
            log_message('error', 'Database Error: ' . print_r($error, true));
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add user']);
        }
    
        return $this->response->setJSON(['status' => 'success', 'redirect' => redirect()->back()]);
    }
    
    public function update_profile_picture()
    {
        $id = $this->request->getPost('id');
        $userModel = new UserModel();
    
        if ($imagefile = $this->request->getFile('profile_picture')) {
            if ($imagefile->isValid() && !$imagefile->hasMoved()) {
                $newName = $imagefile->getRandomName();
                $imagefile->move(ROOTPATH . 'public/backend/images/users', $newName);
    
                $data = ['picture' => $newName];
    
                if ($userModel->update($id, $data)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Profile picture updated successfully',
                        'new_picture_url' => base_url('backend/images/users/' . $newName)
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to update profile picture'
                    ]);
                }
            }
        }
    
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid image file'
        ]);
    }
    
    public function upload()
    {
        $userStatus = session()->get('userStatus');
        $userId = session()->get('userId');  // Assuming 'userId' is stored in session
    
        // Fetch uploaded files by the logged-in user from the database
        $fileModel = new FileModel();
        $files = $fileModel->where('user_id', $userId)->findAll();
    
        $data = [
            'pageTitle' => 'Uploads',
            'userStatus' => $userStatus,
            'files' => $files
        ];
    
        return view('backend/pages/upload', $data);
    }
  
    

    public function uploadFile()
    {
        // Ensure the user is logged in
        if (!$this->isLoggedIn()) {
            $this->session->setFlashdata('error', 'Please log in to upload files.');
            return redirect()->to('/login');
        }

        // Define validation rules to accept all file types
        $validationRules = [
            'file' => [
                'label' => 'File',
                'rules' => 'uploaded[file]'
                            . '|max_size[file,51200]' // Max size in KB (50MB)
                            // Removed 'ext_in' and 'mime_in' to accept all file types
                ,
                'errors' => [
                    'uploaded' => 'Please upload a file.',
                    'max_size' => 'The file size must not exceed 50MB.',
                ]
            ]
        ];

        // Validate the input
        if (!$this->validate($validationRules)) {
            return view('backend/pages/file/upload', [
                'pageTitle' => 'Upload File',
                'validation' => $this->validator
            ]);
        }

        // Retrieve the uploaded file
        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            // Optional: Rename the file to avoid collisions and for security
            $newFileName = $file->getRandomName();

            // Move the file to the desired directory (store outside webroot)
            $uploadPath = WRITEPATH . 'uploads/';

            // Ensure the upload directory exists
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($file->move($uploadPath, $newFileName)) {
                // Set restrictive permissions
                chmod($uploadPath . $newFileName, 0644);

                // Optionally, save file information to the database
                $fileModel = new FileModel();
                $fileDataArray = [
                    'name' => $newFileName,                  // Stored file name
                    'original_name' => $file->getClientName(), // Original file name
                    'uploaded_at' => date('Y-m-d H:i:s'),
                    'user_id' => $this->session->get('user_id'),
                    // Add other fields as necessary
                ];

                if ($fileModel->insert($fileDataArray)) {
                    $this->session->setFlashdata('success', 'File uploaded successfully.');
                } else {
                    $this->session->setFlashdata('error', 'Failed to save the file information to the database.');
                }
            } else {
                $this->session->setFlashdata('error', 'Failed to move the uploaded file.');
            }
        } else {
            $this->session->setFlashdata('error', 'There was an issue with the file upload.');
        }

        return redirect()->back();
    }

    public function downloadFile($id)
    {
        $fileModel = new FileModel();
        $file = $fileModel->find($id);

        if (!$file) {
            $this->session->setFlashdata('error', 'File not found.');
            return redirect()->back();
        }

        // Authorization: Ensure the file belongs to the logged-in user
        if ($file['user_id'] !== $this->session->get('user_id')) {
            $this->session->setFlashdata('error', 'You do not have permission to access this file.');
            return redirect()->back();
        }

        $filePath = WRITEPATH . 'uploads/' . $file['name'];

        if (!file_exists($filePath)) {
            $this->session->setFlashdata('error', 'File does not exist.');
            return redirect()->back();
        }

        // Determine the MIME type of the file
        $mime = mime_content_type($filePath) ?: 'application/octet-stream';

        // Serve the file for download
        return $this->response
                    ->setHeader('Content-Type', $mime)
                    ->setHeader('Content-Disposition', 'attachment; filename="' . $file['original_name'] . '"')
                    ->setBody(file_get_contents($filePath));
    }

    public function viewFile($id)
{
    $fileModel = new FileModel();
    $file = $fileModel->find($id);

    if (!$file) {
        $this->session->setFlashdata('error', 'File not found.');
        return redirect()->back();
    }

    // Define the upload directory path
    $uploadDirectory = WRITEPATH . 'uploads/'; // Adjust this path as per your setup

    // Full path to the file
    $filePath = $uploadDirectory . $file['name'];

    // Check if the file exists
    if (!file_exists($filePath)) {
        $this->session->setFlashdata('error', 'File does not exist.');
        return redirect()->back();
    }

    // Determine the MIME type using the full path
    $mime = mime_content_type($filePath) ?: 'application/octet-stream';

    // Only allow inline viewing for certain MIME types
    $inlineTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];

    // Fallback to 'name' if 'original_name' is not set
    $filename = !empty($file['original_name']) ? $file['original_name'] : $file['name'];

    if (in_array($mime, $inlineTypes)) {
        return $this->response
                    ->setHeader('Content-Type', $mime)
                    ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                    ->setBody(file_get_contents($filePath));
    } else {
        // Optionally, force download for other file types
        return $this->response
                    ->setHeader('Content-Type', $mime)
                    ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->setBody(file_get_contents($filePath));
    }
}



    public function deleteFile($id)
    {
        $fileModel = new FileModel();
        $file = $fileModel->find($id);

        if (!$file) {
            session()->setFlashdata('error', 'File not found.');
            return redirect()->back();
        }

        if ($fileModel->delete($id)) {
            session()->setFlashdata('success', 'File deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete the file.');
        }

        return redirect()->back();
    }

    public function uploadList()
    {
        $userId = session()->get('user_id'); // Get the current user's ID
        $userStatus = session()->get('userStatus'); // Get the current user's status
    
        $fileModel = new FileModel();
    
        // Check if the user is an admin
        if ($userStatus === 'ADMIN') {
            // Fetch all files if the user is an admin
            $files = $fileModel->findAll();
        } else {
            // Fetch only the files uploaded by the logged-in user
            $files = $fileModel->where('user_id', $userId)->findAll();
        }
    
        return view('your_view_file', [
            'files' => $files,
            'pageTitle' => 'Your Uploaded Files',
        ]);
    }
    


}
