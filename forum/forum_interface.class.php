<?php
/*##################################################
 *                              forum_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2007 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

define('FORUM_MAX_SEARCH_RESULTS', 50);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ForumInterface extends ModuleInterface
{
	## Public Methods ##
	function ForumInterface() //Constructeur de la classe ForumInterface
	{
		parent::ModuleInterface('forum');
	}

	//Récupération du cache.
	function get_cache()
	{
		global $Sql;

		//Configuration du forum
		$forum_config = 'global $CONFIG_FORUM;' . "\n";

		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_FORUM = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'forum'", __LINE__, __FILE__));
		$CONFIG_FORUM['auth'] = unserialize($CONFIG_FORUM['auth']);
			
		$forum_config .= '$CONFIG_FORUM = ' . var_export($CONFIG_FORUM, true) . ';' . "\n";

		//Liste des catégories du forum
		$i = 0;
		$forum_cats = 'global $CAT_FORUM;' . "\n";
		$result = $Sql->query_while("SELECT id, id_left, id_right, level, name, url, status, aprob, auth, aprob
		FROM " . PREFIX . "forum_cats
		ORDER BY id_left", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if (empty($row['auth']))
			$row['auth'] = serialize(array());

			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'status\'] = ' . var_export($row['status'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'url\'] = ' . var_export($row['url'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
		}
		$Sql->query_close($result);

		return $forum_config . "\n" . $forum_cats;
	}

	//Changement de jour.
	function on_changeday()
	{
		global $Sql, $Cache, $CONFIG_FORUM;

		//Suppression des marqueurs de vue du forum trop anciens.
		$Cache->load('forum'); //Requête des configuration générales (forum), $CONFIG_FORUM variable globale.
		$Sql->query_inject("DELETE FROM " . PREFIX . "forum_view WHERE timestamp < '" . (time() - $CONFIG_FORUM['view_time']) . "'", __LINE__, __FILE__);
	}

	//Récupère le lien vers la listes des messages du membre.
	function get_member_msg_link($memberId)
	{
		return PATH_TO_ROOT . '/forum/membermsg.php?id=' . $memberId[0];
	}

	//Récupère le nom associé au lien.
	function get_member_msg_name()
	{
		global $LANG;
		load_module_lang('forum'); //Chargement de la langue du module.

		return $LANG['forum'];
	}

	//Récupère l'image associé au lien.
	function get_member_msg_img()
	{
		return PATH_TO_ROOT . '/forum/forum_mini.png';
	}

	// Recherche
	function get_search_form($args=null)
	/**
	 *  Renvoie le formulaire de recherche du forum
	 */
	{
		global $User, $MODULES, $Errorh, $CONFIG, $CONFIG_FORUM, $Cache, $CAT_FORUM, $LANG, $Sql;

		import('io/template/template');
		$Tpl = new Template('forum/forum_search_form.tpl');

		//Autorisation sur le module.
		if (isset($MODULES['forum']) && $MODULES['forum']['activ'] == 1)
		{
			if (!$User->check_auth($MODULES['forum']['auth'], ACCESS_MODULE)) //Accès non autorisé!
			$Errorh->handler('e_auth', E_USER_REDIRECT);
		}

		require_once(PATH_TO_ROOT . '/forum/forum_functions.php');
		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
		load_module_lang('forum'); //Chargement de la langue du module.
		$Cache->load('forum');

		$search = $args['search'];
		$idcat = !empty($args['ForumIdcat']) ? numeric($args['ForumIdcat']) : -1;
		$time = !empty($args['ForumTime']) ? numeric($args['ForumTime']) : 0;
		$where = !empty($args['ForumWhere']) ? strprotect($args['ForumWhere']) : 'all';
		$colorate_result = !empty($args['ForumColorate_result']) ? true : false;

		$Tpl->assign_vars(Array(
            'L_DATE' => $LANG['date'],
            'L_DAY' => $LANG['day'],
            'L_DAYS' => $LANG['day_s'],
            'L_MONTH' => $LANG['month'],
            'L_MONTHS' => $LANG['month'],
            'L_YEAR' => $LANG['year'],
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
            'L_COLORATE_RESULTS' => $LANG['colorate_result'],
            'IS_COLORATION_CHECKED' => $colorate_result ? 'checked="checked"' : '',
            'L_CATEGORY' => $LANG['category'],
            'L_ALL_CATS' => $LANG['all'],
            'IS_ALL_CATS_SELECTED' => ($idcat == '-1') ? ' selected="selected"' : '',
		));
		if (is_array($CAT_FORUM))
		{
			foreach ($CAT_FORUM as $id => $key)
			{
				if ($User->check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM))
				{
					$Tpl->assign_block_vars('cats', array(
                        'MARGIN' => ($key['level'] > 0) ? str_repeat('----------', $key['level']) : '----',
                        'ID' => $id,
                        'L_NAME' => $key['name'],
                        'IS_SELECTED' => ($id == $idcat) ? ' selected="selected"' : ''
                        ));
				}
			}
		}
		return $Tpl->parse(Template::TEMPLATE_PARSER_STRING);
	}

	function get_search_args()
	/**
	 *  Renvoie la liste des arguments de la méthode <get_search_args>
	 */
	{
		return Array('ForumTime', 'ForumIdcat', 'ForumWhere', 'ForumColorate_result');
	}

	function get_search_request($args)
	/**
	 *  Renvoie la requête de recherche dans le forum
	 */
	{
		global $CONFIG, $CAT_FORUM, $User, $Cache, $Sql;
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		$Cache->load('forum');

		$search = $args['search'];
		$idcat = !empty($args['ForumIdcat']) ? numeric($args['ForumIdcat']) : -1;
		$time = !empty($args['ForumTime']) ? numeric($args['ForumTime']) : 0;
		$where = !empty($args['ForumWhere']) ? strprotect($args['ForumWhere']) : 'title';
		$colorate_result = !empty($args['ForumColorate_result']) ? true : false;

		require_once(PATH_TO_ROOT . '/forum/forum_defines.php');
		$auth_cats = '';
		if (is_array($CAT_FORUM))
		{
			foreach ($CAT_FORUM as $id => $key)
			{
				if (!$User->check_auth($CAT_FORUM[$id]['auth'], READ_CAT_FORUM))
				$auth_cats .= $id.',';
			}
		}
		$auth_cats = !empty($auth_cats) ? " AND c.id NOT IN (" . trim($auth_cats, ',') . ")" : '';

		if ($where == 'all')         // All
		return "SELECT ".
		$args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(( 2 * MATCH(t.title) AGAINST('" . $search."') + MATCH(msg.contents) AGAINST('" . $search."') ) / 3) * " . $weight . " AS `relevance`,
                " . $Sql->concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM " . PREFIX . "forum_msg msg
            JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
            JOIN " . PREFIX . "forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE ( MATCH(t.title) AGAINST('" . $search."') OR MATCH(msg.contents) AGAINST('" . $search."') )
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." " . $auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC" . $Sql->limit(0, FORUM_MAX_SEARCH_RESULTS);

		if ($where == 'contents')    // Contents
		return "SELECT ".
		$args['id_search']." AS `id_search`,
                MIN(msg.id) AS `id_content`,
                t.title AS `title`,
                MAX(MATCH(msg.contents) AGAINST('" . $search."')) * " . $weight . " AS `relevance`,
                " . $Sql->concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM " . PREFIX . "forum_msg msg
            JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
            JOIN " . PREFIX . "forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE MATCH(msg.contents) AGAINST('" . $search."')
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." " . $auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC" . $Sql->limit(0, FORUM_MAX_SEARCH_RESULTS);
		else                                         // Title only
		return "SELECT ".
		$args['id_search']." AS `id_search`,
                msg.id AS `id_content`,
                t.title AS `title`,
                MATCH(t.title) AGAINST('" . $search."') * " . $weight . " AS `relevance`,
                " . $Sql->concat("'" . PATH_TO_ROOT . "'", "'/forum/topic.php?id='", 't.id', "'#m'", 'msg.id')."  AS `link`
            FROM " . PREFIX . "forum_msg msg
            JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
            JOIN " . PREFIX . "forum_cats c ON c.level != 0 AND c.aprob = 1 AND c.id = t.idcat
            WHERE MATCH(t.title) AGAINST('" . $search."')
            ".($idcat != -1 ? " AND c.id_left BETWEEN '" . $CAT_FORUM[$idcat]['id_left'] . "' AND '" . $CAT_FORUM[$idcat]['id_right'] . "'" : '')." " . $auth_cats."
            GROUP BY t.id
            ORDER BY relevance DESC" . $Sql->limit(0, FORUM_MAX_SEARCH_RESULTS);
	}



	/**
	 * @desc Return the array containing the result's data list
	 * @param &string[][] $args The array containing the result's id list
	 * @return string[] The array containing the result's data list
	 */
	function compute_search_results(&$args)
	{
		global $CONFIG, $Sql;

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
            m.login AS login,
            m.user_avatar AS avatar,
            s.user_id AS connect,
            msg.contents AS contents
        FROM " . PREFIX . "forum_msg msg
        LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = msg.user_id AND s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "' AND s.user_id != -1
        LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = msg.user_id
        JOIN " . PREFIX . "forum_topics t ON t.id = msg.idtopic
        WHERE msg.id IN (".implode(',', $ids).")
        GROUP BY t.id";

		$request_results = $Sql->query_while ($request, __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($request_results))
		{
			$results_data[] = $row;
		}
		$Sql->query_close($request_results);

		return $results_data;
	}

	/**
	 *  @desc Return the string to print the result
	 *  @param &string[] $result_data the result's data
	 *  @return string[] The string to print the result of a search element
	 */
	function parse_search_result(&$result_data)
	{
		global $CONFIG, $LANG, $CONFIG_USER;

		load_module_lang('forum'); //Chargement de la langue du module.

		$tpl = new Template('forum/forum_generic_results.tpl');

		$tpl->assign_vars(Array(
            'L_ON' => $LANG['on'],
            'L_TOPIC' => $LANG['topic']
		));
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '+' . url_encode_rewrite($result_data['title']) : '';
		$tpl->assign_vars(array(
            'USER_ONLINE' => '<img src="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' . ((!empty($result_data['connect']) && $result_data['user_id'] !== -1) ? 'online' : 'offline') . '.png" alt="" class="valign_middle" />',
            'U_USER_PROFILE' => !empty($result_data['user_id']) ? PATH_TO_ROOT . '/member/member'.url('.php?id='.$result_data['user_id'],'-'.$result_data['user_id'].'.php') : '',
            'USER_PSEUDO' => !empty($result_data['login']) ? wordwrap_html($result_data['login'], 13) : $LANG['guest'],
            'U_TOPIC' => PATH_TO_ROOT . '/forum/topic' . url('.php?id=' . $result_data['topic_id'], '-' . $result_data['topic_id'] . $rewrited_title . '.php') . '#m' . $result_data['msg_id'],
            'TITLE' => ucfirst($result_data['title']),
            'DATE' => gmdate_format('d/m/y', $result_data['date']),
            'CONTENTS' => second_parse($result_data['contents']),
            'USER_AVATAR' => '<img src="' . ($CONFIG_USER['activ_avatar'] == '1' && !empty($result_data['avatar']) ? $result_data['avatar'] : PATH_TO_ROOT . '/templates/' . get_utheme() . '/images/' .  $CONFIG_USER['avatar_url']) . '" alt="" />'
            ));

            return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
	}

	function _feeds_add_category(&$cat_tree, &$category)
	{
		$child = new FeedsCat('forum', $category['this']['id'], $category['this']['name']);
		foreach ($category['children'] as $sub_category)
		{
			ForumInterface::_feeds_add_category($child, $sub_category);
		}
		$cat_tree->add_child($child);
	}

	function get_feeds_list()
	{
		global $LANG;
		$feed = array();
		require_once PATH_TO_ROOT . '/forum/forum.class.php';
		$forum = new Forum();
		$categories = $forum->get_cats_tree();

		import('content/syndication/feeds_list');
		import('content/syndication/feeds_cat');

		$cat_tree = new FeedsCat('forum', 0, $LANG['root']);
        
		ForumInterface::_feeds_add_category($cat_tree, $categories);

		$children = $cat_tree->get_children();
		$feeds = new FeedsList();
		if (count($children) > 0)
		{
			$feeds->add_feed($children[0], DEFAULT_FEED_NAME);
		}
		return $feeds;
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_FORUM, $CAT_FORUM, $User;

		$_idcat = $idcat;
		require_once(PATH_TO_ROOT . '/forum/forum_init_auth_cats.php');
		$idcat = $_idcat;   // Because <$idcat> is overwritten in /forum/forum_init_auth_cats.php

		$data = new FeedData();

		import('content/syndication/feed_data');
		import('util/date');
		import('util/url');

		$data->set_title($LANG['xml_forum_desc']);
		$data->set_date(new Date());
		$data->set_link(new Url('/syndication.php?m=forum&amp;cat=' . $_idcat));
		$data->set_host(HOST);
		$data->set_desc($LANG['xml_forum_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(READ_CAT_FORUM);

		$req_cats = (($idcat > 0) && isset($CAT_FORUM[$idcat])) ? " AND c.id_left >= '" . $CAT_FORUM[$idcat]['id_left'] . "' AND id_right <= '" . $CAT_FORUM[$idcat]['id_right'] . "' " : "";

		$req = "SELECT t.id, t.title, t.last_timestamp, t.last_msg_id, t.display_msg, t.nbr_msg AS t_nbr_msg, msg.id mid, msg.contents, c.auth
		FROM " . PREFIX . "forum_topics t
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.idcat
		LEFT JOIN " . PREFIX . "forum_msg msg ON msg.id = t.last_msg_id
		WHERE c.level != 0 AND c.aprob = 1 " . $req_cats . "
		ORDER BY t.last_timestamp DESC
		" . $Sql->limit(0, 2 * $CONFIG_FORUM['pagination_msg']);
		$result = $Sql->query_while ($req, __LINE__, __FILE__);
		// Generation of the feed's items
		while ($row = $Sql->fetch_assoc($result))
		{
			$item = new FeedItem();
				
			//Link
			$last_page = ceil($row['t_nbr_msg'] / $CONFIG_FORUM['pagination_msg']);
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';

			$link = new Url('/forum/topic' . url(
			        '.php?' . $last_page .  'id=' . $row['id'],
                    '-' . $row['id'] . $last_page_rewrite . '+' . url_encode_rewrite($row['title'])  . '.php'
                    ) . '#m' .  $row['last_msg_id']
                    );
            $item->set_title(
            	(($CONFIG_FORUM['activ_display_msg'] && !empty($row['display_msg'])) ?
            	html_entity_decode($CONFIG_FORUM['display_msg'], ENT_NOQUOTES) . ' ' : '') .
                ucfirst($row['title'])
            );
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(second_parse($row['contents']));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['last_timestamp']));
            $item->set_auth(unserialize($row['auth']));

            $data->add_item($item);
		}
		$Sql->query_close($result);

		return $data;
	}
}

?>
