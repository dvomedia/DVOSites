<?php
/**
 * User Gateway
 *
 * @package DVO
 * @author  Bobby DeVeaux
 */
class Model_Entity_Photo_Gateway
{
	/**
	 * Get User from DB
	 *
	 * @return void
	 * @author 
	 **/
	public function getPhotos($id, $userId, $albumId)
	{
		if (true === isset($id)) {
			// get by uniq id
		} elseif (true === isset($userId)) {
			// get by by site category
			$exec = "SELECT
			             ph.id, ph.caption, ph.url
				     FROM
				         albums a
					 INNER JOIN
					     user_album ua ON ua.album_id=a.id
					 INNER JOIN
					     photos ph ON ph.album_id=a.id
			         WHERE
			             ua.user_id = :userid
			         ORDER BY ph.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':userid', $userId);
			// echo $db->compile(Database::instance());
		} elseif (true === isset($albumId)) {
			$exec = "SELECT
                         ph.id, ph.caption, ph.url
			         FROM
			             photos ph
			         WHERE
			             ph.album_id = :album_id";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':album_id', $albumId);
			Kohana::$log->add(Kohana_Log::DEBUG, '[:class] Query :query', array(':class' => __CLASS__, ':query' => $db->compile(Database::instance())));
		}

		$photos = $db->execute()
			        ->as_array();
		
		return $photos;
		
		print_r($photos);

		return array();
	}
}