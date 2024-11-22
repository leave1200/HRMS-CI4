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
        $employeeCount = $employeeModel->where('result !=', 'Pending')->countAllResults();
        $employeeCounts = $employeeModel->where('result !=', 'Hired')->countAllResults();
        $designationModel = new Designation();
        $designations = $designationModel->findAll();
        $designationCount = $designationModel->countAllResults();
        $userStatus = session()->get('userStatus');
        $leaveModel = new LeaveApplicationModel();
        $approvedCount = $leaveModel->countApprovedLeaves();
        $pendingCount = $leaveModel->countPendingLeaves();
        $positionModel = new \App\Models\Position();
        $positions = $positionModel->findAll();
        $positionCount = $positionModel->countAllResults();
        $attendanceModel = new AttendanceModel();
        $attendances = $attendanceModel->findAll();
        $amAttendanceRecords = $attendanceModel->where('sign_in IS NOT NULL')->countAllResults();
        $pmAttendanceRecords = $attendanceModel->where('pm_sign_in IS NOT NULL')->countAllResults();
        


        $data = [
            'pageTitle' => 'Dashboard',
            'employee' => $employee,
            'employeeCount' => $employeeCount,
            'employeeCounts' => $employeeCounts,
            'designationCount' => $designationCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'positionCount' => $positionCount,
            'amAttendanceRecords' => $amAttendanceRecords,
            'pmAttendanceRecords' => $pmAttendanceRecords,
            'userStatus' => $userStatus
        ];
        return view('backend/pages/home', $data);
    }
    public function getUserFileUploads()
{
    $userId = session()->get('user_id'); // Ensure the session holds the logged-in user's ID
    $fileModel = new \App\Models\FileModel();

    // Query to get file upload counts grouped by date
    $fileData = $fileModel->select("DATE(uploaded_at) as upload_date, COUNT(*) as file_count")
                          ->where('user_id', $userId)
                          ->groupBy('upload_date')
                          ->orderBy('upload_date', 'ASC')
                          ->findAll();

    return $this->response->setJSON($fileData);
}
// public function getApprovedLeaves()
// {
//     $userId = session()->get('user_id'); // Get logged-in user's ID from the session
//     if (!$userId) {
//         return $this->response->setJSON(['success' => false, 'message' => 'User not logged in.']);
//     }

//     $leaveModel = new \App\Models\LeaveApplicationModel();

//     // Query to count approved leave applications by date
//     $leaveData = $leaveModel->select("DATE(la_start) as leave_date, COUNT(*) as leave_count")
//                             ->where('la_name', $userId) // Use la_name to filter by user ID
//                             ->where('status', 'Approved') // Filter only approved leaves
//                             ->groupBy('leave_date')
//                             ->orderBy('leave_date', 'ASC')
//                             ->findAll();

//     if (empty($leaveData)) {
//         return $this->response->setJSON(['success' => true, 'data' => [], 'message' => 'No approved leaves found.']);
//     }

