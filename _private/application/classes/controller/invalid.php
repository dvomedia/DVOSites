<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Invalid extends Controller_REST {

	public function action_index()
	{
		$this->response->body('Hello. We have not set a default controller yet - well, apart from this one :)');
	}

} // End Welcome
