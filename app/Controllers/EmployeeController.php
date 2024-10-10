<?php

namespace App\Controllers;

use App\Models\EmployeeModel;
use CodeIgniter\Controller;

class EmployeeController extends Controller
{
    public function saveEmployee()
    {
        helper(['form', 'url']);

        $rules = [
            'firstname' => 'required|min_length[3]|max_length[250]',
            'lastname' => 'required|min_length[3]|max_length[250]',
            'email' => 'required|valid_email|max_length[250]',
            'phone' => 'required|numeric|max_length[11]',
            'address' => 'required|max_length[250]',
            'dob' => 'required|valid_date',
            'p_school' => 'required|max_length[255]',
            's_school' => 'required|max_length[255]',
            't_school' => 'required|max_length[255]',
            'behaviour' => 'required|max_length[255]',
            'result' => 'required|max_length[255]',
            'comment' => 'max_length[65535]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $employeeModel = new EmployeeModel();

        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'dob' => $this->request->getPost('dob'),
            'p_school' => $this->request->getPost('p_school'),
            's_school' => $this->request->getPost('s_school'),
            't_school' => $this->request->getPost('t_school'),
            'behaviour' => $this->request->getPost('behaviour'),
            'result' => $this->request->getPost('result'),
            'comment' => $this->request->getPost('comment'),
        ];

        if ($employeeModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Employee added successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to add employee.'
            ]);
        }
    }
}