//     return $this->response->setJSON(['success' => true, 'data' => $leaveData]);
// }
public function getUserLeaveApplications()
{
    $userId = session()->get('user_id'); // Get logged-in user's ID from the session
    if (!$userId) {
        return $this->response->setJSON(['success' => false, 'message' => 'User not logged in.']);
    }

    $leaveModel = new \App\Models\LeaveApplicationModel();

    // Query to count leave applications for the logged-in user grouped by date and status
    $leaveData = $leaveModel->select("DATE(la_start) as leave_date, status, COUNT(*) as leave_count")
                            ->where('la_name', $userId) // Filter by the logged-in user's ID
                            ->groupBy('leave_date, status')
                            ->orderBy('leave_date', 'ASC')
                            ->findAll();

    if (empty($leaveData)) {
        return $this->response->setJSON(['success' => true, 'data' => [], 'message' => 'No leave applications found for the user.']);
    }

    return $this->response->setJSON(['success' => true, 'data' => $leaveData]);
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


    
    
        

    /////!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


    public function designation(){
        $designationModel = new Designation();
        $designations = $designationModel->findAll(); // Retrieve all designations from the database
        $userStatus = session()->get('userStatus');
        if ($userStatus !== 'ADMIN') {
            return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
        }

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
        if ($userStatus !== 'ADMIN') {
            return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
        }
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
        $userStatus = session()->get('userStatus');
        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->findAll();
        if ($userStatus !== 'ADMIN') {
            return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
        }
    
    
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
           'age' => 'required',
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
           'age' => $request->getPost('age'),
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
               'age' => $this->request->getPost('age'),
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
       $userStatus = session()->get('userStatus');
       if ($userStatus !== 'ADMIN') {
        return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
    }

       $data = [
           'pageTitle' => 'Employee List',
           'employee' => $employee,
           'userStatus' => $userStatus
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
   public function getEmployeeGenderData()
   {
    $model = new EmployeeModel();
    return $this->response->setJSON($model->getGenderCount());
   }
   public function hire_employee()
   {
       $model = new EmployeeModel();
       $input = $this->request->getJSON(); // Get JSON data
   
       // Check if the ID is provided
       if (isset($input->id) && $model->hireEmployee($input->id)) {
           return $this->response->setJSON(['success' => true]);
       } else {
           return $this->response->setJSON(['success' => false], 400);
       }
   }
   
  public function pendingemployeelist()
   {
       $employeeModel = new EmployeeModel();
       $userStatus = session()->get('userStatus');
       $employee = $employeeModel->findAll();
       if ($userStatus !== 'ADMIN') {
        return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
    }

       $data = [
           'pageTitle' => 'Employee List',
           'employee' => $employee,
           'userStatus' => $userStatus
       ];
       return view('backend/pages/pendingemployee',$data);
   }
   
    
    /////&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&



    public function attendance()
    {
         // Get the logged-in user's ID
         $loggedInUserId = session()->get('userId'); // Ensure 'userId' is set in session during login
    
         // Fetch attendance records only for the logged-in user
         $attendances = $attendanceModel->where('att', $loggedInUserId)->findAll(); 
        // Fetch users instead of employees
        $userModel = new User(); // Assuming your model for users is User.php
        $users = $userModel->findAll(); // Fetch all users
        
        $designationModel = new Designation();
        $designations = $designationModel->findAll();
        
        $positionModel = new Position();
        $positions = $positionModel->findAll();
        
        $attendanceModel = new AttendanceModel();
        
        // Fetch attendance records, including pm_sign_out if necessary
        $attendances = $attendanceModel->findAll(); // Adjust this to include pm_sign_out if necessary
        $userStatus = session()->get('userStatus');
    
        $data = [
            'pageTitle' => 'Attendance',
            'users' => $users, // Replace 'employees' with 'users'
            'designations' => $designations,
            'positions' => $positions,
            'attendances' => $attendances, // Include attendance records here
            'userStatus' => $userStatus
        ];
        
        return view('backend/pages/attendance', $data);
    }
    // public function attendance()
    // {
    //     $userModel = new User(); 
    //     $designationModel = new Designation();
    //     $positionModel = new Position();
    //     $attendanceModel = new AttendanceModel();
    
    //     // Get the logged-in user's ID
    //     $loggedInUserId = session()->get('userId'); // Ensure 'userId' is set in session during login
    
    //     // Fetch attendance records only for the logged-in user
    //     $attendances = $attendanceModel->where('att', $loggedInUserId)->findAll(); 
    
    //     // Get additional data if required
    //     $users = $userModel->findAll(); 
    //     $designations = $designationModel->findAll();
    //     $positions = $positionModel->findAll();
    //     $userStatus = session()->get('userStatus'); 
    
    //     $data = [
    //         'pageTitle' => 'Attendance',
    //         'users' => $users,
    //         'designations' => $designations,
    //         'positions' => $positions,
    //         'attendances' => $attendances,
    //         'userStatus' => $userStatus,
    //     ];
    
    //     return view('backend/pages/attendance', $data);
    // }
    
    


// public function saveAttendance()
// {
//     $attendanceModel = new AttendanceModel();
//     $userModel = new User(); // Assuming UserModel is used for user-related data
//     $designationModel = new Designation();
//     $positionModel = new Position();

//     // Get user, office, and position data from POST request
//     $userId = $this->request->getPost('user'); // Assuming 'employee' is passed as 'userId' in the front-end
//     $officeId = $this->request->getPost('office');
//     $positionId = $this->request->getPost('position');

//     // Fetch user details
//     $user = $userModel->find($userId); // Get user from the UserModel
//     $designation = $designationModel->find($officeId);
//     $position = $positionModel->find($positionId);

//     // Validate user, office, and position data
//     if (!$user || !isset($user['name'])) {
//         return $this->response->setJSON(['success' => false, 'message' => 'User not found or missing data.']);
//     }

//     if (!$designation || !isset($designation['name'])) {
//         return $this->response->setJSON(['success' => false, 'message' => 'Office not found or missing data.']);
//     }

//     if (!$position || !isset($position['position_name'])) {
//         return $this->response->setJSON(['success' => false, 'message' => 'Position not found or missing data.']);
//     }

//     // Prepare attendance data
//     $currentTime = date('Y-m-d H:i:s');
//     $attendanceData = [
//         'name' => $user['name'], // Use 'name' from the user model
//         'office' => $designation['name'],
//         'position' => $position['position_name'],
//         'sign_in' => null, // Set to null for AM sign-in initially
//         'sign_out' => null, // Initially null for AM sign-out
//         'pm_sign_in' => null, // Initially null for PM sign-in
//         'pm_sign_out' => null, // Initially null for PM sign-out
//     ];

//     // Insert new attendance record
//     if ($this->request->getPost('pm_sign_in')) {
//         $attendanceData['pm_sign_in'] = $currentTime; // Record PM sign-in time
//     } else {
//         $attendanceData['sign_in'] = $currentTime; // Record AM sign-in time
//     }

//     // Insert the new attendance record regardless of previous records
//     if ($attendanceModel->insert($attendanceData)) {
//         return $this->response->setJSON(['success' => true, 'message' => 'Attendance recorded successfully.']);
//     } else {
//         return $this->response->setJSON(['success' => false, 'message' => 'Failed to record attendance.']);
//     }
// }
public function saveAttendance()
{
    $attendanceModel = new AttendanceModel();
    $userModel = new User(); // Assuming UserModel is used for user-related data
    $designationModel = new Designation();
    $positionModel = new Position();

    // Get user, office, and position data from POST request
    $userId = $this->request->getPost('user'); // Assuming 'user' is passed in the front-end
    $officeId = $this->request->getPost('office');
    $positionId = $this->request->getPost('position');

    // Fetch user details
    $user = $userModel->find($userId); // Get user from the UserModel
    $designation = $designationModel->find($officeId);
    $position = $positionModel->find($positionId);

    // Validate user, office, and position data
    if (!$user || !isset($user['name'])) {
        return $this->response->setJSON(['success' => false, 'message' => 'User not found or missing data.']);
    }

    if (!$designation || !isset($designation['name'])) {
        return $this->response->setJSON(['success' => false, 'message' => 'Office not found or missing data.']);
    }

    if (!$position || !isset($position['position_name'])) {
        return $this->response->setJSON(['success' => false, 'message' => 'Position not found or missing data.']);
    }

    // Prepare attendance data
    $currentTime = date('Y-m-d H:i:s');
    $attendanceData = [
        'name' => $user['name'], // Use 'name' from the user model
        'office' => $designation['name'],
        'position' => $position['position_name'],
        'attendance' => 'Present', // Example value; modify based on logic
        'att' => $userId, // Store the user ID in the 'att' field
        'sign_in' => null, // Set to null for AM sign-in initially
        'sign_out' => null, // Initially null for AM sign-out
        'pm_sign_in' => null, // Initially null for PM sign-in
        'pm_sign_out' => null, // Initially null for PM sign-out
    ];

    // Insert new attendance record
    if ($this->request->getPost('pm_sign_in')) {
        $attendanceData['pm_sign_in'] = $currentTime; // Record PM sign-in time
    } else {
        $attendanceData['sign_in'] = $currentTime; // Record AM sign-in time
    }

    // Insert the new attendance record regardless of previous records
    if ($attendanceModel->insert($attendanceData)) {
        return $this->response->setJSON(['success' => true, 'message' => 'Attendance recorded successfully.']);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to record attendance.']);
    }
}


public function pmSave()
{
    $attendanceModel = new AttendanceModel();
    
    // Get attendance ID from the POST request
    $attendanceId = $this->request->getPost('attendance_id');

    // Validate the attendance ID
    if (!$attendanceId) {
        return $this->response->setJSON(['success' => false, 'message' => 'Attendance ID is required.']);
    }

    // Find the attendance record
    $attendance = $attendanceModel->find($attendanceId);
    
    if (!$attendance) {
        return $this->response->setJSON(['success' => false, 'message' => 'Attendance record not found.']);
    }

    // Check if PM sign-in is already recorded
    if (!is_null($attendance['pm_sign_in'])) {
        return $this->response->setJSON(['success' => false, 'message' => 'PM sign-in already recorded for today.']);
    }

    // Get current time
    $currentTime = date('Y-m-d H:i:s');

    // Update the attendance record with PM sign-in time
    if (!$attendanceModel->update($attendanceId, ['pm_sign_in' => $currentTime])) {
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to record PM sign-in.']);
    }

    return $this->response->setJSON(['success' => true, 'message' => 'PM sign-in recorded successfully.']);
}



               
            public function signOut()
            {
                $attendanceModel = new AttendanceModel();
                $attendanceId = $this->request->getPost('id'); // Fetch attendance ID
                $session = $this->request->getPost('session'); // Fetch session (am/pm)
                $attendance = $attendanceModel->find($attendanceId); // Fetch the attendance record
            
                if ($session === 'am') {
                    if (empty($attendance['sign_out'])) {
                        // Update for AM sign out
                        $attendanceModel->update($attendanceId, ['sign_out' => date('Y-m-d H:i:s')]);
                        return json_encode(['success' => true, 'message' => 'AM Sign Out successful']);
                    } else {
                        return json_encode(['success' => false, 'message' => 'Already signed out for AM']);
                    }
                } elseif ($session === 'pm') {
                    if (empty($attendance['pm_sign_out'])) {
                        // Update for PM sign out
                        $attendanceModel->update($attendanceId, ['pm_sign_out' => date('Y-m-d H:i:s')]);
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
                if ($userStatus !== 'ADMIN') {
                    return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
                }
            
                // Number of records per page
                $perPage = 10;
            
                // Get the current page number from the query string
                $currentPage = $this->request->getVar('page') ?: 1;
            
                // Get filter dates from query string
                $name = $this->request->getVar('name');
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
            public function archiveAttendance()
            {
                $attendanceModel = new \App\Models\AttendanceModel();
                
                $id = $this->request->getPost('id'); // Retrieve the ID from POST data
            
                // Find the attendance record by ID
                $attendance = $attendanceModel->find($id);
            
                if ($attendance) {
                    // Update the attendance record to archive it
                    if ($attendanceModel->update($id, ['att' => 'archive'])) {
                        return $this->response->setStatusCode(200)->setBody('Attendance record archived successfully.');
                    }
                }
            
                return $this->response->setStatusCode(400)->setBody('Error archiving attendance record.');
            }
            public function archived()
            {
                $attendanceModel = new \App\Models\AttendanceModel();
                $archivedRecords = $attendanceModel->where('att', 'archive')->findAll(); // Fetch archived records

                return $this->response->setJSON($archivedRecords);
            }

            
            
            



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function leave_type()
    {
        $userStatus = session()->get('userStatus');
        if ($userStatus !== 'ADMIN') {
            return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
        }
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
    public function approveLeave()
    {
        $leaveModel = new LeaveApplicationModel();

        // Get the leave ID and new status from the request
        $leaveId = $this->request->getPost('la_id');
        $status = $this->request->getPost('status');

        // Validate input
        if (empty($leaveId) || empty($status)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid input.',
            ]);
        }

        // Update the leave application in the database
        $data = [
            'status' => $status,
        ];

        $updated = $leaveModel->update($leaveId, $data);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Leave application approved successfully.',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to approve leave application.',
            ]);
        }
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
            if ($userStatus !== 'ADMIN') {
                return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
            }
            
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
//////////////////////////////////////////////////////////////////////////////////////////////////////

public function leave_application()
{
    $leaveTypeModel = new leave_typeModel();
    $leaveApplicationModel = new LeaveApplicationModel();
    $userModel = new User();

    // Get the logged-in user's ID from the session
    $loggedInUserId = session()->get('user_id');  // Assuming the user ID is stored in the session

    // Fetch leave applications for the logged-in user
    $data = [
        'pageTitle' => 'Leave Application',
        'leaveTypes' => $leaveTypeModel->findAll(),
        'users' => $userModel->select('id, name')->findAll(),
        'userStatus' => session()->get('userStatus'),
        'leaveApplications' => $leaveApplicationModel->getLeaveApplicationsWithDetails($leaveTypeModel, $userModel, $loggedInUserId),
    ];

    return view('backend/pages/leave_application', $data);
}

public function pendingleave(){
      // Load the models
    $leaveTypeModel = new leave_typeModel(); // Ensure the correct class name
    $leaveApplicationModel = new LeaveApplicationModel();
    $userModel = new User(); // Load the User model
    $userStatus = session()->get('userStatus');
    if ($userStatus !== 'ADMIN') {
        return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
    }
    
    // Fetch leave applications with details
    $leaveApplications = $leaveApplicationModel->getLeaveApplications($leaveTypeModel, $userModel);
    
    // Retrieve all leave types
    $leaveTypes = $leaveTypeModel->findAll();

    // Fetch user names
    $users = $userModel->select('id, name')->findAll();

    // Prepare data for the view
    $data = [
        'pageTitle' => 'Leave Application',
        'leaveTypes' => $leaveTypes,
        'users' => $users, // Pass users to the view
        'userStatus' => $userStatus,
        'leaveApplications' => $leaveApplications // Pass leave applications with details
    ];


// Load the view with data
return view('backend/pages/pendingleave', $data);

}
public function rejectLeave()
{
    // $leaveApplicationModel = new LeaveApplicationModel();
    // $laId = $this->request->getPost('la_id');

    // // Attempt to delete the leave application
    // if ($leaveApplicationModel->delete($laId)) {
    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'message' => 'Leave application has been successfully Rejected.'
    //     ]);
    // } else {
    //     return $this->response->setJSON([
    //         'status' => 'error',
    //         'message' => 'Failed to Reject the leave application. Please try again.'
    //     ]);
    // }
    $leaveApplicationModel = new LeaveApplicationModel();
    $laId = $this->request->getPost('la_id');

    // Attempt to update the leave application status to "Cancelled"
    $updated = $leaveApplicationModel->update($laId, [
        'status' => 'Rejected'
    ]);

    if ($updated) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Leave application has been successfully rejected.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to reject the leave application. Please try again.'
        ]);
    }
}


