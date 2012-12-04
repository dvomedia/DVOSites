<?php
/**
 * User Entity
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_User extends Model_Entity_Abstract
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
							  'username' => '',
							  'password' => '',
							  'albums' => ''
			                  );
	}

}