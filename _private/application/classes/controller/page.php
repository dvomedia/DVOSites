<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller {

    public function __construct(Request $request, Response $response) {
        parent::__construct($request, $response);
    }

    public function action_index()
	{
        $siteinfo   = parse_url(Url::base(true, true));
        $page       = new Model_Api('page');
        $page->site = $siteinfo['host'];

        // horrible hacky thing - there's a much better way but cba
        if (strpos($page->site, 'dev.') !== false) {
            $page->site = str_replace('dev.', '', $page->site);
        }

        $page->slug = $this->request->param('page_slug');

        $slug = $page->getSlug();
        if (true === empty($slug)) {
            $page->slug = 'home';
        }

        $page->load();

        $pageId    = $page->getId();
        $category  = $page->getCategory_Title();
        $protected = $page->getProtected();

        $page->template = $page->skin . '/' . $page->template;    
        if ("Y" === $protected) {
            // okay, protected page, just check we're auth'd
            $userId = $this->user->getId();
            if (true === empty($userId)) {
                $page->template = 'login';
            }
        }

        if (false === isset($pageId)) {
            // page not loaded
            $page->template = $page->skin . '/default';
        } else {
            // page loaded fine.
            

            // any special functions?
            $params = $page->getSpecial();

            if (true === isset($params)) {
                $params = unserialize($params);
                if (true === is_array($params)) {
                    foreach ($params as $param => $value) {
                        switch ($param . ':' . $value) {
                            case 'category:news':
                                $special           = new Model_Api('page');
                                $special->site     = $page->site;
                                $special->category = $value;    
                                break;
                            case 'category:photos':
                                $special         = new Model_Api('photo');
                                $special->site   = $page->site;
                                $special->user_id = $this->user->getId();
                                break;
                        }
                    }
                }
                
                $special->load();

                $page->items = $special->getItems();
            }
        }
        
        // the master view
        $master = View::factory($page->skin . '/master');
        $master->title  = $page->title;
        $master->user   = $this->user;
        $master->sitetitle = $page->getSite_Title();
        $master->siteurl = '//' . $page->getSite_Url();

        if (true === isset($category)) {
            $master->active = strtolower($category);
        } else {
            $master->active = $page->slug;    
        }

        // this controller's view
        $view             = View::factory($page->template);
        $view->heading    = $page->title;
        $view->content    = $page->content;
        $view->items      = $page->items;
        $view->return_url = $this->request->url(); 

        // assign controller 
        $master->body = $view;

        $this->response->body($master);
	}
}