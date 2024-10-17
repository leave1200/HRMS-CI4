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
    
        log_message('debug', 'Gender Counts: ' . json_encode($genderCounts));
    
        // Initialize counts
        $maleCount = 0;
        $femaleCount = 0;
    
        foreach ($genderCounts as $gender) {
            if (strcasecmp($gender['sex'], 'Male') === 0) {
                $maleCount = (int)$gender['count'];
            } elseif (strcasecmp($gender['sex'], 'Female') === 0) {
                $femaleCount = (int)$gender['count'];
            }
        }
    
        return [
            'Male' => $maleCount,
            'Female' => $femaleCount
        ];
    }
    
    
}


