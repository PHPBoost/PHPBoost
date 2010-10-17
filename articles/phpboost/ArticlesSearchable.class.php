<?php
/*##################################################
 *                           ArticlesSearchable.class.php
 *                            -------------------
 *   begin                : October 17, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class ArticlesSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($search_text)
	{
		global $Cache, $CONFIG_ARTICLES, $ARTICLES_CAT, $LANG,$ARTICLES_LANG;
		$Cache->load('articles');
		require_once PATH_TO_ROOT . '/articles/articles_constants.php';

		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$user = AppContext::get_user();
		//Catégories non autorisées.
		$unauth_cats_sql = array();
		foreach ($ARTICLES_CAT as $idcat => $key)
		{
			if ($ARTICLES_CAT[$idcat]['visible'] == 1)
			{
				if (!$user->check_auth($ARTICLES_CAT[$idcat]['auth'], AUTH_ARTICLES_READ))
				{
					$clause_level = !empty($g_idcat) ? ($ARTICLES_CAT[$idcat]['order'] == ($ARTICLES_CAT[$g_idcat]['order'] + 1)) : ($ARTICLES_CAT[$idcat]['order'] == 0);
					if ($clause_level)
					$unauth_cats_sql[] = $idcat;
				}
			}
		}
		$clause_unauth_cats = (count($unauth_cats_sql) > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

		$request = "SELECT
				" . $args['id_search'] . " AS id_search,
	             a.id AS id_content,
	             a.title AS title,
	             ( 2 * FT_SEARCH_RELEVANCE(a.title, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(a.contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
	             . $this->sql_querier->concat("'" . PATH_TO_ROOT . "/articles/articles.php?id='","a.id","'&amp;cat='","a.idcat") . " AS link
				FROM " . PREFIX . "articles a
				LEFT JOIN " . PREFIX . "articles_cats ac ON ac.id = a.idcat
				WHERE
					a.visible = 1 AND ((ac.visible = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%') OR a.idcat = 0)
					AND (FT_SEARCH(a.title, '" . $args['search'] . "') OR FT_SEARCH(a.contents, '" . $args['search'] . "'))
				ORDER BY relevance DESC " . $this->sql_querier->limit(0, $CONFIG_ARTICLES['nbr_articles_max']);

	             return $request;
	}
}

?>