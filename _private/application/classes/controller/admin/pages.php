<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Pages extends Controller_Admin {

    public function action_index()    
	{
        $pages       = new Model_Api('page');
        $pages->site = $this->siteinfo['host'];

        $pages->load();

        $this->setTemplate('admin/pages');
        // this controller's view
        $view             = View::factory($this->getTemplate());
        $view->return_url = $this->request->url(); 

        $view->pages = $pages->items;

        // the master view
        $master            = View::factory('admin/master');
        $master->title     = 'Admin';
        $master->sitetitle = 'Admin';
        $master->active    = '';
        $master->siteurl   = $this->siteinfo['host'];
        $master->user      = $this->user;
        $master->active    = 'pages';

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
        $pageId  = $this->request->param('id');
        $data    = $this->request->post('page');
        $success = null;
        $post    = null;
        
        
        $page       = new Model_Api('page', $pageId);
        $page->site = $this->siteinfo['host'];

        $page->load();

        if (false === empty($data)) {
            $post = true;
            $success = false;
            $page->content = $data['content'];
            if (true === $page->save() instanceof Model_Api) {
                $success = true;
            }
        }

        $this->setTemplate('admin/pages/edit');

        $view             = View::factory($this->getTemplate());
        $view->return_url = $this->request->url(); 
        $view->title      = $page->title;
        $view->content    = $page->content;
        $view->page_id    = $pageId;
        $view->slug       = $page->slug;
        $view->post       = $post;
        $view->success    = $success;

        // the master view
        $master            = View::factory('admin/master');
        $master->title     = 'Admin';
        $master->sitetitle = 'Admin';
        $master->active    = '';
        $master->siteurl   = $this->siteinfo['host'];
        $master->user      = $this->user;

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
    }
}