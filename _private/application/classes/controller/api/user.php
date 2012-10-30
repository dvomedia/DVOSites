<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Controller_Api_User extends Controller_REST
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
			return new Model_Entity_User_Gateway;
		};

		$this->_di['cache'] = function ($c) {
			return Cache::instance();
		};

		$this->_di['factory'] = function ($c) {
			return new Model_Entity_User_Factory($c['gateway'], $c['cache']);
		};
	}

	/**
	 * the view method (GET)
	 *
	 * @return void
	 **/
	public function action_index()
	{
		$userId   = $this->request->param('id');
		$username = $this->request->query('username');
		$password = $this->request->query('password');

		$users = array_map(function($user) {
			$u['id']       = $user->getId();
			$u['username'] = $user->getUsername();
			$u['password'] = $user->getPassword();
			
			return $u;
		}, $this->_di['factory']->getUsers($userId, $username, $password));

		// return just the one, not as an array.
		if (false !== Arr::get($users, 0, false)) {
			if (count($users) == 1) {
				$users = $users[0];
			} else {
				$items = $users;
				unset($users);
				$users['items'] = $items;	
			}
		} else  {
			$users = array();
		}

		$this->response->headers('Cache-Control', 'max-age=' . DATE::HOUR . ', must-revalidate');
		$this->response->headers('Content-Type', 'application/x-javascript');
		$this->response->body(json_encode($users));
	}
}