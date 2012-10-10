<?php
/**
 * Person Gateway
 *
 * @package Mwl
 * @author  Mindwork Labs
 */
class Model_Entity_Person_Gateway
{
	/**
	 * Get Person from DB
	 *
	 * @return void
	 * @author 
	 **/
	public function getPerson($id)
	{
		/*
		$exec = 'SELECT id, name FROM people WHERE id = :id';

		$db   = DB::query(Database::SELECT, $exec);
		$db->param(':id', $id);
		$retailers = $db->execute()
			         	->as_array();
		*/

		$retailers = [['id' => 'test', 'name' => 'foo']];
		return $retailers;
	}
}