<?php

use Phalcon\Mvc\Controller;
use Phalcon\Escaper;
use Firebase\JWT\JWT;

class SignupController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }

    public function registerAction()
    {
        $escaper = new Escaper();
        $user = new Users();
        $array = [
            'name' => $escaper->escapeHtml($this->request->getPost('name')),
            'email' => $escaper->escapeHtml($this->request->getPost('email')),
            'pswd' => $escaper->escapeHtml($this->request->getPost('pswd')),
            'role' => $this->request->getPost('role'),
        ];

        $user->assign(
            $array,
            ['name', 'email', 'pswd', 'role']
        );
        $success = $user->save();
        if ($success) {
            $key = 'example_key';
            $payload = [
                'iss' => 'http://example.org',
                'aud' => 'http://example.com',
                'iat' => 1356999524,
                'nbf' => 1357000000,
                'role'=>$array['role'],
                'name'=>$array['name']
            ];
            $jwt = JWT::encode($payload, $key, 'HS256');
            print_r($jwt);die;
            $this->session->set('token', $jwt);
            
        } else {
            print_r($user->getMessages());
        }
    }
}
