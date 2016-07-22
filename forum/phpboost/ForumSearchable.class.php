<?php
/*##################################################
 *		                    ForumSearchable.class.php
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

class ForumSearchable extends AbstractSearchableExtensionPoint
{
	private $db_querier;

	public function __construct()
	{
		$this->db_querier = PersistenceContext::get_querier();
		parent::__construct(true, true);
	}
	
	public function get_search_form($args)
	/**
	 *  Renvoie le formulaire de recherche du forum
	 */
	{
		global $LANG;

		$tpl = new FileTemplate('forum/forum_search_form.tpl');

		require_once(PATH_TO_ROOT . '/forum/forum_functions.php');
		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
		load_module_lang('forum'); //Chargement de la langue du module.

		$search = $args['search'];
		$idcat = !empty($args['ForumIdcat']) ? NumberHelper::numeric($args['ForumIdcat']) : -1;
		$time = !empty($args['ForumTime']) ? NumberHelper::numeric($args['ForumTime']) : 0;
		$where = !empty($args['ForumWhere']) ? TextHelper::strprotect($args['ForumWhere']) : 'all';
		
		//Liste des catégories.
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories_tree = ForumService::get_categories_manager()->get_select_categories_form_field('cats', '', $idcat, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$cat_list = '';
		foreach ($categories_tree_options as $option)
		{
			if ($option->get_raw_value())
			{
				$cat = ForumService::get_categories_manager()->get_categories_cache()->get_category($option->get_raw_value());
				if (!$cat->get_url())
					$cat_list .= $option->display()->render();
			}
		}
		
		$date_lang = LangLoader::get('date-common');
		$tpl->put_all(Array(
			'L_DATE' => $date_lang['date'],
			'L_DAY' => $date_lang['day'],
			'L_DAYS' => $date_lang['days'],
			'L_MONTH' => $date_lang['month'],
			'L_MONTHS' => $date_lang['month'],
			'L_YEAR' => $date_lang['year'],
			'IS_SELECTED_30000' => $time == 30000 ? ' selected="selected"' : '',
			'IS_SELECTED_1' => $time == 1 ? ' selected="selected"' : '',
			'IS_SELECTED_7' => $time == 7 ? ' selected="selected"' : '',
			'IS_SELECTED_15' => $time == 15 ? ' selected="selected"' : '',
			'IS_SELECTED_30' => $time == 30 ? ' selected="selected"' : '',
			'IS_SELECTED_180' => $time == 180 ? ' selected="selected"' : '',
			'IS_SELECTED_360' => $time == 360 ? ' selected="selected"' : '',
			'L_OPTIONS' => $LANG['options'],
			'L_TITLE' => $LANG['title'],
			'L_CONTENTS' => $LANG['content'],
			'IS_TITLE_CHECKED' => $where == 'title' ? ' checked="checked"' : '' ,
			'IS_CONTENTS_CHECKED' => $where == 'contents' ? ' checked="checked"' : '' ,
			'IS_ALL_CHECKED' => $where == 'all' ? ' checked="checked"' : '' ,
			'L_CATEGORY' => $LANG['category'],
			'L_ALL_CATS' => $LANG['all'],
			'IS_ALL_CATS_SELECTED' => ($idcat == '-1') ? ' selected="selected"' : '',
			'CATS' => $cat_list,
		));
		return $tpl->render();
	}

	public function get_search_args()
	/**
	 *  Renvoie la liste des arguments de la méthode <get_search_args>
	 */
	{
		return Array('ForumTime', 'ForumIdcat', 'ForumWhere');
	}

	public function get_search_request($args)
	/**
	 *  Renvoie la requête de recherche dans le forum
	 */
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$search = $args['search'];
		$idcat = !empty($args['ForumIdcat']) ? NumberHelper::numeric($args['ForumIdcat']) : -1;
		$time = (!empty($args['ForumTime']) ? NumberHelper::numeric($args['ForumTime']) : 30000) * 3600 * 24;
		$where = !empty($args['ForumWhere']) ? TextHelper::strprotect($args['ForumWhere']) : 'all';

		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
		$authorized_categories = ForumService::get_authorized_categories(Category::ROOT_CATEGORY);

		if ($where == 'all')         // All
			return "SELECT ".
			$args['id_search']." AS id_search,
				MIN(msg.id) AS id_content,
				t.title AS title,
				MAX(( 2 * FT_SEARCH_RELEVANCE(t.title, '" . $search."') + FT_SEARCH_RELEVANCE(msg.contents, '" . $search."') ) / 3) * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/forum/topic.php?id=', t.id, '#m', msg.id) AS link
			FROM " . PREFIX . "forum_msg msg
			JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
			JOIN " . PREFIX . "forum_cats c ON c.id_parent != 0 AND c.id = t.idcat
			WHERE ( FT_SEARCH(t.title, '" . $search."') OR FT_SEARCH(msg.contents, '" . $search."') ) AND msg.timestamp > '" . (time() - $time) . "'
			" . ($idcat > 0 ? " AND c.id = " . $idcat : '') . " AND c.id IN (" . implode(',', $authorized_categories) . ")
			GROUP BY t.id
			ORDER BY relevance DESC
			LIMIT " . FORUM_MAX_SEARCH_RESULTS;

		if ($where == 'contents')    // Contents
			return "SELECT ".
			$args['id_search']." AS id_search,
				MIN(msg.id) AS id_content,
				t.title AS title,
				MAX(FT_SEARCH_RELEVANCE(msg.contents, '" . $search."')) * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/forum/topic.php?id=', t.id, '#m', msg.id) AS link
			FROM " . PREFIX . "forum_msg msg
			JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
			JOIN " . PREFIX . "forum_cats c ON c.id_parent != 0 AND c.id = t.idcat
			WHERE FT_SEARCH(msg.contents, '" . $search."') AND msg.timestamp > '" . (time() - $time) . "'
			" . ($idcat > 0 ? " AND c.id = " . $idcat : '') . " AND c.id IN (" . implode(',', $authorized_categories) . ")
			GROUP BY t.id
			LIMIT " . FORUM_MAX_SEARCH_RESULTS;
		else                                         // Title only
		return "SELECT ".
		$args['id_search']." AS id_search,
				msg.id AS id_content,
				t.title AS title,
				FT_SEARCH_RELEVANCE(t.title, '" . $search."') * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/forum/topic.php?id=', t.id, '#m', msg.id) AS link
			FROM " . PREFIX . "forum_msg msg
			JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
			JOIN " . PREFIX . "forum_cats c ON c.id_parent != 0 AND c.id = t.idcat
			WHERE FT_SEARCH(t.title, '" . $search."') AND msg.timestamp > '" . (time() - $time) . "'
			" . ($idcat > 0 ? " AND c.id = " . $idcat : '') . " AND c.id IN (" . implode(',', $authorized_categories) . ")
			GROUP BY t.id
			LIMIT " . FORUM_MAX_SEARCH_RESULTS;
	}



	/**
	 * @desc Return the array containing the result's data list
	 * @param &string[][] $args The array containing the result's id list
	 * @return string[] The array containing the result's data list
	 */
	public function compute_search_results($args)
	{
		$results_data = array();

		$results =& $args['results'];
		$nb_results = count($results);

		$ids = array();
		for ($i = 0; $i < $nb_results; $i++)
		$ids[] = $results[$i]['id_content'];

		$request = "
		SELECT
			msg.id AS msg_id,
			msg.user_id AS user_id,
			msg.idtopic AS topic_id,
			msg.timestamp AS date,
			t.title AS title,
			m.display_name AS login,
			ext_field.user_avatar AS avatar,
			s.user_id AS connect,
			msg.contents AS contents
		FROM " . PREFIX . "forum_msg msg
		LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' AND s.user_id != -1
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
		JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
		WHERE msg.id IN (".implode(',', $ids).")
		GROUP BY t.id";

		$result = $this->db_querier->select($request);
		while ($row = $result->fetch())
		{
			$results_data[] = $row;
		}
		$result->dispose();

		return $results_data;
	}

	/**
	 *  @desc Return the string to print the result
	 *  @param &string[] $result_data the result's data
	 *  @return string[] The string to print the result of a search element
	 */
	public function parse_search_result($result_data)
	{
		global $LANG;

		load_module_lang('forum'); //Chargement de la langue du module.

		$tpl = new FileTemplate('forum/forum_generic_results.tpl');

		$tpl->put_all(Array(
			'L_ON' => $LANG['on'],
			'L_TOPIC' => $LANG['topic']
		));
		$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($result_data['title']) : '';
		$tpl->put_all(array(
			'USER_ONLINE' => '<i class="fa ' . ((!empty($result_data['connect']) && $result_data['user_id'] !== -1) ? 'fa-online' : 'fa-offline') . '"></i>',
			'U_USER_PROFILE' => !empty($result_data['user_id']) ? UserUrlBuilder::profile($result_data['user_id'])->rel() : '',
			'USER_PSEUDO' => !empty($result_data['display_name']) ? $result_data['display_name'] : $LANG['guest'],
			'U_TOPIC' => PATH_TO_ROOT . '/forum/topic' . url('.php?id=' . $result_data['topic_id'], '-' . $result_data['topic_id'] . $rewrited_title . '.php') . '#m' . $result_data['msg_id'],
			'TITLE' => stripslashes($result_data['title']),
			'DATE' => Date::to_format($result_data['date'], 'd/m/y'),
			'CONTENTS' => FormatingHelper::second_parse(stripslashes($result_data['contents'])),
			'USER_AVATAR' => '<img src="' . (UserAccountsConfig::load()->is_default_avatar_enabled() && !empty($result_data['avatar']) ? $result_data['avatar'] : PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  UserAccountsConfig::load()->get_default_avatar_name()) . '" alt="' . LangLoader::get_message('avatar', 'user-common') . '" class="message-avatar"/>'
		));

			return $tpl->render();
	}
}
?>
