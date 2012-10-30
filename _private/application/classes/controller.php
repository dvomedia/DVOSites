<?php defined('SYSPATH') or die('No direct script access.');

class Controller extends Kohana_Controller {

	/**
	 * Creates a new controller instance. Each controller must be constructed
	 * with the request object that created it.
	 *
	 * @param   Request   $request  Request that created the controller
	 * @param   Response  $response The request's response
	 * @return  void
	 */
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);

		$user = Session::instance()->get('user');
		if (true === isset($user)) {
			$this->user = $user;
		} else {
			$this->user = Model_Entity_User_Factory::create();	
		}
	}
}