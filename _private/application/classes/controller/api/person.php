<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Controller_Api_Person extends Controller_REST
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
			return new Model_Entity_Person_Gateway;
		};

		$this->_di['cache'] = function ($c) {
			return Cache::instance();
		};

		$this->_di['factory'] = function ($c) {
			return new Model_Entity_Person_Factory($c['gateway'], $c['cache']);
		};
	}

	/**
	 * the view method (GET)
	 *
	 * @return void
	 **/
	public function action_index()
	{
		$personId = $this->request->param('id');

		$persons = array_map(function($person) {
			$ps['id']   = $person->getId();
			$ps['name'] = $person->getName();
			return $ps;
		}, $this->_di['factory']->getPerson($personId));

		// return just the one, not as an array.
		if (false === empty($personId) && false !== Arr::get($persons, 0, false)) {
			$persons = $persons[0];
		}

		$this->response->headers('Cache-Control', 'max-age=' . DATE::HOUR . ', must-revalidate');
		$this->response->headers('Content-Type', 'application/x-javascript');
		$this->response->body(json_encode($persons));
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
		parse_str($this->request->body(), $params);

		// create JSON
		$json  = json_encode(sprintf('saved id (%d) whoop whoop', $params['id']));

		// convert to JSONP for cross domain / cross browser compatibilty
		$jsonp = $callback . '(' . $json .')';

		// fire out the response
		$this->response->body($jsonp);
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