public function cancelLeave()
{
    // $leaveApplicationModel = new LeaveApplicationModel();
    // $laId = $this->request->getPost('la_id');

    // // Attempt to delete the leave application
    // if ($leaveApplicationModel->delete($laId)) {
    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'message' => 'Leave application has been successfully Canceled.'
    //     ]);
    // } else {
    //     return $this->response->setJSON([
    //         'status' => 'error',
    //         'message' => 'Failed to Cancel the leave application. Please try again.'
    //     ]);
    // }
    $leaveApplicationModel = new LeaveApplicationModel();
    $laId = $this->request->getPost('la_id');

    // Attempt to update the leave application status to "Cancelled"
    $updated = $leaveApplicationModel->update($laId, [
        'status' => 'Cancelled'
    ]);

    if ($updated) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Leave application has been successfully cancelled.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to cancel the leave application. Please try again.'
        ]);
    }
}


//////////////////////////////////////////////////////////////////////////
public function submitLeaveApplication()
{
    log_message('info', 'Submit Leave Application started.');

    $leaveApplicationModel = new LeaveApplicationModel();
    $holidayModel = new HolidayModel(); // Load the HolidayModel
    
    $validation = \Config\Services::validation();
    $validation->setRules([
        'la_name' => 'required|integer',
        'la_type' => 'required|integer',
        'la_start' => 'required|valid_date',
        'la_end' => 'required|valid_date', // The user selects start and end dates
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

    // Calculate the number of leave days requested (excluding holidays)
    $total_leave_days = $this->calculateWorkingDays($la_start, $la_end, $holidayModel);

    // Adjust the end date so that the total number of leave days is correct
    $la_end = $this->adjustLeaveEndDate($la_start, $total_leave_days, $holidayModel);

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

private function calculateWorkingDays($start_date, $end_date, $holidayModel)
{
    $current_date = $start_date;
    $working_days = 0;

    // Count working days between start and end dates (excluding holidays)
    while (strtotime($current_date) <= strtotime($end_date)) {
        if (!$holidayModel->where('date', $current_date)->first()) {
            $working_days++; // Count only non-holidays
        }
        $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
    }

    return $working_days;
}

private function adjustLeaveEndDate($start_date, $total_leave_days, $holidayModel)
{
    $current_date = $start_date;
    $days_counted = 0;

    // Adjust the end date so that it accounts for holidays
    while ($days_counted < $total_leave_days) {
        if (!$holidayModel->where('date', $current_date)->first()) {
            $days_counted++; // Only count non-holidays
        }
        $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
    }

    return date('Y-m-d', strtotime($current_date . ' -1 day')); // Return the correct end date
}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function setting(){
        $userStatus = session()->get('userStatus');
        if ($userStatus !== 'ADMIN') {
            return redirect()->to('/forbidden'); // Or whatever route you choose for unauthorized access
        }

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

    public function notifications()
    {
        $employeeModel = new EmployeeModel();
    
        // Fetch employees whose result is "Pending"
        $pendingEmployees = $employeeModel->where('result', 'Pending')->findAll();
    
        // Pass the pending employees to the view
        return view('backend/pages/pendingemployee', ['pendingEmployees' => $pendingEmployees]);
    }
    public function fetchPendingResults()
        {
            $employeeModel = new EmployeeModel();
            
            // Fetch employees with a pending result
            $pendingEmployees = $employeeModel->where('result', 'Pending')->findAll();

            // Format the response
            $data = [];
            foreach ($pendingEmployees as $employee) {
                $data[] = [
                    'firstname' => $employee['firstname'],
                    'lastname' => $employee['lastname']
                ];
            }

            return $this->response->setJSON($data);
        }
        public function deleteuser()
        {
            if ($this->request->isAJAX()) {
                $userModel = new \App\Models\User();
                $id = $this->request->getPost('id');
        
                if (!empty($id) && $userModel->find($id)) {
                    if ($userModel->delete($id)) {
                        return $this->response->setJSON(['status' => 'success', 'message' => 'User deleted successfully']);
                    } else {
                        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete user'])->setStatusCode(500);
                    }
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid user ID'])->setStatusCode(400);
                }
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized access'])->setStatusCode(401);
            }
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        public function terms(){
            // Get the logged-in user's ID from the session
            $userId = session()->get('user_id'); // Replace 'user_id' with the actual session variable you're using
            $userStatus = session()->get('userStatus');
        
            // Pass the user ID to the view
            $data = array(
                'pageTitle' => 'Terms and Condition',
                'userId' => $userId, // Add the user ID to the data array
                'userStatus' => $userStatus
            );
            
            return view('backend/pages/terms', $data);
        }

}
