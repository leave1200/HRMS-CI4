<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = 'array'; // Return results as an array
    protected $useSoftDeletes = false; // Enable if using soft deletes

    protected $allowedFields = [
        'name', 
        'username', 
        'email', 
        'password', 
        'picture', 
        'bio', 
        'status'
    ];

    // Automatically handle created_at and updated_at fields
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation rules for creating/updating users
    protected $validationRules = [
        'name'              => 'required|string|max_length[255]',
        'username'          => 'required|string|max_length[255]',
        'email'             => 'required|valid_email',
        'password'          => 'required|min_length[8]',
        'confirm_password'  => 'matches[password]',
        'picture'           => 'mime_in[picture,image/jpg,image/jpeg,image/png]|max_size[picture,2048]',
        'bio'               => 'permit_empty|string',
        'status'            => 'required|string|max_length[255]'
    ];

    protected $validationMessages = [
        'password' => [
            'min_length' => 'Password must be at least 8 characters long.'
        ],
        'confirm_password' => [
            'matches' => 'Password confirmation does not match.'
        ],
        'picture' => [
            'mime_in' => 'Only JPG, JPEG, and PNG files are allowed.',
            'max_size' => 'The file size should not exceed 2MB.'
        ]
    ];

    // Methods for hashing passwords
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // Method to verify password
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
}
