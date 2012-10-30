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
	public function getPhotos($id, $userId)
	{
		if (true === isset($id)) {
			// get by uniq id
		} elseif (true === isset($userId)) {
			// get by by site category
			$exec = "SELECT
                         ph.id, ph.caption, ph.url
			         FROM
			             photos ph
			         INNER JOIN
			         	user_photo up
			         	  ON 
			         	  ph.id=up.photo_id
			         INNER JOIN
			            users u
			              ON
			              u.id=up.user_id
			         WHERE
			             u.id = :userid
			         ORDER BY ph.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':userid', $userId);
		}

		$photos = $db->execute()
			        ->as_array();
		
		return $photos;
		
		print_r($photos);

		return array();
	}
}