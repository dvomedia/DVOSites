<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Controller_Api_Photo extends Controller_REST
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
			return new Model_Entity_Photo_Gateway;
		};

		$this->_di['cache'] = function ($c) {
			return Cache::instance();
		};

		$this->_di['factory'] = function ($c) {
			return new Model_Entity_Photo_Factory($c['gateway'], $c['cache']);
		};
	}

	/**
	 * the view method (GET)
	 *
	 * @return void
	 **/
	public function action_index()
	{
		$photoId = $this->request->param('id');
		$albumId = $this->request->query('album_id');
		$userId  = $this->request->query('user_id');

		$photos = array_map(function($user) {
			$u['id']      = $user->getId();
			$u['caption'] = $user->getCaption();
			$u['url']     = $user->getUrl();
			
			return $u;
		}, $this->_di['factory']->getPhotos($photoId, $userId, $albumId));

		// return just the one, not as an array.
		if (false !== Arr::get($photos, 0, false)) {
			if (count($photos) == 1 && true === isset($photoId)) {
				$photos = $photos[0];
			} else {
				$items = $photos;
				unset($photos);
				$photos['items'] = $items;	
			}
		} else  {
			$photos = array();
		}

		$this->response->headers('Cache-Control', 'max-age=' . DATE::HOUR . ', must-revalidate');
		$this->response->headers('Content-Type', 'application/x-javascript');
		$this->response->body(json_encode($photos));
	}
}