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
        // Get the gender counts using a single query with group by
        $genderCounts = $this->select('sex, COUNT(*) as count')
            ->groupBy('sex')
            ->findAll();

        // Initialize counts
        $maleCount = 0;
        $femaleCount = 0;

        // Populate counts based on the results
        foreach ($genderCounts as $gender) {
            if ($gender['sex'] === 'Male') {
                $maleCount = (int)$gender['count'];
            } elseif ($gender['sex'] === 'Female') {
                $femaleCount = (int)$gender['count'];
            }
        }

        return [
            'Male' => $maleCount,
            'Female' => $femaleCount
        ];
    }
    
    
    

    
}


