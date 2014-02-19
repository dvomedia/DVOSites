<?php defined('SYSPATH') or die('No direct script access.');

class Controller extends Kohana_Controller
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	public $siteinfo = '';

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

		$siteinfo  = parse_url(Url::base(true, true));
		// horrible hacky thing - there's a much better way but cba
        if (strpos($siteinfo['host'], 'dev.') !== false) {
            $siteinfo['host'] = str_replace('dev.', '', $siteinfo['host']);
        }

        if (true === defined('SITE')) {
			$siteinfo['host'] = SITE;
		}

        $this->siteinfo = $siteinfo;
	}
}