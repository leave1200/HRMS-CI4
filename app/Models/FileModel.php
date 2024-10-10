<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table         = 'files';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name', 'original_name', 'uploaded_at', 'user_id'];
    protected $returnType    = 'array';

    // Optional: Enable timestamps if desired
    // protected $useTimestamps = true;
    // protected $createdField  = 'uploaded_at';
    // protected $updatedField  = 'updated_at';
    
}
