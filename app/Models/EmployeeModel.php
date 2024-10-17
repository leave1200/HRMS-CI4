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
    public function getEmployeeGenderData()
    {
        try {
            // Load the EmployeeModel
            $this->load->model('EmployeeModel');
            
            // Fetch the gender counts
            $genderData = $this->EmployeeModel->getGenderCounts();
            
            // Log the fetched data for debugging
            log_message('debug', 'Fetched Gender Data: ' . json_encode($genderData));
    
            // Return the data as JSON response
            return $this->response->setJSON($genderData);
        } catch (\Exception $e) {
            // Log the exception message
            log_message('error', 'Error fetching gender data: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'An error occurred while fetching data.']);
        }
    }
    
}


