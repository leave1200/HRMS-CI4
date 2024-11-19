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
    
    public function getLeaveApplicationsWithDetails($leaveTypeModel, $userModel, $userId = null)
    {
        // Assuming you have a join with the leave_type and user tables
        $builder = $this->builder()
            ->select('leave_applications.la_id, leave_applications.la_start, leave_applications.la_end, leave_applications.status, leave_types.l_name as leave_type_name, users.name as user_name')
            ->join('leave_types', 'leave_types.l_id = leave_applications.la_type', 'left')
            ->join('users', 'users.id = leave_applications.la_name', 'left');
    
        // Filter by user ID if the user is an EMPLOYEE
        if ($userId !== null) {
            $builder->where('leave_applications.la_name', $userId);
        }
    
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
