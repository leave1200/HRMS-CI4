<?php

namespace App\Models;

use CodeIgniter\Model;

class Setting extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 's_id';
    protected $allowedFields    = [
        'title',
        'email',
        'phone',
        'meta_keywords',
        'description',
        'logo',
        'favicon'
    ];

}
