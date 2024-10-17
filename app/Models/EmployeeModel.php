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
        // Log the number of rows where 'sex' is 'Male'
        $maleCount = $this->where('sex', 'Male')->countAllResults();
        
        // Log the number of rows where 'sex' is 'Female'
        $femaleCount = $this->where('sex', 'Female')->countAllResults();
        
        // Check if the counts are returning as expected
        return [
            'Male' => $maleCount,
            'Female' => $femaleCount
        ];
    }
    
    

    
}


