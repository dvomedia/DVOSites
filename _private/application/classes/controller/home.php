<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller {

    public function action_index()
	{
        // this controller's view
        $view = View::factory('home');

        // the master view
        $master = View::factory('master');

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
	}
}