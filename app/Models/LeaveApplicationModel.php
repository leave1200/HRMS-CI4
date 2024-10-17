<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\leave_typeModel;
use App\Models\EmployeeModel;


class LeaveApplicationModel extends Model
{
    protected $table = 'leave'; // Your table name
    protected $primaryKey = 'la_id'; // Primary key of the table

    protected $allowedFields = [
        'la_name',
        'la_type', 
        'la_start', 
        'la_end',
        'status', // Add status if needed
    ];
    

    // Set true to return insert ID
    protected $useAutoIncrement = true;

    // Add validation rules if needed
    protected $validationRules = [
        'la_name' => 'required|string',
        'la_type' => 'required|string',
        'la_start' => 'required|valid_date',
        'la_end' => 'required|valid_date',
    ];
    
    public function getLeaveApplicationsWithDetails(leave_typeModel $leaveTypeModel, EmployeeModel $employeeModel)
    {
        $leaveApplications = $this->findAll(); // Fetch all leave applications
    
        // Prepare an array to hold the applications with names
        $applicationsWithDetails = [];
    
        foreach ($leaveApplications as $application) {
            // Fetch leave type name
            $leaveType = $leaveTypeModel->find($application['la_type']);
            $application['leave_type_name'] = $leaveType ? $leaveType['l_name'] : 'Unknown Leave Type';
    
            // Fetch employee name
            $employee = $employeeModel->find($application['la_name']);
            $application['employee_name'] = $employee ? $employee['firstname'] . ' ' . $employee['lastname'] : 'Unknown Employee';
    
            // Add the application details to the array
            $applicationsWithDetails[] = $application;
        }
    
        return $applicationsWithDetails;
    }
    
    
}
