<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Validation\IsCurrentPasswordCorrect;
use App\Models\Designation;
use App\Models\Position;
use App\Models\EmployeeModel; 

class AdminController extends BaseController
{
    protected $helpers =['url','form', 'CIMail', 'CIFunctions', 'EmployeeModel'];
    protected $employeeModel;


    public function index()
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->findAll();
        $employeeCount = $employeeModel->countAllResults();
        $designationModel = new Designation();
        $designations = $designationModel->findAll();
        $designationCount = $designationModel->countAllResults();

        $data = [
            'pageTitle' => 'Dashboard',
            'employee' => $employee,
            'employeeCount' => $employeeCount,
            'designationCount' => $designationCount
        ];
        return view('backend/pages/home', $data);
    }
    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
    }

    public function logoutHandler(){
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('fail', 'You are logged out!');
    }
    public function profile(){
        $data = array(
            'pageTitle'=>'Profile',
        );
        return view('backend/pages/profile', $data);
    }
    public function updatePersonalDetails() {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $user_id = CIAuth::id();
    
        // Validate the form input
        $validation->setRules([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Fullname is required'
                ]
            ],
            'username' => [
                'rules' => 'required|min_length[4]|is_unique[users.username,id,' . $user_id . ']',
                'errors' => [
                    'required' => 'Username is required',
                    'min_length' => 'Username must have a minimum of 4 characters',
                    'is_unique' => 'Username already taken!'
                ]
            ]
        ]);
    
        if (!$validation->withRequest($request)->run()) {
            // Validation failed, redirect back with errors
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        } else {
            $userModel = new \App\Models\User();
            
            // Update user details
            $update = $userModel->update($user_id, [
                'name' => $request->getPost('name'),
                'username' => $request->getPost('username'),
                'bio' => $request->getPost('bio')
            ]);
    
            if ($update) {
                $user_info = $userModel->find($user_id);
                return redirect()->back()->with('success', "Your personal details have been updated successfully.");
            } else {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        }
    }

     public function updatePersonalPictures(){
        $request = \Config\Services::request();
        $user_id = CIAuth::id();
        $user = new User();
        $user_info = $user->asObject()->where('id',$user_id)->first();

        $path ='images/users/';
        $file = $request->getFile('user_profile_file');
        $old_picture = $user_info->picture;
        $new_filename = 'UIMG_'.$user_id.$file->getRandomName();

        if( $file->move($path,$new_filename) ){
            if( $old_picture != null && file_exists($path.$old_picture) ){
                unlink($path.$old_picture);
            }
            $user->where('id',$user_info->id)
                 ->set(['picture'=>$new_filename])
                 ->update();

                 echo json_encode(['status'=>1,'msg'=>'Done!, Your profile picture has been successfully updated.']);
        }else{
            echo json_encode(['status'=>0,'msg'=>'Something went wrong.']);
        }
        // $upload_image = \Config\Services::image()
        //                 ->withFile($file)
        //                 ->resize(450,450,true,'height')
        //                 ->save($path.$new_filename);
        
        // if( $upload_image ){
        //     if( $old_picture != null && file_exists($path.$new_filename) ){
        //         unlink($path.$old_picture);
        //     }
        //     $user->where('id',$user_info->id)
        //                     ->set(['picture'->$new_filename])
        //                     ->update();

        //     echo json_encode(['status'=>1,'msg'=>'Something wentt wrong.']);
        // }else{
        //     echo json_encode(['status'=>0,'msg'=>'Something went wrong.']);
        // }
    } 
    public function changePassword(){
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
                'rules' => 'required|min_length[5]|max_length[20]|is_password_strong[new_password]',
                'errors' => [
                    'required' => 'New password is required',
                    'min_length' => 'New password must have at least 5 characters',
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
            $user->update($user_id, ['password' => password_hash($new_password, PASSWORD_DEFAULT)]);
    
            return redirect()->back()->with('success', 'Password has been changed successfully.');
        }
    }
    
    
    
    
    

    

    /////!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    public function designation(){
        $designationModel = new Designation();
        $designations = $designationModel->findAll(); // Retrieve all designations from the database

        $data = [
            'pageTitle' => 'Designation',
            'designations' => $designations // Pass the fetched designations to the view
        ];

        return view('backend/pages/designation', $data);
    }
            public function designationSave()
            {
                $designationModel = new Designation();
                
                $data = [
                    'name' => $this->request->getPost('designation')
                ];
            
                if ($designationModel->insert($data)) {
                    // Return a JSON response indicating success
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Designation added successfully']);
                } else {
                    // Return a JSON response indicating failure
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add Designation']);
                }
            }
            public function deleteDesignation()
            {
                if ($this->request->isAJAX()) {
                    $designationModel = new \App\Models\Designation();
                    $id = $this->request->getPost('id');
        
                    if (!empty($id)) {
                        if ($designationModel->delete($id)) {
                            return $this->response->setJSON(['status' => 'success', 'message' => 'Designation deleted successfully']);
                        } else {
                            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete designation'])->setStatusCode(500);
                        }
                    } else {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid designation ID'])->setStatusCode(400);
                    }
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized access'])->setStatusCode(401);
                }
            }
            
            

