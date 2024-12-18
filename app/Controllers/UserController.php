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
        $userModel = new User(); // Assuming you have a UserModel for the `users` table
    
        $userStatus = session()->get('userStatus');
        
        // Fetch all employees
        $allEmployees = $employeeModel->getEmployeeNames();
        
        // Fetch all names from the users table
        $users = $userModel->select('name')->findAll();
        $userNames = array_column($users, 'name'); // Extract the names into an array
    
        // Filter out employees whose names already exist in the users table
        $filteredEmployees = array_filter($allEmployees, function ($employee) use ($userNames) {
            return !in_array($employee['name'], $userNames);
        });
    
        $data = [
            'employees' => $filteredEmployees, // Filtered employees
            'pageTitle' => 'Add User',
            'userStatus' => $userStatus,
            'validation' => \Config\Services::validation()
        ];
    
        return view('backend/pages/adduser', $data);
    }
    
    public function userlist()
    {
        $userStatus = session()->get('userStatus');
        if ($userStatus !== 'ADMIN') {
            return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
        }
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
            'status' => $status,
            'policy' => 'NO', // Default value for policy
            'terms' => 'NO'   // Default value for terms
        ];
    
        if (!$builder->insert($userData)) {
            $error = $db->error(); // Get database error
            log_message('error', 'Database Error: ' . print_r($error, true));
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add user']);
        }
    
        return $this->response->setJSON(['status' => 'success', 'redirect' => redirect()->back()]);
    }
    
    // public function update_profile_picture()
    // {
    //     $id = $this->request->getPost('id');
    //     $userModel = new User();
    
    //     if ($imagefile = $this->request->getFile('profile_picture')) {
    //         if ($imagefile->isValid() && !$imagefile->hasMoved()) {
    //             $newName = $imagefile->getRandomName();
    //             $imagefile->move(ROOTPATH . 'public/images/users', $newName);
    
    //             $data = ['picture' => $newName];
    
    //             if ($userModel->update($id, $data)) {
    //                 return $this->response->setJSON([
    //                     'success' => true,
    //                     'message' => 'Profile picture updated successfully',
    //                     'new_picture_url' => base_url('/images/users/' . $newName)
    //                 ]);
    //             } else {
    //                 return $this->response->setJSON([
    //                     'success' => false,
    //                     'message' => 'Failed to update profile picture'
    //                 ]);
    //             }
    //         }
    //     }
    
    //     return $this->response->setJSON([
    //         'success' => false,
    //         'message' => 'Invalid image file'
    //     ]);
    // }
    public function updatePersonalPictures() {
        $userModel = new User();
        $user_id = CIAuth::id(); // Adjust this according to your authentication setup to get the current user's ID
    
        if ($imagefile = $this->request->getFile('user_profile_file')) {
            if ($imagefile->isValid() && !$imagefile->hasMoved()) {
                $newName = 'UIMG_' . $user_id . '_' . $imagefile->getRandomName();
                $path = ROOTPATH . 'public/images/users';
    
                // Move the uploaded file
                if ($imagefile->move($path, $newName)) {
                    // Retrieve the user's old picture
                    $user_info = $userModel->asObject()->find($user_id);
                    $old_picture = $user_info->picture;
    
                    if ($old_picture && file_exists($path . '/' . $old_picture)) {
                        if (!unlink($path . '/' . $old_picture)) {
                            log_message('error', 'Failed to delete old picture: ' . $old_picture);
                        }
                    }
                    
    
                    // Update the user's profile picture in the database
                    $userModel->update($user_id, ['picture' => $newName]);
    
                    return $this->response->setJSON([
                        'status' => 1,
                        'msg' => 'Done! Your profile picture has been successfully updated.',
                        'new_picture_url' => base_url('/images/users/' . $newName)
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 0,
                        'msg' => 'Failed to move the uploaded file.'
                    ]);
                }
            }
        }
    
        return $this->response->setJSON([
            'status' => 0,
            'msg' => 'Invalid image file.'
        ]);
    }

    public function changePassword()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $user_id = CIAuth::id();
        $user = new User();
        $user_info = $user->asObject()->where('id', $user_id)->first();
    
        // Validate the form
        $validation->setRules([
            'current_password' => [
                'rules' => 'required|min_length[5]|check_current_password[current_password]',
                'errors' => [
                    'required' => 'Enter the current password',
                    'min_length' => 'Password must have at least 5 characters',
                    'check_current_password' => 'The current password is incorrect'
                ]
            ],
            'new_password' => [
                'rules' => 'required|min_length[8]|max_length[20]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'New password is required',
                    'min_length' => 'New password must have at least 8 characters',
                    'max_length' => 'New password must not exceed 20 characters',
                    'is_password_strong' => 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character'
                ]
            ],
            'confirm_new_password' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Confirm new password',
                    'matches' => 'Password mismatch.'
                ]
            ]
        ]);
    
        if (!$validation->withRequest($request)->run()) {
            // Validation failed
            $errors = $validation->getErrors();
            return redirect()->back()->withInput()->with('errors', $errors);
        } else {
            // Validation passed, update the password
            $new_password = $request->getPost('new_password');
    
            // Hash the new password using bcrypt
            $hashed_password = password_hash($new_password, PASSWORD_ARGON2ID);
    
            // Update the user password in the database
            $user->update($user_id, ['password' => $hashed_password]);
    
            return redirect()->back()->with('success', 'Password has been changed successfully.');
        }
    }
    public function upload()
    {
        $userModel = new \App\Models\User();
        $fileModel = new \App\Models\FileModel();
    
        // Get the logged-in user's ID and status
        $userId = $this->session->get('user_id'); 
        $userStatus = session()->get('userStatus');// Assuming this holds 'ADMIN', 'EMPLOYEE', 'STAFF', etc.

        // If user is an admin, fetch all files
        if ($userStatus !== 'ADMIN') {
            // Non-admin users can only view their own files
            $files = $fileModel->where('user_id', $userId)->findAll();
           
        } else {
            $files = $fileModel->findAll();
            $files = $fileModel->select('files.*, users.username')
                   ->join('users', 'users.id = files.user_id')
                   ->findAll();
        }
    
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
                            . '|max_size[file,20480]' // Max size in KB (50MB)
                            // Removed 'ext_in' and 'mime_in' to accept all file types
                ,
                'errors' => [
                    'uploaded' => 'Please upload a file.',
                    'max_size' => 'The file size must not exceed 10MB.',
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
    
        // Check if the file exists
        if (!$file) {
            $this->session->setFlashdata('error', 'File not found.');
            return redirect()->back();
        }
    
        $userStatus = $this->session->get('userStatus');
        $loggedInUserId = $this->session->get('user_id'); // Get the logged-in user's ID
    
        // Check if the user is ADMIN or the file belongs to the logged-in user (owner)
        if ($userStatus !== 'ADMIN' && $file['user_id'] !== $loggedInUserId) {
            // Show an error message if the user is not ADMIN or the file does not belong to them
            $this->session->setFlashdata('error', 'You do not have permission to access this file.');
            return redirect()->back();
        }
    
        // File path
        $filePath = WRITEPATH . 'uploads/' . $file['name'];
    
        // Check if the file exists on the server
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
    public function updateTermsAcceptance()
    {
            // Ensure the 'userId' and 'termsAccepted' data are received correctly
            $userId = $this->request->getPost('userId');
            $termsAccepted = $this->request->getPost('termsAccepted');
            
            // Log incoming data for debugging
            log_message('debug', 'Received data: userId=' . $userId . ', termsAccepted=' . $termsAccepted);
    
            if (empty($userId) || !isset($termsAccepted)) {
                log_message('error', 'Invalid input: ' . json_encode($this->request->getPost()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid input data. Please fill out all fields.'
                ]);
            }
    
            $userModel = new \App\Models\User();
            
            // Update the terms acceptance in the database
            $data = ['terms' => 1];
            if ($userModel->update($userId, $data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Terms acceptance updated successfully.'
                ]);
            } else {
                log_message('error', 'Failed to update terms acceptance for user ' . $userId);
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update your terms acceptance. Please try again.'
                ]);
            }
    }
    
    
}
