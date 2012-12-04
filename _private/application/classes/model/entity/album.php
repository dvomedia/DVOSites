<?php
/**
 * Album Entity
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_Album extends Model_Entity_Abstract
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
							  'title' => '',
							  'photos' => ''
			                  );
	}

}