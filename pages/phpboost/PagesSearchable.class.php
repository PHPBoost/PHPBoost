<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 20
 * @since       PHPBoost 3.0 - 2010 05 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PagesSearchable extends AbstractSearchableExtensionPoint
{
	public function get_search_request($args)
	/**
	 *  Renvoie la requête de recherche
	 */
	{
		$search = $args['search'];
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		require_once(PATH_TO_ROOT . '/pages/pages_defines.php');
		$categories = PagesCategoriesCache::load()->get_categories();

		$unauth_cats = '';
		if (!AppContext::get_current_user()->check_auth(PagesConfig::load()->get_authorizations(), READ_PAGE))
			$unauth_cats .= '0,';
		foreach ($categories as $id => $cat)
		{
			if (!AppContext::get_current_user()->check_auth($cat['auth'], READ_PAGE))
				$unauth_cats .= $id.',';
		}
		$unauth_cats = !empty($unauth_cats) ? " AND p.id_cat NOT IN (" . trim($unauth_cats, ',') . ")" : '';

		$results = array();
		$result = PersistenceContext::get_querier()->select("SELECT ".
		$args['id_search']." AS `id_search`,
		p.id AS `id_content`,
		p.title AS `title`,
		( 2 * FT_SEARCH_RELEVANCE(p.title, '".$args['search']."' IN BOOLEAN MODE) + FT_SEARCH_RELEVANCE(p.contents, '".$args['search']."' IN BOOLEAN MODE) ) / 3 * " . $weight . " AS `relevance`,
		CONCAT('" . PATH_TO_ROOT . "/pages/pages.php?title=',p.encoded_title) AS `link`,
		p.auth AS `auth`
		FROM " . PREFIX . "pages p
		WHERE ( FT_SEARCH(title, '".$args['search']."*' IN BOOLEAN MODE) OR FT_SEARCH(contents, '".$args['search']."*' IN BOOLEAN MODE) )".$unauth_cats . "
		LIMIT 100 OFFSET 0");

		while ($row = $result->fetch())
		{
			if ( !empty($row['auth']) )
			{
				$auth = TextHelper::unserialize($row['auth']);
				if ( !AppContext::get_current_user()->check_auth($auth, READ_PAGE) )
				{
					unset($row['auth']);
					array_push($results, $row);
				}
			}
			else
			{
				unset($row['auth']);
				array_push($results, $row);
			}
		}
		$result->dispose();

		return $results;
	}
}
?>
