<?php
/**
 * User Gateway
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_User_Gateway
{
	/**
	 * Get User from DB
	 *
	 * @return void
	 * @author 
	 **/
	public function getUsers($id, $username, $password)
	{
		if (true === isset($id)) {
			// get by uniq id
		} elseif (true === isset($username) && true === isset($password)) {
			// get by by site category
			$exec = "SELECT
                         u.id, u.username, md5(u.password) as password
			         FROM
			             users u
			         WHERE
			             u.username = :username
			             AND
			             u.password = :password
			         ORDER BY u.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':username', $username);
			$db->param(':password', $password);
		}

		$users = $db->execute()
			        ->as_array();
		
		return $users;
		
		print_r($users);

		return array();
	}
}