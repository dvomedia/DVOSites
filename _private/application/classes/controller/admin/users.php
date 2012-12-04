<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Users extends Controller_Admin {

    public function action_index()    
	{
        $users       = new Model_Api('user');
        $users->site = $this->siteinfo['host'];

        $users->load();

        $this->setTemplate('admin/users');
        // this controller's view
        $view             = View::factory($this->getTemplate());
        $view->return_url = $this->request->url(); 

        $view->users = $users->items;

        // the master view
        $master            = View::factory('admin/master');
        $master->title     = 'Admin';
        $master->sitetitle = 'Admin';
        $master->active    = '';
        $master->siteurl   = $this->siteinfo['host'];
        $master->user      = $this->user;
        $master->active    = 'users';

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
	}

    /**
     * edit page
     *
     * @return void
     * @author 
     **/
    public function action_edit()
    {
        $userId  = $this->request->param('id');
        $data    = $this->request->post('user');
        $success = null;
        $post    = null;
        
        $user       = new Model_Api('user', $userId);
        $user->site = $this->siteinfo['host'];
        $url = '/admin/users';
        
        $userAlbums = array();
        if (false === empty($userId)) {
            $user->load();
            $url .= '/edit/' . $userId;

            $albums       = new Model_Api('album');
            $albums->site = $this->siteinfo['host'];
            $albums->load();

            $user->getAlbum();
            if (true === isset($user->album['items'])) {
                foreach ($user->album['items']->getData() as $album) {
                    $userAlbums[] = $album['id'];
                }
            }
        }
            
        Kohana::$log->add(Kohana_Log::DEBUG, 'user data with: ' . print_r($user, true));

        if (false === empty($data)) {
            $str = '';
            if (true === empty($userId)) {
                // create password
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $str   = substr( str_shuffle( $chars ), 0, 8 );
                $user->password = md5($str);
            }

            $post         = true;
            $success      = false;

            if (true === isset($data['albums'])) {
                $user->albums = $data['albums'];
            }

            $user->username = $data['username'];

            if (true === $user->save() instanceof Model_Api) {
                $success = true;
                // $this->response->headers('Location', $url);
                // return;
                echo 'Password is: ' . $str;
            }
        }



        $this->setTemplate('admin/users/edit');

        $view             = View::factory($this->getTemplate());
        $view->return_url = $this->request->url(); 
        $view->user_id   = $userId;
        $view->username      = $user->username;
        $view->post       = $post;
        $view->success    = $success;
        $view->albums     = (true === isset($albums)) ? $albums->items : null;
        $view->user_albums = $userAlbums;

        // the master view
        $master            = View::factory('admin/master');
        $master->title     = 'Admin';
        $master->sitetitle = 'Admin';
        $master->active    = '';
        $master->siteurl   = $this->siteinfo['host'];
        $master->user      = $this->user;
        $master->active    = 'users';

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
    }
}