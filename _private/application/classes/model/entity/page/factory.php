<?php
/**
 * Page Factory
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_Page_Factory
{
	protected $_gateway;
	protected $_cache;
	
	/**
	 * Mwl_Entity_Page_Factory constructor
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(Model_Entity_Page_Gateway $gateway, Cache $cache)
	{
		$this->_gateway = $gateway;
		$this->_cache   = $cache;
	}

	/**
	 * Create a Page object
	 *
	 * @return Model_Entity_Page
	 **/
	public static function create()
	{
		$page = new Model_Entity_Page();
		return $page;
	}

	/**
	 * Get all the pages
	 *
	 * @return array
	 **/
	public function getPage($id, $site, $slug)
	{
		//$page = $this->_cache->get('model.entity.page.factory.getpage.' . $id);

		if (true === empty($page)) {
			// get from db
			$page = array_map(function($p) {
				$rt = Model_Entity_Page_Factory::create();
				foreach ($p as $key => $value) {
					$rt->$key = $value;
				}
				
				return $rt;
			}, $this->_gateway->getPage($id, $site, $slug));

			$this->_cache->set('model.entity.page.factory.getpage.' . $id, $page, DATE::WEEK);
		}
		return $page;
	}
}