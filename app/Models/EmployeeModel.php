<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employee'; // Make sure this matches your table name
    protected $primaryKey = 'id';
    protected $allowedFields = ['firstname', 'lastname', 'email', 'phone', 'dob','address','p_school', 's_school', 't_school', 'interview_for', 'interview_type', 'interview_date', 'interview_time', 'behaviour', 'result', 'comment','picture','sex', 'age'];
    public function getEmployeeNames()
    {
        return $this->select('id, firstname, lastname, email, picture')->findAll(); // Include email in the selection
    }
    public function getGenderCount()
    {
        $maleCount = $this->where('sex', 'Male')->where('result !=', 'Pending')->countAllResults();
        $femaleCount = $this->where('sex', 'Female')->where('result !=', 'Pending')->countAllResults();
        return ['Male' => $maleCount, 'Female' => $femaleCount];
    }
    
    public function hireEmployee($id)
    {
        return $this->update($id, ['result' => 'Hired']); // Ensure 'result' matches your database column
    }    
    public function getEmployeeNames()
{
    return $this->select('id, CONCAT(firstname, " ", lastname) AS name, email')->findAll();
}

}


