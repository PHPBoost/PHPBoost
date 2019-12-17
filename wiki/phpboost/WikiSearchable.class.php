<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 20
 * @since       PHPBoost 3.0 - 2012 01 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class WikiSearchable extends AbstractSearchableExtensionPoint
{
	public function __construct()
	{
		parent::__construct(true, false);
	}

	public function get_search_form($args=null)
	{
		require_once(PATH_TO_ROOT . '/kernel/begin.php');
		load_module_lang('wiki');
		global $LANG;

		$tpl = new FileTemplate('wiki/wiki_search_form.tpl');

		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], array('title', 'contents', 'all')) )
		$args['WikiWhere'] = 'all';

		$tpl->put_all(Array(
			'L_WHERE' => $LANG['wiki_search_where'],
			'IS_TITLE_SELECTED' => $args['WikiWhere'] == 'title'? ' selected="selected"': '',
			'IS_CONTENTS_SELECTED' => $args['WikiWhere'] == 'contents'? ' selected="selected"': '',
			'IS_ALL_SELECTED' => $args['WikiWhere'] == 'all'? ' selected="selected"': '',
			'L_TITLE' => $LANG['wiki_search_where_title'],
			'L_CONTENTS' => $LANG['wiki_search_where_contents']
		));

		return $tpl->render();
	}

	public function get_search_args()
	{
		return Array('WikiWhere');
	}

	public function get_search_request($args)
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], array('title', 'contents', 'all')) )
		$args['WikiWhere'] = 'all';

		if ( $args['WikiWhere'] == 'all' )
		$req = "SELECT ".
				$args['id_search']." AS id_search,
				a.id AS id_content,
				a.title AS title,
				( 2 * FT_SEARCH_RELEVANCE(a.title, '".$args['search']."' IN BOOLEAN MODE) +
				FT_SEARCH_RELEVANCE(c.content, '".$args['search']."' IN BOOLEAN MODE) ) / 3 * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS link
				FROM " . PREFIX . "wiki_articles a
				LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
				WHERE ( FT_SEARCH(a.title, '".$args['search']."*' IN BOOLEAN MODE) OR FT_SEARCH(c.content, '".$args['search']."*' IN BOOLEAN MODE) )
				ORDER BY relevance DESC
				LIMIT 100 OFFSET 0";
		else if ( $args['WikiWhere'] == 'contents' )
		$req = "SELECT ".
				$args['id_search']." AS id_search,
				a.id AS id_content,
				a.title AS title,
				FT_SEARCH_RELEVANCE(c.content, '".$args['search']."' IN BOOLEAN MODE) * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS link
				FROM " . PREFIX . "wiki_articles a
				LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
				WHERE FT_SEARCH(c.content, '".$args['search']."*' IN BOOLEAN MODE)
				ORDER BY relevance DESC
				LIMIT 100 OFFSET 0";
		else
		$req = "SELECT ".
				$args['id_search']." AS id_search,
				id AS id_content,
				title AS title,
				((FT_SEARCH_RELEVANCE(title, '".$args['search']."' IN BOOLEAN MODE) )* " . $weight . ") AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',encoded_title) AS link
				FROM " . PREFIX . "wiki_articles
				WHERE FT_SEARCH(title, '".$args['search']."*' IN BOOLEAN MODE)
				ORDER BY relevance DESC
				LIMIT 100 OFFSET 0";

		return $req;
	}
}
?>
