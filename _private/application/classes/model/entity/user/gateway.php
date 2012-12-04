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
	public function getUsers($id, $username, $password, $site)
	{
		if (true === isset($id)) {
			$exec = "SELECT
                         u.id, u.username, md5(u.password) as password
			         FROM
			             users u
			         WHERE
			             u.id = :id
			         ORDER BY u.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':id', $id);
			// @TODO - filterby siteid too for security reasons

		} elseif (true === isset($username) && true === isset($password) && true === isset($site)) {
			// get by by site category
			$exec = "SELECT
                         u.id, u.username, md5(u.password) as password
			         FROM
			             users u
			         LEFT JOIN
			         	sites s ON s.id = u.site_id
			         WHERE
			             (u.username = :username
			             AND
			             u.password = :password)
			             AND
			             (s.url = :site
			             	OR
			              u.site_id = 0)
			         ORDER BY u.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':username', $username);
			$db->param(':password', $password);
			$db->param(':site', $site);
		} elseif (true === isset($site)) {
			$exec = "SELECT
                         u.id, u.username, md5(u.password) as password
			         FROM
			             users u
			         LEFT JOIN
			         	sites s ON s.id = u.site_id
			         WHERE
			             s.url = :site
			             OR 
			             u.site_id = 0;
			         ORDER BY u.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':site', $site);
		}

		$users = $db->execute()
			        ->as_array();
		
		return $users;
		
		print_r($users);

		return array();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function updateUser($site, $userId, $username, $albums)
	{
		if (true === isset($albums) && true === is_array($albums)) {
			$exec = "DELETE FROM user_album
					 WHERE
					    user_id = :userid";
			$db = DB::query(Database::DELETE, $exec);
			$db->param(':userid', $userId);

			$db->execute();

			unset($db);

			$values = "('',-1)";
			foreach ($albums as $albumId) {
				$values .= ", ('" . $userId . "', '" . $albumId . "')";
			}

			$exec = 'INSERT IGNORE INTO
						user_album (user_id, album_id)
					 VALUES ' . $values;
			$db = DB::query(Database::INSERT, $exec);
			return $db->execute();
		}
		return true;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function createUser($site, $username, $password)
	{
		$exec = "INSERT INTO users (username, password, site_id)
				 SELECT
				 	:username, :password, s.id
				 FROM 
				 	sites s
				 WHERE 
				 	s.url = :site";
		$db = DB::query(Database::INSERT, $exec);
		$db->param(':username', $username);
		$db->param(':password', $password);
		$db->param(':site', $site);

		try {
			$response = $db->execute();
		} catch (Exception $ex) {
			$response = $ex->getMessage();
		}

		return $response;
	}
}