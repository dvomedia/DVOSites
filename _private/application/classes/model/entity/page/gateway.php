<?php
/**
 * Page Gateway
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_Page_Gateway
{
	/**
	 * Get Person from DB
	 *
	 * @return void
	 * @author 
	 **/
	public function getPage($id, $site, $slug)
	{
		/*
		$exec = 'SELECT id, name FROM people WHERE id = :id';

		$db   = DB::query(Database::SELECT, $exec);
		$db->param(':id', $id);
		$retailers = $db->execute()
			         	->as_array();
		*/

		$pages = ['dev.dvosites.co.uk' => ['home'  => ['id' => 1, 'title' => 'the title of the page', 'content' => 'the content'],
	                                       'about' => ['id' => 2, 'title' => 'about title', 'content' => 'about innit'],
	                                       'contact' => ['id' => 3, 'title' => 'title for contact', 'content' => 'contact us'],
	                                       ]];

		if (true === isset($pages[$site][$slug])) {
			$page[] = $pages[$site][$slug];
			return $page;
		}

		return array();
	}
}