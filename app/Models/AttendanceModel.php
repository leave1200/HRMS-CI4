<?php

namespace App\Models;
use App\Models\User;
use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'office', 'position', 'attendance','sign_in','pm_sign_in', 'sign_out','pm_sign_out','att'];

    // public function getAttendanceRecords($userName = null) {
    //     $builder = $this->db->table('attendance')
    //         ->select('attendance.id, employees.firstname, employees.lastname, offices.name AS office, positions.position_name AS position, sign_in, sign_out, pm_sign_in, pm_sign_out')
    //         ->join('employees', 'employees.id = attendance.employee_id')
    //         ->join('offices', 'offices.id = attendance.office_id')
    //         ->join('positions', 'positions.id = attendance.position_id')
    //         ->where('sign_out IS NULL OR pm_sign_out IS NULL'); // Fetch records where either sign-out is missing
    
    //     // If a user name is provided, filter by it
    //     if ($userName) {
    //         $builder->where('employees.firstname', $userName); // Adjust as necessary
    //     }
    
    //     return $builder->findAll();
    // }
    public function getAttendanceRecords($userName = null)
    {
        $builder = $this->db->table('attendance')
            ->select('attendance.id, users.name AS user_name, offices.name AS office, positions.position_name AS position, sign_in, sign_out, pm_sign_in, pm_sign_out')
            ->join('users', 'users.id = attendance.user_id') // Join with users table
            ->join('offices', 'offices.id = attendance.office_id')
            ->join('positions', 'positions.id = attendance.position_id')
            ->where('sign_out IS NULL OR pm_sign_out IS NULL'); // Fetch records where either sign-out is missing

        // If a user name is provided, filter by it
        if ($userName) {
            $builder->where('users.name', $userName); // Filter by the user's name
        }

        return $builder->findAll();
    }
    
}
