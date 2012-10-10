<?php
/**
 * Person Factory
 *
 * @package Skeleton
 * @author  Mindwork Labs
 */
class Model_Entity_Person_Factory
{
	protected $_gateway;
	protected $_cache;
	
	/**
	 * Mwl_Entity_Retailer_Factory constructor
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct(Model_Entity_Person_Gateway $gateway, Cache $cache)
	{
		$this->_gateway = $gateway;
		$this->_cache   = $cache;
	}

	/**
	 * Create a retailer object
	 *
	 * @return Mwl_Entity_Retailer
	 **/
	public static function create()
	{
		$retailer = new Model_Entity_Person();
		return $retailer;
	}

	/**
	 * Get all the retailers
	 *
	 * @return array
	 **/
	public function getPerson($id)
	{
		//$person = $this->_cache->get('model.entity.person.factory.getperson.' . $id);

		if (true === empty($person)) {
			// get from db
			$person = array_map(function($p) {
				$rt = Model_Entity_Person_Factory::create();
				foreach ($p as $key => $value) {
					$rt->$key = $value;
				}
				
				return $rt;
			}, $this->_gateway->getPerson($id));

			$this->_cache->set('model.entity.person.factory.getperson.' . $id, $person, DATE::WEEK);
		}
		return $person;
	}
}