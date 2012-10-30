<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Pages extends Controller_Admin {

    public function action_index()    
	{
        $siteinfo   = parse_url(Url::base(true, true));

        $pages       = new Model_Api('page');
        $pages->site = $siteinfo['host'];

        $pages->load();

        $this->setTemplate('admin/pages');
        // this controller's view
        $view = View::factory($this->getTemplate());
        $view->return_url = $this->request->url(); 

        $view->pages = $pages->items;

        // the master view
        $master = View::factory('admin/master');
        $master->title  = 'Admin';
        $master->sitetitle = 'Admin';
        $master->active = '';
        $master->siteurl = $siteinfo['host'];
        $master->user   = $this->user;

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
	}
}