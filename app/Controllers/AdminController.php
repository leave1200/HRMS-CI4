<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\User;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Validation\IsCurrentPasswordCorrect;
use App\Models\Designation;
use App\Models\Position;
use App\Models\EmployeeModel;
use App\Models\AttendanceModel; 
use App\Models\leave_typeModel;
use App\Models\LeaveApplicationModel;
use App\Models\HolidayModel;

class AdminController extends BaseController
{
    
    protected $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new \App\Models\EmployeeModel(); // Load your model
        $this->leaveTypeModel = new \App\Models\leave_typeModel();
    }
    protected $helpers =['url','form', 'CIMail', 'CIFunctions', 'EmployeeModel','AttendanceModel'];

    public function index()
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->findAll();
        $employeeCount = $employeeModel->countAllResults();
        $designationModel = new Designation();
        $designations = $designationModel->findAll();
        $designationCount = $designationModel->countAllResults();
        $userStatus = session()->get('userStatus');
        

        $data = [
            'pageTitle' => 'Dashboard',
            'employee' => $employee,
            'employeeCount' => $employeeCount,
            'designationCount' => $designationCount,
            'userStatus' => $userStatus
        ];
        return view('backend/pages/home', $data);
    }

    public function logoutHandler(){
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('fail', 'You are logged out!');
        session()->destroy();

    // Optionally, clear cookies (if you set any manually)
    // setcookie('your_cookie_name', '', time() - 3600, '/'); // Clear the cookie

    // Redirect to the login page after logout
    return redirect()->route('admin.login.form')->with('success', 'You have been logged out successfully.');
    }
    public function profile(){
        $userStatus = session()->get('userStatus');
        $data = array(
            'pageTitle'=>'Profile',
            'userStatus' => $userStatus
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
    
            // Hash the new password using bcrypt
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    
            // Update the user password in the database
            $user->update($user_id, ['password' => $hashed_password]);
    
            return redirect()->back()->with('success', 'Password has been changed successfully.');
        }
    }
    
    
        

    /////!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    public function designation(){
        $designationModel = new Designation();
        $designations = $designationModel->findAll(); // Retrieve all designations from the database
        $userStatus = session()->get('userStatus');

        $data = [
            'pageTitle' => 'Designation',
            'designations' => $designations, // Pass the fetched designations to the view
            'userStatus' => $userStatus
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
        $userStatus = session()->get('userStatus');
        $data = [
            'pageTitle' => 'Position',
            'positions' => $positions,
            'userStatus' => $userStatus
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
        $userStatus = session()->get('userStatus');

        $data = [
            'pageTitle' => 'Employee',
            'employee' => $employees, // Pass the retrieved data to the view
            'userStatus' => $userStatus
        ];

        return view('backend/pages/employee', $data); // Load the view with data
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

   public function updateProfilePicture()
   {
       if ($this->request->isAJAX()) {
           $id = $this->request->getPost('id');

           // Handle file upload
           $file = $this->request->getFile('profile_picture');
           if ($file->isValid() && !$file->hasMoved()) {
               $newName = $file->getRandomName();
               $file->move(WRITEPATH . 'uploads', $newName);

               $this->employeeModel->update($id, ['profile_picture' => $newName]);
               return $this->response->setJSON(['success' => true, 'message' => 'Profile picture updated successfully.']);
           } else {
               return $this->response->setJSON(['success' => false, 'message' => 'File upload failed.']);
           }
       }

       return $this->response->setJSON(['success' => false, 'message' => 'Invalid request.']);
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
    
   public function update_profile_picture()
   {
       $id = $this->request->getPost('id');
       $employeeModel = new EmployeeModel();
   
       if ($imagefile = $this->request->getFile('profile_picture')) {
           if ($imagefile->isValid() && !$imagefile->hasMoved()) {
               $newName = $imagefile->getRandomName();
               $imagefile->move(ROOTPATH . 'public/backend/images/users', $newName);
   
               $data = ['picture' => $newName];
   
               if ($employeeModel->update($id, $data)) {
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
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($employeeId);

        if ($employee) {
            return $this->response->setJSON($employee);
        } else {
            return $this->response->setStatusCode(404, 'Employee not found');
        }
    }
   
   use ResponseTrait;

   public function getEmployeeData()
   {
       $employeeModel = new EmployeeModel();
       $employees = $employeeModel->findAll();
   
       $maleData = [];
       $femaleData = [];
       $years = [];
   
       foreach ($employees as $employee) {
           $dob = $employee['dob']; // Assuming 'dob' is the date of birth field
           $year = date('Y', strtotime($dob));
   
           if (!isset($maleData[$year])) {
               $maleData[$year] = 0;
               $femaleData[$year] = 0;
           }
   
           if ($employee['sex'] == 'Male') {
               $maleData[$year]++;
           } else {
               $femaleData[$year]++;
           }
   
           if (!in_array($year, $years)) {
               $years[] = $year;
           }
       }
   
       sort($years);
   
       $response = [
           'male' => array_values(array_intersect_key($maleData, array_flip($years))),
           'female' => array_values(array_intersect_key($femaleData, array_flip($years))),
           'years' => $years,
       ];
   
       return $this->response->setJSON($response);
   }
   

    
    
    /////&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&



public function attendance()
{
    $employeeModel = new EmployeeModel();
    $employees = $employeeModel->findAll();
    
    $designationModel = new Designation();
    $designations = $designationModel->findAll();
    
    $positionModel = new Position();
    $positions = $positionModel->findAll();
    
    $attendanceModel = new AttendanceModel();
    
    // Fetch attendance records including pm_sign_out
    $attendances = $attendanceModel->findAll(); // Adjust this to include pm_sign_out if necessary
    $userStatus = session()->get('userStatus');

    // Debugging: Log the fetched attendances
    log_message('debug', 'Fetched attendances: ' . print_r($attendances, true));

    $data = [
        'pageTitle' => 'Attendance',
        'employees' => $employees,
        'designations' => $designations,
        'positions' => $positions,
        'attendances' => $attendances, // Include attendance records here
        'userStatus' => $userStatus
    ];
    
    return view('backend/pages/attendance', $data);
}


            public function saveAttendance()
                {
                    $attendanceModel = new AttendanceModel();
                    $employeeModel = new EmployeeModel();
                    $designationModel = new Designation();
                    $positionModel = new Position();

                    $employeeId = $this->request->getPost('employee');
                    $officeId = $this->request->getPost('office');
                    $positionId = $this->request->getPost('position');
                    
                    // Fetch employee details
                    $employee = $employeeModel->find($employeeId);
                    $designation = $designationModel->find($officeId);
                    $position = $positionModel->find($positionId);

                    if (!$employee || !isset($employee['firstname']) || !isset($employee['lastname'])) {
                        return $this->response->setJSON(['success' => false, 'message' => 'Employee not found or missing data.']);
                    }

                    if (!$designation || !isset($designation['name'])) {
                        return $this->response->setJSON(['success' => false, 'message' => 'Office not found or missing data.']);
                    }

                    if (!$position || !isset($position['position_name'])) {
                        return $this->response->setJSON(['success' => false, 'message' => 'Position not found or missing data.']);
                    }

                    // Check if the employee has an existing attendance record
                    $attendance = $attendanceModel->where('name', $employee['firstname'] . ' ' . $employee['lastname'])->first();

                    // Prepare attendance data
                    $currentTime = date('Y-m-d H:i:s');
                    $data = [
                        'name' => $employee['firstname'] . ' ' . $employee['lastname'],
                        'office' => $designation['name'],
                        'position' => $position['position_name'],
                        'sign_in' => $currentTime, // Record sign-in time
                        'sign_out' => null, // Initially null for sign-out
                    ];
                    // If attendance record exists, update sign-out time
                        if ($attendance) {
                            // Check if AM sign-in exists and sign-out is null
                            if ($attendance['sign_in'] !== null && $attendance['sign_out'] === null) {
                                // Update sign-out for the AM session
                                $attendanceModel->update($attendance['id'], [
                                    'sign_out' => $currentTime, // Record AM sign-out time
                                ]);
                                return $this->response->setJSON(['success' => true, 'message' => 'AM sign-out recorded successfully.']);
                            }

                            // Handle PM sign-in and sign-out
                            // Check if PM sign-in is null and PM sign-out is null (only then sign-in for PM)
                            if (is_null($attendance['pm_sign_in']) && is_null($attendance['pm_sign_out'])) {
                                $attendanceModel->update($attendance['id'], [
                                    'pm_sign_in' => $currentTime, // Record PM sign-in time
                                ]);
                                return $this->response->setJSON(['success' => true, 'message' => 'PM sign-in recorded successfully.']);
                            }

                            // If PM sign-in exists but PM sign-out is still null, allow PM sign-out
                            if (!is_null($attendance['pm_sign_in']) && is_null($attendance['pm_sign_out'])) {
                                $attendanceModel->update($attendance['id'], [
                                    'pm_sign_out' => $currentTime, // Record PM sign-out time
                                ]);
                                return $this->response->setJSON(['success' => true, 'message' => 'PM sign-out recorded successfully.']);
                            }

                            // If PM sign-out has already been recorded, don't allow further sign-out
                            if (!is_null($attendance['pm_sign_out'])) {
                                return $this->response->setJSON(['success' => false, 'message' => 'PM sign-out already recorded for today.']);
                            }

                            // If none of the conditions match, return a generic error
                            return $this->response->setJSON(['success' => false, 'message' => 'Invalid attendance operation.']);
                        }
                    else {
                        // If no attendance record exists, create a new one
                        if ($attendanceModel->insert($data)) {
                            return $this->response->setJSON(['success' => true, 'message' => 'Sign-in recorded successfully.']);
                        } else {
                            return $this->response->setJSON(['success' => false, 'message' => 'Failed to record sign-in.']);
                        }
                    }
                }
                


            // public function saveAttendance()
            // {
            //     $attendanceModel = new AttendanceModel();
            //     $employeeModel = new EmployeeModel();
            //     $designationModel = new Designation();
            //     $positionModel = new Position();
            
            //     $employeeId = $this->request->getPost('employee');
            //     $officeId = $this->request->getPost('office');
            //     $positionId = $this->request->getPost('position');
            //     $isPM = $this->request->getPost('is_pm'); // New input to indicate if this is a PM sign-in
            
            //     // Fetch employee details
            //     $employee = $employeeModel->find($employeeId);
            //     $designation = $designationModel->find($officeId);
            //     $position = $positionModel->find($positionId);
            
            //     if (!$employee || !isset($employee['firstname']) || !isset($employee['lastname'])) {
            //         return $this->response->setJSON(['success' => false, 'message' => 'Employee not found or missing data.']);
            //     }
            
            //     if (!$designation || !isset($designation['name'])) {
            //         return $this->response->setJSON(['success' => false, 'message' => 'Office not found or missing data.']);
            //     }
            
            //     if (!$position || !isset($position['position_name'])) {
            //         return $this->response->setJSON(['success' => false, 'message' => 'Position not found or missing data.']);
            //     }
            
            //     // Check if the employee has already signed in
            //     $attendance = $attendanceModel->where('name', $employee['firstname'] . ' ' . $employee['lastname'])->first();
            
            //     if ($isPM) {
            //         // If signing in for PM
            //         if ($attendance) {
            //             // If the employee has already signed in for AM
            //             if ($attendance['sign_in'] === null) {
            //                 // Mark as absent for AM if they did not sign in
            //                 $attendanceModel->update($attendance['id'], [
            //                     'attendance' => 0, // Mark as absent
            //                     'pm_sign_in' => date('Y-m-d H:i:s'), // Record PM sign-in time
            //                 ]);
            //                 return $this->response->setJSON(['success' => true, 'message' => 'Marked as absent for AM and PM sign-in recorded successfully.']);
            //             } elseif ($attendance['pm_sign_in'] !== null) {
            //                 // PM sign-in already recorded
            //                 return $this->response->setJSON(['success' => false, 'message' => 'PM sign-in already recorded.']);
            //             } else {
            //                 // Update PM sign-in time
            //                 $attendanceModel->update($attendance['id'], [
            //                     'pm_sign_in' => date('Y-m-d H:i:s'), // Update PM sign-in time
            //                 ]);
            //                 return $this->response->setJSON(['success' => true, 'message' => 'PM sign-in recorded successfully.']);
            //             }
            //         } else {
            //             // If no attendance record exists, create one and mark as absent for AM
            //             $data = [
            //                 'name' => $employee['firstname'] . ' ' . $employee['lastname'],
            //                 'office' => $designation['name'],
            //                 'position' => $position['position_name'],
            //                 'attendance' => 0, // Mark as absent
            //                 'sign_in' => null,
            //                 'pm_sign_in' => date('Y-m-d H:i:s'), // Record PM sign-in time
            //                 'sign_out' => null,
            //                 'pm_sign_out' => null,
            //             ];
            //             $attendanceModel->insert($data);
            //             return $this->response->setJSON(['success' => true, 'message' => 'Marked as absent for AM and PM sign-in recorded successfully.']);
            //         }
            //     }
            
            //     // For AM sign-in
            //     if ($attendance) {
            //         if ($attendance['sign_in'] !== null) {
            //             return $this->response->setJSON(['success' => false, 'message' => 'AM sign-in already recorded.']);
            //         }
            //     }
            
            //     // Prepare data for AM sign-in
            //     $data = [
            //         'name' => $employee['firstname'] . ' ' . $employee['lastname'],
            //         'office' => $designation['name'],
            //         'position' => $position['position_name'],
            //         'attendance' => 1, // Assuming attendance value is 1 for sign-in
            //         'sign_in' => date('Y-m-d H:i:s'), // Update AM sign-in time
            //         'pm_sign_in' => null, // Initially null for PM sign-in
            //         'sign_out' => null, // Initially null for sign-out
            //         'pm_sign_out' => null, // Initially null for PM sign-out
            //     ];
            
            //     // Insert or update data into the database
            //     if ($attendance) {
            //         // Update existing attendance record
            //         $attendanceModel->update($attendance['id'], $data);
            //         return $this->response->setJSON(['success' => true, 'message' => 'AM sign-in recorded successfully.']);
            //     } else {
            //         // Insert new attendance record
            //         if ($attendanceModel->insert($data)) {
            //             return $this->response->setJSON(['success' => true, 'message' => 'AM sign-in recorded successfully.']);
            //         } else {
            //             return $this->response->setJSON(['success' => false, 'message' => 'Failed to record sign-in.']);
            //         }
            //     }
            // }
               
            public function signOut()
            {
                $attendanceModel = new AttendanceModel();
                $attendance = $attendanceModel->find($attendanceId); // Fetch the attendance record
            
                if ($session === 'am') {
                    // Check if AM sign out is already present
                    if (empty($attendance['sign_out'])) {
                        // If AM sign out is empty, mark it as signed out
                        $attendanceModel->update($attendanceId, [
                            'sign_out' => date('Y-m-d H:i:s') // Current timestamp for AM sign out
                        ]);
                        return json_encode(['success' => true, 'message' => 'AM Sign Out successful']);
                    } else {
                        return json_encode(['success' => false, 'message' => 'Already signed out for AM']);
                    }
                } elseif ($session === 'pm') {
                    // Check if PM sign out is already present
                    if (empty($attendance['pm_sign_out'])) {
                        // If PM sign out is empty, mark it as signed out
                        $attendanceModel->update($attendanceId, [
                            'pm_sign_out' => date('Y-m-d H:i:s') // Current timestamp for PM sign out
                        ]);
                        return json_encode(['success' => true, 'message' => 'PM Sign Out successful']);
                    } else {
                        return json_encode(['success' => false, 'message' => 'Already signed out for PM']);
                    }
                }
            
                return json_encode(['success' => false, 'message' => 'Invalid session']);
            }
            
            public function report()
            {
                // Load the AttendanceModel
                $attendanceModel = new \App\Models\AttendanceModel();
                $userStatus = session()->get('userStatus');
            
                // Number of records per page
                $perPage = 10;
            
                // Get the current page number from the query string
                $currentPage = $this->request->getVar('page') ?: 1;
            
                // Get filter dates from query string
                $startDate = $this->request->getVar('start_date');
                $endDate = $this->request->getVar('end_date');
            
                // Prepare filter conditions
                $filterConditions = [];
                if ($startDate) {
                    $startDateTime = $startDate . ' 00:00:00'; // Start of the day
                    $filterConditions['sign_in >='] = $startDateTime;
                }
                if ($endDate) {
                    $endDateTime = $endDate . ' 23:59:59'; // End of the day
                    $filterConditions['sign_in <='] = $endDateTime;
                }
            
                // Total number of records based on filters
                $totalRecords = $attendanceModel->where($filterConditions)->countAllResults();
            
                // Fetch data for the current page based on filters
                $attendances = $attendanceModel->where($filterConditions)
                                               ->orderBy('id', 'ASC')
                                               ->findAll($perPage, ($currentPage - 1) * $perPage);
            
                // Initialize pager manually
                $pager = \Config\Services::pager();
            
                // Calculate if there is a previous page
                $hasPrevious = ($currentPage > 1);
            
                // Calculate if there is a next page
                $hasNext = ($currentPage * $perPage < $totalRecords);
            
                // Prepare data to pass to the view
                $data = [
                    'attendances' => $attendances,
                    'pager' => $pager,
                    'perPage' => $perPage,
                    'currentPage' => $currentPage,
                    'hasPrevious' => $hasPrevious,
                    'hasNext' => $hasNext,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'pageTitle'=> 'Attendance Report',
                    'userStatus' => $userStatus
                ];
            
                // Load the view and pass the data
                return view('backend/pages/attendance_report', $data);
            }
            public function deleteAttendance()
            {
                $attendanceModel = new \App\Models\AttendanceModel();
                
                $id = $this->request->getPost('id'); // Retrieve the ID from POST data
            
                // Find the attendance record by ID
                $attendance = $attendanceModel->find($id);
            
                if ($attendance) {
                    // Delete the attendance record
                    if ($attendanceModel->delete($id)) {
                        return $this->response->setStatusCode(200)->setBody('Attendance record deleted successfully.');
                    }
                }
            
                return $this->response->setStatusCode(400)->setBody('Error deleting attendance record.');
            }
            
            



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function leave_type()
    {
        $userStatus = session()->get('userStatus');
        $data = [
            'pageTitle' => 'Leave Type',
            'leaveTypes' => $this->leaveTypeModel->findAll(), // Load leave types from the model
            'userStatus' => $userStatus
        ];
        return view('backend/pages/leave_type', $data);
     
    }
    public function save()
    {
        $data = [
            'l_name' => $this->request->getPost('l_name'),
            'l_description' => $this->request->getPost('l_description'),
            'l_days' => $this->request->getPost('l_days'),
        ];

        $id = $this->request->getPost('id');
        if ($id) {
            // Update existing leave type
            $this->leaveTypeModel->update($id, $data);
        } else {
            // Insert new leave type
            $this->leaveTypeModel->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Leave type saved successfully']);
    }
    public function updateLeave()
    {
        $leaveTypeModel = new \App\Models\leave_typeModel();
    
        // Define validation rules
        $validationRules = [
            'l_name' => 'required|max_length[255]',
            'l_description' => 'required|max_length[255]',
            'l_days' => 'required|integer|max_length[3]'
        ];
    
        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $this->validator->getErrors()
            ]);
        }
    
        // Get input data
        $id = $this->request->getPost('l_id');
        $name = $this->request->getPost('l_name');
        $description = $this->request->getPost('l_description');
        $days = $this->request->getPost('l_days');
    
        // Prepare data for database
        $data = [
            'l_name' => $name,
            'l_description' => $description,
            'l_days' => $days,
        ];
    
        if ($id) {
            try {
                // Editing an existing leave type
                $leaveTypeModel->update($id, $data);
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Leave type updated successfully.'
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'An error occurred while updating leave type.'
                ]);
            }
        }
    
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid leave type ID.'
        ]);
    }
    


    public function deleteLeave()
    {
        if ($this->request->isAJAX()) {
            $leaveTypeModel = new \App\Models\leave_typeModel(); // Adjust the model name if different
            $l_id = $this->request->getPost('l_id');
    
            if (!empty($l_id)) {
                if ($leaveTypeModel->delete($l_id)) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Leave type deleted successfully']);
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete leave type'])->setStatusCode(500);
                }
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid leave type ID'])->setStatusCode(400);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized access'])->setStatusCode(401);
        }
    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
        public function holidays()
        {
            $holidayModel = new \App\Models\HolidayModel();
            $holidays = $holidayModel->findAll(); // Fetch all holidays
            $userStatus = session()->get('userStatus');
            
            // Include holidays in the data array
            $data = array(
                'pageTitle' => 'Holidays',
                'userStatus' => $userStatus,
                'holidays' => $holidays // Pass holidays to the view
            );

            return view('backend/pages/holidays', $data); // Pass the data array to the view
        }

        public function create()
{
    $holidayModel = new \App\Models\HolidayModel();

    $name = $this->request->getPost('name');
    $date = $this->request->getPost('date');

    // Validate input
    if (empty($name) || empty($date)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Holiday name and date are required.'
        ]);
    }

    // Check for existing holiday on the same date
    $existingHoliday = $holidayModel->where('date', $date)->first();

    if ($existingHoliday) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'A holiday already exists on this date. Please choose a different date.'
        ]);
    }

    // Add new holiday to the database
    $data = [
        'name' => $name,
        'date' => $date,
    ];

    if ($holidayModel->insert($data)) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Holiday added successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to add holiday. Please try again.'
        ]);
    }
}

