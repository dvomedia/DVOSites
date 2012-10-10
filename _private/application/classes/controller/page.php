<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller {

    public function action_index()
	{
        $siteinfo   = parse_url(Url::base(true, true));
        $page       = new Model_Api('page');
        $page->site = $siteinfo['host'];
        $page->slug = $this->request->param('page_slug');

        $slug = $page->getSlug();
        if (false === isset($slug)) {
            $page->slug = 'home';
        }

        $page->load();

        $pageId = $page->getId(); 

        if (false === isset($pageId)) {
            // page not loaded
            $page->template = $page->site . '/default';
        } else {
            // page loaded fine.
            $page->template = $page->site . '/' . $page->template;    
        }
        
        // the master view
        $master = View::factory($page->site . '/master');
        $master->title  = $page->title;
        $master->active = $page->slug;

        // this controller's view
        $view = View::factory($page->template);
        $view->heading = $page->title;
        $view->content = $page->content;

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
	}
}