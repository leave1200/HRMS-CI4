<?php

namespace App\Models;

use CodeIgniter\Model;

class leave_typeModel extends Model
{
    protected $table = 'leave_type';  // Replace with your actual table name
    protected $primaryKey = 'l_id';  // Your primary key

    // Fields to insert/update
    protected $allowedFields = ['l_name', 'l_description', 'l_days',];

    // Set true to return insert ID
    protected $useAutoIncrement = true;
}