public function updateHolidays()
{
    $holidayModel = new \App\Models\HolidayModel();

    $id = $this->request->getPost('id');
    $name = $this->request->getPost('name');
    $date = $this->request->getPost('date');

    // Validate input
    if (empty($id) || empty($name) || empty($date)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid input data. Please fill out all fields.'
        ]);
    }

    // Update holiday in the database
    $data = [
        'name' => $name,
        'date' => $date,
    ];

    if ($holidayModel->update($id, $data)) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Holiday updated successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update holiday. Please try again.'
        ]);
    }
}
public function cancelHolidays()
{
    $holidayModel = new \App\Models\HolidayModel();

    $id = $this->request->getPost('id');

    // Validate input
    if (empty($id)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid holiday ID.'
        ]);
    }

    // Delete holiday from the database
    if ($holidayModel->delete($id)) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Holiday cancelled successfully.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to cancel holiday. Please try again.'
        ]);
    }
}


        
////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
public function leave_application()
{
    // Load the models
    $leaveTypeModel = new leave_typeModel(); // Ensure the correct class name
    $leaveApplicationModel = new LeaveApplicationModel();
    $employeeModel = new EmployeeModel();
    

    // Fetch leave applications with details
    $leaveApplications = $leaveApplicationModel->getLeaveApplicationsWithDetails($leaveTypeModel, $employeeModel);
    
    // Retrieve all leave types
    $leaveTypes = $leaveTypeModel->findAll();

    // Fetch employee names
    $employees = $employeeModel->getEmployeeNames();

    // Get user status from session
    $userStatus = session()->get('userStatus');

    // Prepare data for the view
    $data = [
        'pageTitle' => 'Leave Application',
        'leaveTypes' => $leaveTypes,
        'employees' => $employees,
        'userStatus' => $userStatus,
        'leaveApplications' => $leaveApplications // Pass leave applications with details
    ];

    // Load the view with data
    return view('backend/pages/leave_application', $data);
}

    public function submitLeaveApplication()
        {
            log_message('info', 'Submit Leave Application started.');

            $leaveApplicationModel = new LeaveApplicationModel();
            
            $validation = \Config\Services::validation();
            $validation->setRules([
                'la_name' => 'required|integer',
                'la_type' => 'required|integer',
                'la_start' => 'required|valid_date',
                'la_end' => 'required|valid_date',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                log_message('error', 'Validation errors: ' . json_encode($validation->getErrors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validation->getErrors()
                ]);
            }

            $la_start = $this->request->getPost('la_start');
            $la_end = $this->request->getPost('la_end');

            if (strtotime($la_start) > strtotime($la_end)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'End date must be after start date.'
                ]);
            }

            $data = [
                'la_name' => $this->request->getPost('la_name'),
                'la_type' => $this->request->getPost('la_type'),
                'la_start' => $la_start,
                'la_end' => $la_end,
                'status' => 'Pending',
            ];

            if ($leaveApplicationModel->insert($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Leave application submitted successfully'
                ]);
            } else {
                log_message('error', 'Database insert failed: ' . json_encode($leaveApplicationModel->errors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to submit leave application. Please try again.'
                ]);
            }
        }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function setting(){
        $userStatus = session()->get('userStatus');

        $data = array(
        'pageTitle'=>'Setting',
        'userStatus' => $userStatus
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

    public function getPendingLeaveNotifications()
    {
        $leaveApplicationModel = new LeaveApplicationModel();
        
        // Fetch pending leave applications
        $pendingApplications = $leaveApplicationModel->where('status', 'Pending')->findAll() ?? [];
    
        // Prepare notifications
        $notifications = [];
        foreach ($pendingApplications as $application) {
            $employee = $this->employeeModel->find($application['la_name']);
            $leaveType = $this->leaveTypeModel->find($application['la_type']);
    
            $notifications[] = [
                'id' => $application['la_id'],
                'employee_name' => $employee ? $employee['firstname'] . ' ' . $employee['lastname'] : 'Unknown Employee',
                'leave_type' => $leaveType ? $leaveType['l_name'] : 'Unknown Leave Type',
                'start_date' => $application['la_start'],
                'end_date' => $application['la_end'],
            ];
        }
    
        // Pass notifications to the view
        return view('backend/pages/notifications', ['pendingApplications' => $notifications]);
    }
    

}
