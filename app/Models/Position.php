<?php

namespace App\Models;

use CodeIgniter\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $primaryKey = 'position_id';
    protected $allowedFields = ['position_name'];
}
