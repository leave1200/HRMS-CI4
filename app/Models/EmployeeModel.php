<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employee'; // Make sure this matches your table name
    protected $primaryKey = 'id';
    protected $allowedFields = ['firstname', 'lastname', 'email', 'phone', 'dob','address','p_school', 's_school', 't_school', 'interview_for', 'interview_type', 'interview_date', 'interview_time', 'behaviour', 'result', 'comment','picture','sex'];
    public function getEmployeeNames()
    {
        return $this->select('id, firstname, lastname, email, picture')->findAll(); // Include email in the selection
    }
    public function getGenderCounts()
    {
        // Ensure 'sex' values in the database are properly set as 'Male' and 'Female'
        $maleCount = $this->where('sex', 'Male')->countAllResults();
        $femaleCount = $this->where('sex', 'Female')->countAllResults();
        
        return [
            'male' => $maleCount,
            'female' => $femaleCount
        ];
    }
    

    
}


