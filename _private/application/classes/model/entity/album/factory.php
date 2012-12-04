<?php
/**
 * Album Factory
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_Album_Factory
{
	protected $_gateway;
	protected $_cache;
	
	/**
	 * Mwl_Entity_Album_Factory constructor
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(Model_Entity_Album_Gateway $gateway, Cache $cache)
	{
		$this->_gateway = $gateway;
		$this->_cache   = $cache;
	}

	/**
	 * Create a Page object
	 *
	 * @return Model_Entity_Album
	 **/
	public static function create()
	{
		$page = new Model_Entity_Album();
		return $page;
	}

	/**
	 * Get all the albums
	 *
	 * @return array
	 **/
	public function getAlbums($id, $site, $userId)
	{
		//$album = $this->_cache->get('model.entity.page.factory.getpage.' . $id);

		if (true === empty($album)) {
			// get from db
			$album = array_map(function($p) {
				$rt = Model_Entity_Album_Factory::create();
				foreach ($p as $key => $value) {
					$rt->$key = $value;
				}
				
				return $rt;
			}, $this->_gateway->getAlbums($id, $site, $userId));

			$this->_cache->set('model.entity.album.factory.getalbums.' . $id, $album, DATE::WEEK);
		}
		return $album;
	}
}