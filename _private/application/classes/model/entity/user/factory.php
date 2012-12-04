<?php
/**
 * User Factory
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_User_Factory
{
	protected $_gateway;
	protected $_cache;
	
	/**
	 * Mwl_Entity_User_Factory constructor
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(Model_Entity_User_Gateway $gateway, Cache $cache)
	{
		$this->_gateway = $gateway;
		$this->_cache   = $cache;
	}

	/**
	 * Create a Page object
	 *
	 * @return Model_Entity_User
	 **/
	public static function create()
	{
		$page = new Model_Entity_User();
		return $page;
	}

	/**
	 * Get all the pages
	 *
	 * @return array
	 **/
	public function getUsers($id, $username, $password, $site)
	{
		//$page = $this->_cache->get('model.entity.page.factory.getpage.' . $id);

		if (true === empty($page)) {
			// get from db
			$page = array_map(function($p) {
				$rt = Model_Entity_User_Factory::create();
				foreach ($p as $key => $value) {
					$rt->$key = $value;
				}
				
				return $rt;
			}, $this->_gateway->getUsers($id, $username, $password, $site));

			$this->_cache->set('model.entity.page.factory.getpage.' . $id, $page, DATE::WEEK);
		}
		return $page;
	}
}