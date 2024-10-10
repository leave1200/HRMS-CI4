<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table = 'employee'; // Make sure this matches your table name
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'firstname', 'lastname', 'email', 'phone', 'dob','sex','address',
        'p_school', 's_school', 't_school', 'interview_for', 
        'interview_type', 'interview_date', 'interview_time', 
        'behaviour', 'result', 'comment','picture'
    ];
}
