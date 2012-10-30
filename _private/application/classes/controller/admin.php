<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $template = '';

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

		
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function setTemplate($value)
	{
		$userId = $this->user->getId();
        if (true === empty($userId)) {
            $this->template = 'login';
        } else {
        	$this->template = $value;
        }

        return $this;
    }
}