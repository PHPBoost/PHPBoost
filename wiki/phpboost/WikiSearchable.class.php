<?php
/*##################################################
 *		                    WikiSearchable.class.php
 *                            -------------------
 *   begin                : February 21, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
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
		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
		$args['WikiWhere'] = 'all';

		if ( $args['WikiWhere'] == 'all' )
		$req = "SELECT ".
		$args['id_search']." AS id_search,
				a.id AS id_content,
				a.title AS title,
				( 4 * FT_SEARCH_RELEVANCE(a.title, '".$args['search']."') +
				FT_SEARCH_RELEVANCE(c.content, '".$args['search']."') ) / 5 * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS link
				FROM " . PREFIX . "wiki_articles a
				LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
				WHERE ( FT_SEARCH(a.title, '".$args['search']."') OR FT_SEARCH(c.content, '".$args['search']."') )";
		else if ( $args['WikiWhere'] == 'contents' )
		$req = "SELECT ".
		$args['id_search']." AS id_search,
				a.id AS id_content,
				a.title AS title,
				FT_SEARCH_RELEVANCE(c.content, '".$args['search']."') * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS link
				FROM " . PREFIX . "wiki_articles a
				LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
				WHERE FT_SEARCH(c.content, '".$args['search']."')";
		else
		$req = "SELECT ".
		$args['id_search']." AS id_search,
				id AS id_content,
				title AS title,
				((FT_SEARCH_RELEVANCE(title, '".$args['search']."') )* " . $weight . ") AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',encoded_title) AS link
				FROM " . PREFIX . "wiki_articles
				WHERE FT_SEARCH(title, '".$args['search']."')";

		return $req;
	}
}
?>
