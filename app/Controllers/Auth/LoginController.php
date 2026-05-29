<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function loginAuth()
    {
        $session = session();
        $model = new UserModel();
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $model->where('email', $email)->first();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $ses_data = [
                    'id'         => $user['id'],
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'role_id'    => $user['role_id'],
                    'isLoggedIn' => true
                ];
                $session->set($ses_data);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Invalid password.');
                return redirect()->to('/auth/login');
            }
        } else {
            $session->setFlashdata('error', 'Email target not found.');
            return redirect()->to('/auth/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}