<?php
/**
 * Album Gateway
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_Album_Gateway
{
	/**
	 * Get Album from DB
	 *
	 * @return void
	 * @author 
	 **/
	public function getAlbums($id, $site, $userId)
	{
		if (true === isset($id)) {
			// get by uniq id
			$exec = "SELECT
                         a.id, a.title
			         FROM
			             albums a
			         INNER JOIN 
			             sites s ON s.id = a.site_id
			         WHERE
			         	s.url = :site
			         AND
			         	a.id = :id
			         ORDER BY a.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':id', $id);
			$db->param(':site', $site);
		} elseif (true === isset($userId)) {
			$exec = "SELECT
                         a.id, a.title
			         FROM
			             albums a
			         INNER JOIN 
			             sites s ON s.id = a.site_id
			         INNER JOIN
			             user_album ua ON ua.album_id = a.id
			         WHERE
			         	ua.user_id = :userid
			         ORDER BY a.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':userid', $userId);
		} elseif (true === isset($site)) {
			// get by by site category
			$exec = "SELECT
                         a.id, a.title
			         FROM
			             albums a
			         INNER JOIN 
			             sites s ON s.id = a.site_id
			         WHERE
			         	s.url = :site
			         ORDER BY a.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':site', $site);
		}

		$albums = $db->execute()
			         ->as_array();
		
		return $albums;
		
		print_r($albums);

		return array();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function createAlbum($site, $title)
	{
		if (true === isset($site)) {
			$exec = "INSERT INTO
						albums (site_id, title)
					 SELECT
					 	s.id, :title
					 FROM
					 	sites s
					 WHERE
					 	s.url = :site";
			$db   = DB::query(Database::INSERT, $exec);
			$db->param(':site', $site);
			$db->param(':title', $title);

			//print 'hello' . $db->compile(Database::instance());
			return $db->execute();
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function updateAlbum($site, $id, $title, $photos)
	{
		if (true === isset($site)) {
			$exec = "UPDATE 
					     albums a
					 INNER JOIN
		         		sites s ON s.id = a.site_id
					 SET
					 	a.title = :title
					 WHERE
					 	a.id = :id
					 	AND
					 	s.url = :site";
			$db   = DB::query(Database::UPDATE, $exec);

			$db->param(':id', $id);
			$db->param(':site', $site);
			$db->param(':title', $title);

			Kohana::$log->add(Kohana_Log::DEBUG, '[:class] Query :query', array(':class' => __CLASS__, ':query' => $db->compile(Database::instance())));

			if (false === is_array($photos) || count($photos) === 0) {
				return $db->execute();	
			} 
			
			$db->execute();	

			unset($db);

			$values = "('',-1)";
			foreach ($photos as $photo) {
				$values .= ", ('" . $photo . "', '" . $id . "')";
			}

			$exec = 'INSERT IGNORE INTO
						photos (url, album_id)
					 VALUES ' . $values;
			$db = DB::query(Database::INSERT, $exec);

			Kohana::$log->add(Kohana_Log::DEBUG, '[:class] Query :query', array(':class' => __CLASS__, ':query' => $db->compile(Database::instance())));
			return $db->execute();	

		}
	}
}