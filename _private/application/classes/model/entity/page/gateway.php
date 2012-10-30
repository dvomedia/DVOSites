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
	public function getPages($id, $site, $slug, $category)
	{
		if (true === isset($id)) {
			// get by uniq id)
		} elseif (true === isset($site) && true === isset($category)) {
			// get by by site category
			$exec = "SELECT
                         p.id,
                         p.title,
                         p.content,
                         p.slug,
                         c.title as 'category_title',
                         p.special,
                         p.site_id,
                         p.template,
                         p.protected,
                         s.title as 'site_title',
                         s.url as 'site_url'

			         FROM
			             pages p
			         INNER JOIN 
			             sites s ON s.id = p.site_id
			         LEFT JOIN
			             category c ON c.id = p.category_id
			         WHERE
			             s.url = :site
			             AND
			             c.title = :category
			         ORDER BY p.id DESC";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':site', $site);
			$db->param(':category', $category);

		} elseif (true === isset($site) && true === isset($slug)) {
			$exec = "SELECT
                         p.id,
                         p.title,
                         p.content,
                         p.slug,
                         c.title as 'category_title',
                         p.special,
                         p.site_id,
                         p.template,
                         p.protected,
                         s.title as 'site_title',
                         s.url as 'site_url'
			         FROM
			             pages p
			         INNER JOIN 
			             sites s ON s.id = p.site_id
			         LEFT JOIN
			             category c ON c.id = p.category_id
			         WHERE
			             s.url = :site
			             AND
			             p.slug = :slug
			         LIMIT 0,1";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':site', $site);
			$db->param(':slug', $slug);
		} elseif (true === isset($site)) {
			$exec = "SELECT
                         p.id,
                         p.title,
                         p.content,
                         p.slug,
                         c.title as 'category_title',
                         p.special,
                         p.site_id,
                         p.template,
                         p.protected,
                         s.title as 'site_title',
                         s.url as 'site_url'
			         FROM
			             pages p
			         INNER JOIN 
			             sites s ON s.id = p.site_id
			         LEFT JOIN
			             category c ON c.id = p.category_id
			         WHERE
			             s.url = :site
			         LIMIT 0,10";
			$db   = DB::query(Database::SELECT, $exec);
			$db->param(':site', $site);
		}

		$pages = $db->execute()
			        ->as_array();
		
		return $pages;
		/*		         	
		$pages = ['dev.dvosites.co.uk' => ['home'  => ['id' => 1, 'title' => 'the title of the page', 'content' => 'the content'],
	                                       'about' => ['id' => 2, 'title' => 'about title', 'content' => 'about innit'],
	                                       'contact' => ['id' => 3, 'title' => 'title for contact', 'content' => 'contact us'],
	                                       'news' => ['id' => 4, 'title' => 'title for news', 'content' => 'news', 'special' => ['category' => 'news']],
	                                       ]];

		if (true === isset($pages[$site][$slug])) {
			$page[] = $pages[$site][$slug];
			return $page;
		}
		*/
		print_r($pages);

		return array();
	}
}