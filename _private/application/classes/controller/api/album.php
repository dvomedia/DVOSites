<?php

/**
 * Album Controller
 *
 * @package default
 * @author 
 **/
class Controller_Api_Album extends Controller_REST
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
			return new Model_Entity_Album_Gateway;
		};

		$this->_di['cache'] = function ($c) {
			return Cache::instance();
		};

		$this->_di['factory'] = function ($c) {
			return new Model_Entity_Album_Factory($c['gateway'], $c['cache']);
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
		$userId   = $this->request->query('user_id');

		$pages = array_map(function($page) {
			$ps['id']             = $page->getId();
			$ps['title']          = $page->getTitle();
			return $ps;
		}, $this->_di['factory']->getAlbums($pageId, $site, $userId));

		// return just the one, not as an array.
		if (false !== Arr::get($pages, 0, false) && count($pages) == 1 && (false === isset($userId))) {
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
		Kohana::$log->add(Kohana_Log::DEBUG, '[:class]' . print_r($params, true), array(':class' => __CLASS__));
		
		if(false === $this->_di['gateway']->updateAlbum($params['site'], $params['id'], $params['title'], $params['photos'])) {
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
		//print_r($this->request->post());			
		Kohana::$log->add(Kohana_Log::DEBUG, 'need to create innit');
		// parse the PUT values from the request body (none of this nasty php://input business)
		$params = $this->request->post();

		Kohana::$log->add(Kohana_Log::DEBUG, print_r($params, true));

		$array = array();
		if(false === $this->_di['gateway']->createAlbum($params['site'], $params['title'])) {
			$array = array('error' => 'Not happening pal');
		}
		// create JSON
		
		$json  = json_encode($array);

		// convert to JSONP for cross domain / cross browser compatibilty
		//$jsonp = $callback . '(' . $json .')';

		// fire out the response
		$this->response->body($json);
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