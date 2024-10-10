<?php

namespace App\Models;

use CodeIgniter\Model;

class Designation extends Model
{
    protected $table = 'designations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
}