public function updateDesignation()
{
    $request = service('request');
    $designationModel = new Designation();

    if ($request->isAJAX()) {
        $id = $request->getVar('id');
        $name = $request->getVar('designation');

        if (!empty($id) && !empty($name)) {
            // Update the designation
            if ($designationModel->update($id, ['name' => $name])) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Designation updated successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update designation'], 500);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid data'], 400);
        }
    } else {
        return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized access']);
    }
}

    /////////////////////////////////////////////////////////////////////////////////////////////

    public function position()
    {
        $positionModel = new Position();
        $positions = $positionModel->findAll();
        $data = [
            'pageTitle' => 'Position',
            'positions' => $positions
        ];
        return view('backend/pages/position', $data);
    }

    public function positionSave()
    {
        $positionModel = new Position();
        
        $data = [
            'position_name' => $this->request->getPost('position')
        ];
    
        if ($positionModel->insert($data)) {
            // Return a JSON response indicating success
            return $this->response->setJSON(['status' => 'success', 'message' => 'Position added successfully']);
        } else {
            // Return a JSON response indicating failure
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add Position']);
        }
    }
    public function updatePosition()
        {
            $request = service('request');
            $positionModel = new Position();

            if ($request->isAJAX()) {
                $position_id = $request->getVar('position_id');
                $position_name = $request->getVar('position_name');

                if (!empty($position_id) && !empty($position_name)) {
                    // Update the position
                    if ($positionModel->update($position_id, ['position_name' => $position_name])) {
                        return $this->response->setJSON(['status' => 'success', 'message' => 'Position updated successfully']);
                    } else {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update Position'], 500);
                    }
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid data'], 400);
                }
            } else {
                return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized access']);
            }
        }

        public function deletePosition()
        {
            $request = service('request');
            $positionModel = new Position();
        
            if ($request->isAJAX()) {
                $position_id = $request->getVar('position_id');
        
                if (!empty($position_id)) {
                    // Delete the position
                    if ($positionModel->delete($position_id)) {
                        return $this->response->setJSON(['status' => 'success', 'message' => 'Position deleted successfully']);
                    } else {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete position'], 500);
                    }
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid data'], 400);
                }
            } else {
                return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Unauthorized access']);
            }
        }

    /////#######################################################################################################


    public function employee()
    {
        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->findAll(); // Fetch all employees from the database

        $data = [
            'pageTitle' => 'Employee',
            'employee' => $employees // Pass the retrieved data to the view
        ];

        return view('backend/pages/employee', $data); // Load the view with data
    }

    public function saveEmployee()
    {
       // Get the request instance
       $request = \Config\Services::request();

       // Validate the incoming data
       $validation = \Config\Services::validation();
       $validation->setRules([
           'firstname' => 'required',
           'lastname' => 'required',
           'email' => 'required|valid_email',
           'phone' => 'required',
           'address' => 'required',
           'dob' => 'required',
           'p_school' => 'required',
           's_school' => 'required',
           't_school' => 'required',
           'interview_for' => 'required',
           'interview_type' => 'required',
           'interview_date' => 'required',
           'interview_time' => 'required',
           'behaviour' => 'required',
           'result' => 'required',
           'comment' => 'required',
           'sex' => 'required'
       ]);

       if (!$validation->withRequest($request)->run()) {
           return $this->response->setJSON([
               'status' => 'error',
               'message' => 'Validation failed',
               'errors' => $validation->getErrors()
           ]);
       }

       // If validation passes, save the data to the database
       $employeeModel = new EmployeeModel();
       $data = [
           'firstname' => $request->getPost('firstname'),
           'lastname' => $request->getPost('lastname'),
           'email' => $request->getPost('email'),
           'phone' => $request->getPost('phone'),
           'address' => $request->getPost('address'),
           'dob' => $request->getPost('dob'),
           'p_school' => $request->getPost('p_school'),
           's_school' => $request->getPost('s_school'),
           't_school' => $request->getPost('t_school'),
           'interview_for' => $request->getPost('interview_for'),
           'interview_type' => $request->getPost('interview_type'),
           'interview_date' => $request->getPost('interview_date'),
           'interview_time' => $request->getPost('interview_time'),
           'behaviour' => $request->getPost('behaviour'),
           'result' => $request->getPost('result'),
           'comment' => $request->getPost('comment'),
           'sex' => $request->getPost('sex')
       ];

       if ($employeeModel->save($data)) {
           return $this->response->setJSON([
               'status' => 'success',
               'message' => 'Employee added successfully'
           ]);
       } else {
           return $this->response->setJSON([
               'status' => 'error',
               'message' => 'Failed to save employee data'
           ]);
       }
   }
    
    
    public function employeelist()
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->findAll();

        $data = [
            'pageTitle' => 'Employee List',
            'employee' => $employee
        ];
        return view('backend/pages/employeelist',$data);
    }
   // app/Controllers/AdminController.php
   public function updatePersonalDetail()
   {
       if ($this->request->isAJAX()) {
           $id = $this->request->getPost('id');
           $data = [
               'firstname' => $this->request->getPost('firstname'),
               'lastname' => $this->request->getPost('lastname'),
               'phone' => $this->request->getPost('phone'),
               'dob' => $this->request->getPost('dob'),
               'sex' => $this->request->getPost('sex'),
               'address' => $this->request->getPost('address'),
           ];

           $this->employeeModel->update($id, $data);
           return $this->response->setJSON(['success' => true, 'message' => 'Personal details updated successfully.']);
       }

       return $this->response->setJSON(['success' => false, 'message' => 'Invalid request.']);
   }
   public function updateEducationalBackground()
   {
       if ($this->request->isAJAX()) {
           $id = $this->request->getPost('id');
           $data = [
               'p_school' => $this->request->getPost('p_school'),
               's_school' => $this->request->getPost('s_school'),
               't_school' => $this->request->getPost('t_school'),
           ];
   
           if ($this->employeeModel->update($id, $data)) {
               return $this->response->setJSON(['success' => true, 'message' => 'Educational background updated successfully.']);
           } else {
               return $this->response->setJSON(['success' => false, 'message' => 'Failed to update educational background.']);
           }
       }
   
       return $this->response->setJSON(['success' => false, 'message' => 'Invalid request.']);
   }
   
      public function updateInterview()
      {
          if ($this->request->isAJAX()) {
              $id = $this->request->getPost('id');
              $data = [
                  'interview_for' => $this->request->getPost('interview_for'),
                  'interview_type' => $this->request->getPost('interview_type'),
                  'interview_date' => $this->request->getPost('interview_date'),
                  'interview_time' => $this->request->getPost('interview_time'),
              ];
   
              $this->employeeModel->update($id, $data);
              return $this->response->setJSON(['success' => true, 'message' => 'Interview details updated successfully.']);
          }
   
          return $this->response->setJSON(['success' => false, 'message' => 'Invalid request.']);
      }
   
      public function updateRemarks()
      {
          if ($this->request->isAJAX()) {
              $id = $this->request->getPost('id');
              $data = [
                  'behaviour' => $this->request->getPost('behaviour'),
                  'result' => $this->request->getPost('result'),
                  'comment' => $this->request->getPost('comment'),
              ];
   
              $this->employeeModel->update($id, $data);
              return $this->response->setJSON(['success' => true, 'message' => 'Remarks updated successfully.']);
          }
   
          return $this->response->setJSON(['success' => false, 'message' => 'Invalid request.']);
      }

    
   public function update_profile_picture()
   {
       $id = $this->request->getPost('id');
       $employee = new EmployeeModel();
   
       if ($imagefile = $this->request->getFile('profile_picture')) {
           if ($imagefile->isValid() && !$imagefile->hasMoved()) {
               $newName = $imagefile->getRandomName();
               $imagefile->move(ROOTPATH . 'public/backend/images/users', $newName);
   
               $data = ['picture' => $newName];
   
               if ($employee->update($id, $data)) {
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
   

   


    public function getEmployee()
    {
        $employeeId = $this->request->getPost('id');
        $employees = new EmployeeModel();
        $employee = $employees->find($employeeId);

        if ($employee) {
            return $this->response->setJSON($employee);
        } else {
            return $this->response->setStatusCode(404, 'Employee not found');
        }
    }
    public function employee_report(){
        $data = array(
            'pageTitle'=>'Reports'
        );
        return view('backend/pages/employee_report',$data);
    }
    public function deleteEmployee()
{
    $employeeId = $this->request->getPost('id');
    
    if (!$employeeId) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid employee ID.']);
    }

    $employees = new \App\Models\EmployeeModel();
    if ($employees->delete($employeeId)) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Employee deleted successfully.']);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete employee.']);
    }
}

    
    
    /////&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&



    public function attendance(){
        $data = array(
        'pageTitle'=>'Attendance'
        );
        return view('backend/pages/attendance', $data);
    }
    public function report(){
        $data = array(
        'pageTitle'=>'Report'
        );
        return view('backend/pages/report', $data);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////



    public function leave_type(){
        $data = array(
        'pageTitle'=>'Leave Type'
        );
        return view('backend/pages/leave_type', $data);
    }
    public function holidays(){
        $data = array(
        'pageTitle'=>'Holidays'
        );
        return view('backend/pages/holidays', $data);
    }
    public function earn_leave(){
        $data = array(
        'pageTitle'=>'Earn Leave'
        );
        return view('backend/pages/earn_leave', $data);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function setting(){
        $data = array(
        'pageTitle'=>'Setting'
        );
        return view('backend/pages/setting', $data);
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function printEmployee($id)
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);
    
        if (!$employee) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Employee with ID $id not found");
        }
    
        // Prepare the picture URL
        $baseURL = base_url('backend/images/users/');
        $employee['picture_url'] = $employee['picture'] ? $baseURL . htmlspecialchars($employee['picture']) : $baseURL . 'userav-min.png';
    
        $data = [
            'employee' => $employee,
        ];
    
        return view('backend/pages/print', $data);
    }

}
