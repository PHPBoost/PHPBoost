<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2012 02 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
		$view = new FileTemplate('forum/forum_search_form.tpl');
		$view->add_lang(LangLoader::get_all_langs('forum'));

		require_once(PATH_TO_ROOT . '/forum/forum_functions.php');
		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');

		$search      = $args['search'];
		$id_category = !empty($args['ForumIdcat']) ? NumberHelper::numeric($args['ForumIdcat']) : -1;
		$time        = !empty($args['ForumTime']) ? NumberHelper::numeric($args['ForumTime']) : 0;
		$where       = !empty($args['ForumWhere']) ? TextHelper::strprotect($args['ForumWhere']) : 'all';

		//Liste des catégories.
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories_tree = CategoriesService::get_categories_manager('forum')->get_select_categories_form_field('cats', '', $id_category, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$cat_list = '';
		foreach ($categories_tree_options as $option)
		{
			if ($option->get_raw_value())
			{
				$cat = CategoriesService::get_categories_manager('forum')->get_categories_cache()->get_category($option->get_raw_value());
				if (!$cat->get_url())
					$cat_list .= $option->display()->render();
			}
		}

		$view->put_all(Array(
			'IS_SELECTED_30000'    => $time == 30000 ? ' selected="selected"' : '',
			'IS_SELECTED_1'        => $time == 1 ? ' selected="selected"' : '',
			'IS_SELECTED_7'        => $time == 7 ? ' selected="selected"' : '',
			'IS_SELECTED_15'       => $time == 15 ? ' selected="selected"' : '',
			'IS_SELECTED_30'       => $time == 30 ? ' selected="selected"' : '',
			'IS_SELECTED_180'      => $time == 180 ? ' selected="selected"' : '',
			'IS_SELECTED_360'      => $time == 360 ? ' selected="selected"' : '',
			'IS_TITLE_CHECKED'     => $where == 'title' ? ' checked="checked"'  : '' ,
			'IS_CONTENT_CHECKED'   => $where == 'content' ? ' checked="checked"'  : '' ,
			'IS_ALL_CHECKED'       => $where == 'all' ? ' checked="checked"'  : '' ,
			'IS_ALL_CATS_SELECTED' => ($id_category == '-1') ? ' selected="selected"' : '',

			'CATS' => $cat_list,
		));
		return $view->render();
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
		$id_category = !empty($args['ForumIdcat']) ? NumberHelper::numeric($args['ForumIdcat']) : -1;
		$time = (!empty($args['ForumTime']) ? NumberHelper::numeric($args['ForumTime']) : 30000) * 3600 * 24;
		$where = !empty($args['ForumWhere']) ? TextHelper::strprotect($args['ForumWhere']) : 'all';

		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, 'forum');

		if ($where == 'all')         // All
			return "SELECT ".
			$args['id_search']." AS id_search,
				MIN(msg.id) AS id_content,
				t.title AS title,
				MAX(( 2 * FT_SEARCH_RELEVANCE(t.title, '" . $search."' IN BOOLEAN MODE) + FT_SEARCH_RELEVANCE(msg.content, '" . $search."' IN BOOLEAN MODE) ) / 3) * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/forum/topic.php?id=', t.id, '#m', msg.id) AS link
			FROM " . PREFIX . "forum_msg msg
			JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
			JOIN " . PREFIX . "forum_cats c ON c.id_parent != 0 AND c.id = t.id_category
			WHERE ( FT_SEARCH(t.title, '" . $search."*' IN BOOLEAN MODE) OR FT_SEARCH(msg.content, '" . $search."*' IN BOOLEAN MODE) ) AND msg.timestamp > '" . (time() - $time) . "'
			" . ($id_category > 0 ? " AND c.id = " . $id_category : '') . " AND c.id IN (" . implode(',', $authorized_categories) . ")
			GROUP BY t.id, id_search, title, link, msg.timestamp
			ORDER BY msg.timestamp DESC, relevance DESC
			LIMIT " . FORUM_MAX_SEARCH_RESULTS;

		if ($where == 'content')    // Contents
			return "SELECT ".
			$args['id_search']." AS id_search,
				MIN(msg.id) AS id_content,
				t.title AS title,
				MAX(FT_SEARCH_RELEVANCE(msg.content, '" . $search."' IN BOOLEAN MODE)) * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/forum/topic.php?id=', t.id, '#m', msg.id) AS link
			FROM " . PREFIX . "forum_msg msg
			JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
			JOIN " . PREFIX . "forum_cats c ON c.id_parent != 0 AND c.id = t.id_category
			WHERE FT_SEARCH(msg.content, '" . $search."*' IN BOOLEAN MODE) AND msg.timestamp > '" . (time() - $time) . "'
			" . ($id_category > 0 ? " AND c.id = " . $id_category : '') . " AND c.id IN (" . implode(',', $authorized_categories) . ")
			GROUP BY t.id, id_search, title, link, msg.timestamp
			ORDER BY msg.timestamp DESC, relevance DESC
			LIMIT " . FORUM_MAX_SEARCH_RESULTS;
		else                                         // Title only
		return "SELECT ".
		$args['id_search']." AS id_search,
				msg.id AS id_content,
				t.title AS title,
				FT_SEARCH_RELEVANCE(t.title, '" . $search."' IN BOOLEAN MODE) * " . $weight . " AS relevance,
				CONCAT('" . PATH_TO_ROOT . "/forum/topic.php?id=', t.id, '#m', msg.id) AS link
			FROM " . PREFIX . "forum_msg msg
			JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
			JOIN " . PREFIX . "forum_cats c ON c.id_parent != 0 AND c.id = t.id_category
			WHERE FT_SEARCH(t.title, '" . $search."*' IN BOOLEAN MODE) AND msg.timestamp > '" . (time() - $time) . "'
			" . ($id_category > 0 ? " AND c.id = " . $id_category : '') . " AND c.id IN (" . implode(',', $authorized_categories) . ")
			GROUP BY t.id, id_search, id_content, title, link, msg.timestamp
			ORDER BY msg.timestamp DESC, relevance DESC
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

		$request = "SELECT
			t.id AS t_id, t.title AS title,
			msg.id AS msg_id, msg.user_id AS user_id, msg.idtopic AS topic_id, msg.timestamp AS date, msg.content AS content,
			m.display_name,
			ext_field.user_avatar AS avatar,
			s.user_id AS connect
		FROM " . PREFIX . "forum_msg msg
		LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.timestamp > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "' AND s.user_id != -1
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = msg.user_id
		JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
		WHERE msg.id IN (".implode(',', $ids).")
		GROUP BY t_id, msg_id, user_id, topic_id, date, title, display_name, avatar, connect, content";

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
		$view = new FileTemplate('forum/forum_generic_results.tpl');
		$view->add_lang(LangLoader::get_all_langs('forum'));

		$rewrited_title = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($result_data['title']) : '';

		$result_date = new Date($result_data['date'], Timezone::SERVER_TIMEZONE);

		$user_accounts_config = UserAccountsConfig::load();

		$view->put_all(array_merge(
			Date::get_array_tpl_vars($result_date, 'DATE'), array(
			'C_USER_ONLINE'      => !empty($result_data['connect']) && $result_data['user_id'] !== -1,
			'C_USER_PSEUDO'      => !empty($result_data['display_name']),
			'C_USER_AVATAR' 	 => $user_accounts_config->is_default_avatar_enabled() || !empty($result_data['avatar']),
			'C_USER_HAS_AVATAR'  => !empty($result_data['avatar']),

			'USER_PSEUDO'        => $result_data['display_name'],
			'TITLE'              => stripslashes($result_data['title']),
			'CONTENT'            => FormatingHelper::second_parse(stripslashes($result_data['content'])),

			'U_USER_PROFILE'     => !empty($result_data['user_id']) ? UserUrlBuilder::profile($result_data['user_id'])->rel() : '',
			'U_TOPIC'            => PATH_TO_ROOT . '/forum/topic' . url('.php?id=' . $result_data['topic_id'], '-' . $result_data['topic_id'] . $rewrited_title . '.php') . '#m' . $result_data['msg_id'],
			'U_DEFAULT_AVATAR'   => $user_accounts_config->get_default_avatar(),
			'U_USER_AVATAR'      => Url::to_rel($result_data['avatar'])
		)));

		return $view->render();
	}
}
?>
