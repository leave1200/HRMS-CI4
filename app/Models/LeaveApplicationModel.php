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
    
// In your LeaveApplicationModel
public function getLeaveApplicationsWithDetailsByUser($leaveTypeModel, $userModel, $userId)
{
    // Assuming 'leave_applications' table has 'user_id' column to associate applications with users
    return $this->db->table('leave_applications')
                    ->join('leave_types', 'leave_applications.leave_type_id = leave_types.id')
                    ->where('leave_applications.user_id', $userId) // Filter by logged-in user
                    ->get()
                    ->getResultArray();
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
