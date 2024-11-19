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
    
    public function getLeaveApplicationsWithDetails($leaveTypeModel, $userModel, $userId = null) {
        $builder = $this->db->table('leave_applications');
        
        // If the user is an employee, filter by userId
        if ($userId) {
            $builder->where('user_id', $userId); // Assuming `user_id` exists in leave_applications table
        }
        
        // Join leave types and user details
        $builder->join('leave_types', 'leave_types.id = leave_applications.leave_type_id', 'left');
        $builder->join('users', 'users.id = leave_applications.user_id', 'left');
        
        // Fetch leave applications with details
        return $builder->get()->getResultArray();
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
