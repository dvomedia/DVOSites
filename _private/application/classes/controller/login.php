<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Login extends Controller {

    public function action_index()
	{
        // login here
        $username  = $this->request->post('username') ? $this->request->post('username') : $this->request->query('username');
        $password  = $this->request->post('password') ? $this->request->post('password') : $this->request->query('password');
        $returnUrl = $this->request->post('return_url') ? $this->request->post('return_url') : '/';

        $user           = new Model_Api('user');
        $user->username = $username;
        $user->password = md5($password);

        $user->load();

        $userId = $user->getId();
        if (true === isset($userId)) {
            $session = Session::instance();
            $session->set('user', $user);
        }

        $this->response->headers('location', $returnUrl);
	}

    public function action_logout()
    {
        $session = Session::instance();
        $session->destroy();
        $this->response->headers('location', '/');   
    }
}