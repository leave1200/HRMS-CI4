<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('admin', static function($routes){

    $routes->group('', ['filter'=>'cifilter:auth'], static function($routes){
        //$routes->view('example-page','example-page');
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        ///////profile
        $routes->get('profile','AdminController::profile',['as'=>'admin.profile']);
        $routes->post('update-personal-details','AdminController::updatePersonalDetails',['as'=>'update-personal-details']);
        $routes->post('update-profile-picture','AdminController::updatePersonalPictures',['as'=>'update-profile-picture']);  
        $routes->post('change-password','AdminController::changePassword',['as'=>'change-password']);
        /////designation
        $routes->post('designation_save','AdminController::designationSave', ['as' => 'designation_save']);
        $routes->get('designation','AdminController::designation',['as'=>'admin.designation']);
        $routes->post('delete_designation', 'AdminController::deleteDesignation', ['as' => 'delete_designation']);
        $routes->post('update_designation', 'AdminController::updateDesignation', ['as' => 'update_designation']);
        ///////////position
        $routes->get('position','AdminController::position',['as'=>'admin.position']);
        $routes->post('position_save', 'AdminController::positionSave', ['as' => 'position_save']);
        $routes->post('update_position', 'AdminController::updatePosition', ['as' => 'update_position']);
        $routes->post('delete_position', 'AdminController::deletePosition', ['as' => 'delete_position']);
        //////employeeee
        $routes->get('employee','AdminController::employee',['as'=>'admin.employee']);
        $routes->post('employee_save', 'AdminController::saveEmployee',['as' => 'employee_save']);
        $routes->get('employeelist','AdminController::employeelist',['as'=>'admin.employeelist']);
        $routes->post('delete_employee', 'AdminController::deleteEmployee', ['as' => 'delete_employee']);
        $routes->post('update_employee', 'AdminController::updateEmployee', ['as' => 'update_employee']);
        $routes->get('employee_report','AdminController::employee_report',['as'=>'admin.employee_report']);
        $routes->post('employee_view', 'AdminController::getEmployee',['as' => 'employee_view']);
        $routes->post('update_profile_picture', 'AdminController::update_profile_picture', ['as' => 'update_profile_picture']);
        $routes->post('update_employee', 'AdminController::update_employee');
        $routes->post('update_personal_details', 'AdminController::updatePersonalDetail',['as' => 'update_personal_details']);
        $routes->post('update_educational_background', 'AdminController::updateEducationalBackground',['as' => 'update_educational_background']);
        $routes->post('update_interview', 'AdminController::updateInterview',['as' => 'update_interview']);
        $routes->post('update_remarks', 'AdminController::updateRemarks',['as' => 'update_remarks']);
        $routes->get('employee/getEmployeeData', 'AdminController::getEmployeeData');



        //////attendance
        $routes->get('attendance', 'AdminController::attendance', ['as' => 'admin.attendance']);
        $routes->post('attendance_save', 'AdminController::saveAttendance', ['as' => 'attendance_save']);
        $routes->post('attendance_signout', 'AdminController::signOut', ['as' => 'attendance_signout']);
        $routes->get('attendance_report', 'AdminController::report', ['as' => 'admin.Report']);
        $routes->get('fetch_attendance_data', 'AdminController::fetchAttendanceData', ['as' => 'fetch_attendance_data']);
        $routes->post('attendance/delete', 'AdminController::deleteAttendance', ['as' => 'attendance.delete']);
        


         
        ///////leave
        $routes->get('holidays','AdminController::holidays',['as'=>'admin.holidays']);
        $routes->get('leave_type','AdminController::leave_type',['as'=>'admin.leave_type']);
        $routes->post('save_leave', 'AdminController::save',['as' => 'save_leave']);
        $routes->post('delete_leave', 'AdminController::deleteLeave', ['as' => 'delete_leave']);
        $routes->post('update_leave', 'AdminController::updateLeave', ['as' => 'update_leave']);
        $routes->get('leave_application','AdminController::leave_application',['as'=>'admin.leave_application']);
        $routes->post('admin/leave_application', 'AdminController::submitLeaveApplication', ['as' => 'admin.submit_leave']);
        $routes->get('setting','AdminController::setting',['as'=>'setting']);
        //////
        $routes->get('employee/print/(:num)', 'AdminController::printEmployee/$1', ['as' => 'employee_print']);
        $routes->post('user/store', 'UserController::store',['as' => 'user.store']);
        $routes->get('user/add', 'UserController::add', ['as' => 'user.add']);
        $routes->get('userlist', 'UserController::userlist', ['as' => 'user.list']);
        $routes->post('update-user-picture', 'UserController::update_profile_picture');
        $routes->get('upload', 'UserController::upload', ['as' => 'user.upload']);
        $routes->post('upload-file', 'UserController::uploadFile', ['as' => 'uploadFile']);
        $routes->get('download-file/(:num)', 'UserController::downloadFile/$1', ['as' => 'downloadFile']);
        $routes->get('view-file/(:num)', 'UserController::viewFile/$1', ['as' => 'viewFile']);
        // Ensure that deleteFile route is also defined
        $routes->get('delete-file/(:num)', 'UserController::deleteFile/$1', ['as' => 'deleteFile']);
        

        $routes->get('admin/get_pending_notifications', 'AdminController::getPendingLeaveNotifications', ['as' => 'admin.pending']);
        $routes->post('mark-notifications-read', 'AdminController::markNotificationsRead');
        $routes->post('users/fetch', 'UserController::fetchUsers');

///////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////holidays
        $routes->post('holiday/create', 'AdminController::create', ['as' => 'admin.create_holidays']);
        $routes->post('admin/update-holidays', 'AdminController::updateHolidays', ['as' => 'admin.update_holidays']);
        $routes->post('admin/cancel-holidays', 'AdminController::cancelHolidays', ['as' => 'admin.cancel_holidays']);



    });

    $routes->group('', ['filter'=>'cifilter:guest'], static function($routes){
       // $routes->view('example-auth','example-auth');
       $routes->get('/', 'AuthController::loginForm', ['as' => 'admin.login.form']);
       $routes->post('login', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
       $routes->get('forget-password','AuthController::forgotForms',['as'=>'admin.forget.forms']);
       $routes->post('send_password-reset-link', 'AuthController::sendPasswordResetLink', ['as' =>
       'send_password_reset_link']);
        $routes->get('password/reset/(:any)', 'AuthController::resetPassword/$1', ['as' => 'admin.reset-password']);

    });
});