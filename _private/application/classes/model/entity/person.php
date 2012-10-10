<?php
/**
 * Person Entity
 *
 * @package Mwl
 * @author  Mindwork Labs
 */
class Model_Entity_Person extends Model_Entity_Abstract
{
	protected $_data;

	/**
	 * constructor
	 *
	 * @return void
	 **/
	public function __construct()
	{
		$this->_data = array ('id'   => '',
							  'name' => '',
			                  );
	}

}