<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Controller_Api_Page extends Controller_REST
{
	protected $_di;

	/**
	 * constructor
	 *
	 * @return void
	 **/
	public function __construct(Request $request, Response $response) {
		
		parent::__construct($request, $response);
		
		$this->_di = new Pimple;
		$c         = $this->_di;
		
		$this->_di['gateway'] = function ($c) {
			return new Model_Entity_Page_Gateway;
		};

		$this->_di['cache'] = function ($c) {
			return Cache::instance();
		};

		$this->_di['factory'] = function ($c) {
			return new Model_Entity_Page_Factory($c['gateway'], $c['cache']);
		};
	}

	/**
	 * the view method (GET)
	 *
	 * @return void
	 **/
	public function action_index()
	{
		$pageId   = $this->request->param('id');
		$site     = $this->request->query('site');
		$slug     = $this->request->query('slug');
		$category = $this->request->query('category');

		$pages = array_map(function($page) {
			$ps['id']             = $page->getId();
			$ps['title']          = $page->getTitle();
			$ps['template']       = $page->getTemplate() ? $page->getTemplate() : 'default';
			$ps['content']        = $page->getContent() ? $page->getContent() : '';
			$ps['slug']           = $page->getSlug() ? $page->getSlug() : '';
			$ps['protected']      = $page->getProtected() ? $page->getProtected() : 'N';
			$ps['category_title'] = ($page->getCategory_Title() == '') ? null : $page->getCategory_Title();
			$ps['special']        = ($page->getSpecial() == '') ? null : $page->getSpecial();
			$ps['site_title']     = $page->getSite_Title() ? $page->getSite_Title() : 'Project Name';
			$ps['site_url']       = $page->getSite_Url() ? $page->getSite_Url() : '/';
			$ps['skin']           = $page->getSkin() ? $page->getSkin() : 'default';
			return $ps;
		}, $this->_di['factory']->getPages($pageId, $site, $slug, $category));

		// return just the one, not as an array.
		if (false !== Arr::get($pages, 0, false) && count($pages) == 1) {
			$pages = $pages[0];
		} else {
			$items = $pages;
			unset($pages);
			$pages['items'] = $items;
		}

		$this->response->headers('Cache-Control', 'max-age=' . DATE::HOUR . ', must-revalidate');
		$this->response->headers('Content-Type', 'application/x-javascript');
		$this->response->body(json_encode($pages));
	}

	/**
	 * the save method (PUT)
	 *
	 * @return void
	 * @author 
	 **/
	public function action_update()
	{
		$callback = $this->request->query('callback');
		
		// parse the PUT values from the request body (none of this nasty php://input business)
		$params = $this->request->post();

		Kohana::$log->add(Kohana_Log::DEBUG, print_r($params, true));

		$array = array();
		if(false === $this->_di['gateway']->updatePage($params['id'], $params['site'], $params['content'])) {
			$array = array('error' => 'NOt happening pal');
		}
		// create JSON
		
		$json  = json_encode($array);

		// convert to JSONP for cross domain / cross browser compatibilty
		//$jsonp = $callback . '(' . $json .')';

		// fire out the response
		$this->response->body($json);
	}

	/**
	 * the create method (POST)
	 *
	 * @return void
	 * @author 
	 **/
	public function action_create()
	{
		echo "saved<br/>";
		//print_r($this->request->post());
		$this->response->body(json_encode(sprintf('created id (%d) whoop whoop', uniqid())));	
	}

	/**
	 * remove the entity
	 *
	 * @return void
	 * @author 
	 **/
	public function action_delete()
	{
		echo "deleted<br />";
		$id = $this->request->param('id');
		$this->response->body(json_encode(sprintf('deleted id (%d) whoop whoop', $id)));	
	}
}