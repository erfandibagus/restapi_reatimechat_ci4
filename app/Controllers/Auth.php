<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Auth extends ResourceController
{

    public function index()
    {
        if ($this->request->getMethod() == 'post') {
            $userModel = new \App\Models\UserModel();
            $logModel = new \App\Models\LogModel();

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            if ($email && $password) {
                $user = $userModel->where(['email' => $email])->first();
                if ($user) {
                    $is_valid = password_verify($password, $user['password']);
                    if ($is_valid) {
                        $data = [
                            'id'    => $user['id'],
                            'name'  => $user['name'],
                            'email' => $user['email'],
                            'token' => $user['token']
                        ];
                        $log = [
                            'user_id'       => $user['id'],
                            'ip_address'    => $this->request->getIPAddress(),
                            'login_at'      => date('Y-m-d H:i:s'),
                            'leave_at'      => null
                        ];
                        $logModel->save($log);
                        return $this->respond($data);
                    } else {
                        return $this->failForbidden('Wrong email or password');
                    }
                } else {
                    return $this->failNotFound('User not found');
                }
            } else {
                return $this->failValidationError('Missing parameter email or password');
            }
        } else {
            return $this->fail('Only post method is allowed');
        }
    }

    public function register()
    {
        if ($this->request->getMethod() == 'post') {
            helper(['form', 'text']);
            $userModel = new \App\Models\UserModel();

            $validate = $this->validate([
                'name'          => [
                    'rules'     => 'required',
                ],
                'email'         => [
                    'rules'     => 'required|valid_email|is_unique[users.email]',
                ],
                'password'      => [
                    'rules'     => 'required'
                ]
            ]);
            if (!$validate) {
                return $this->fail(implode('<br />', $this->validator->getErrors()));
                die;
            }

            $data = [
                'name'      => $this->request->getVar('name'),
                'email'     => $this->request->getVar('email'),
                'password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'token'     => random_string('alnum', 40)
            ];

            $query = $userModel->save($data);
            if ($query) {
                return $this->respondCreated(['status' => true, 'message' => 'Data has been created']);
            } else {
                return $this->failUnauthorized();
            }
        } else {
            return $this->fail('Only post method is allowed');
        }
    }

    public function logout($id = false)
    {
        if ($id) {
            helper('text');
            $userModel = new \App\Models\UserModel();
            $logModel = new \App\Models\LogModel();

            $newToken = [
                'id'    => $id,
                'token' => random_string('alnum', 40)
            ];
            $userModel->save($newToken);

            $log = $logModel->where(['user_id' => $id, 'leave_at' => null])->orderBy('login_at', 'DESC')->first();
            if ($log) {
                $updateLog = [
                    'id'        => $log['id'],
                    'leave_at'  => date('Y-m-d H:i:s')
                ];
                $logModel->save($updateLog);
            }
            return $this->respond(['status' => true]);
        } else {
            return $this->fail('Missing parameter id');
        }
    }
}
