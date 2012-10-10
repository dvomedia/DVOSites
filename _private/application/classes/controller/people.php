<?php defined('SYSPATH') or die('No direct script access.');

class Controller_People extends Controller {

	public function action_index()
	{
        //echo 'um';
        $person = new Model_Api('person', 1);

        
        //$person->save()->delete();
        $person->load();
        //print_r($person);
        $person->name = 'bobbwh';
        //$person->save();

        $person->getGroups();
        

        // this controller's view
        $view = View::factory('demo');
        $view->firstname = $person->name;

        // the master view
        $master = View::factory('master');

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
	}
}
