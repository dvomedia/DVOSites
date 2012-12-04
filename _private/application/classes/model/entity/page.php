<?php
/**
 * Page Entity
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_Page extends Model_Entity_Abstract
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
							  'template' => '',
							  'content'  => '',
							  'slug'     => '',
							  'special'  => '',
							  'category_title' => '',
							  'site_id' => '',
							  'protected' => '',
							  'site_title' => '',
							  'site_url' => '',
							  'skin'	=> '',
			                  );
	}

}