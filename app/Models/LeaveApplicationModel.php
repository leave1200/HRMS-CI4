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
    
    public function getLeaveApplications($leaveTypeModel, $userModel)
    {
        // Fetch all pending leave applications
        return array_map(function ($application) use ($leaveTypeModel, $userModel) {
            // Fetch leave type and user details
            $application['leave_type_name'] = $leaveTypeModel->find($application['la_type'])['l_name'] ?? 'Unknown Leave Type';
            $application['user_name'] = $userModel->find($application['la_name'])['name'] ?? 'Unknown User';
            return $application;
        }, $this->where('status', 'Pending')->findAll());
    }
    
    public function getLeaveApplicationsWithDetails($leaveTypeModel, $userModel, $loggedInUserId)
    {
        // Fetch all pending leave applications for the logged-in user only
        return array_map(function ($application) use ($leaveTypeModel, $userModel) {
            // Fetch leave type and user details
            $application['leave_type_name'] = $leaveTypeModel->find($application['la_type'])['l_name'] ?? 'Unknown Leave Type';
            $application['user_name'] = $userModel->find($application['la_name'])['name'] ?? 'Unknown User';
            return $application;
        }, $this->where('status', 'Pending')->where('la_name', $loggedInUserId)->findAll());
    }
    
    



    public function countApprovedLeaves()
    {
        return $this->where('status', 'Approved')->countAllResults();
    }

    public function countPendingLeaves()
    {
        return $this->where('status', 'Pending')->countAllResults();
    }
    
}
