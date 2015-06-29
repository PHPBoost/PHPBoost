<?php
/*##################################################
 *                     ForumHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 07, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class ForumHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $category;
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $LANG;
		
		load_module_lang('forum');
		
		return $LANG['title_forum'];
	}
	
	private function get_view()
	{
		global $LANG, $config, $nbr_msg_not_read, $sid, $id_get;
		
		require_once(PATH_TO_ROOT . '/forum/forum_begin.php');
		require_once(PATH_TO_ROOT . '/forum/forum_tools.php');

		$tpl = new FileTemplate('forum/forum_index.tpl');
		$tpl_top = new FileTemplate('forum/forum_top.tpl');
		$tpl_bottom = new FileTemplate('forum/forum_bottom.tpl');
		
		if ($config->is_connexion_form_displayed())
		{
			$display_connexion = array(	
				'C_FORUM_CONNEXION' => true,
				'L_CONNECT' => LangLoader::get_message('connect', 'user-common'),
				'L_DISCONNECT' => LangLoader::get_message('disconnect', 'user-common'),
				'L_AUTOCONNECT' => LangLoader::get_message('autoconnect', 'user-common'),
				'L_REGISTER' => LangLoader::get_message('register', 'user-common')
			);
			
			$tpl_top->put_all($display_connexion);
			$tpl_bottom->put_all($display_connexion);
		}
		
		$vars_tpl = array(
			'C_DISPLAY_UNREAD_DETAILS' => (AppContext::get_current_user()->get_id() !== -1),
			'C_MODERATION_PANEL' => AppContext::get_current_user()->check_level(1),
			'U_TOPIC_TRACK' => '<a class="small" href="'. PATH_TO_ROOT .'/forum/track.php' . $sid . '" title="' . $LANG['show_topic_track'] . '">' . $LANG['show_topic_track'] . '</a>',
			'U_LAST_MSG_READ' => '<a class="small" href="'. PATH_TO_ROOT .'/forum/lastread.php' . $sid . '" title="' . $LANG['show_last_read'] . '">' . $LANG['show_last_read'] . '</a>',
			'U_MSG_NOT_READ' => '<a class="small" href="'. PATH_TO_ROOT .'/forum/unread.php' . $sid  . '" title="' . $LANG['show_not_reads'] . '">' . $LANG['show_not_reads'] . (AppContext::get_current_user()->get_id() !== -1 ? ' (' . $nbr_msg_not_read . ')' : '') . '</a>',
			'U_MSG_SET_VIEW' => '<a class="small" href="'. PATH_TO_ROOT .'/forum/action' . url('.php?read=1', '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
			'L_MODERATION_PANEL' => $LANG['moderation_panel'],
			'L_CONFIRM_READ_TOPICS' => $LANG['confirm_mark_as_read'],
			'L_AUTH_ERROR' => LangLoader::get_message('error.auth', 'status-messages-common')
		);
		
		//Affichage des sous-catégories de la catégorie.
		$display_cat = !empty($id_get);
		
		//Vérification des autorisations.
		$authorized_categories = ForumService::get_authorized_categories($id_get);
		
		//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
		$max_time_msg = forum_limit_time_msg();

		$is_guest = AppContext::get_current_user()->get_id() == -1;
		$total_topic = 0;
		$total_msg = 0;
		$i = 0;

		//On liste les catégories et sous-catégories.
		$result = PersistenceContext::get_querier()->select('SELECT @id_cat:= c.id, c.id AS cid, c.id_parent, c.name, c.rewrited_name, c.description as subname, c.url, c.last_topic_id, t.id AS tid, t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, t.status, m.user_id, m.display_name as login, m.level as user_level, m.groups, v.last_view_id,
		(SELECT COUNT(*) FROM ' . ForumSetup::$forum_topics_table . '
			WHERE idcat IN (
				@id_cat,
				(SELECT GROUP_CONCAT(id SEPARATOR \',\') FROM ' . ForumSetup::$forum_cats_table . ' WHERE id_parent = @id_cat), 
				(SELECT GROUP_CONCAT(childs.id SEPARATOR \',\') FROM ' . ForumSetup::$forum_cats_table . ' parents
				INNER JOIN ' . ForumSetup::$forum_cats_table . ' childs ON parents.id = childs.id_parent
				WHERE parents.id_parent = @id_cat)
			)
		) AS nbr_topic,
		(SELECT COUNT(*) FROM ' . ForumSetup::$forum_message_table . '
			WHERE idtopic IN (
				(SELECT GROUP_CONCAT(id SEPARATOR \',\') FROM ' . ForumSetup::$forum_topics_table . ' WHERE idcat = @id_cat), 
				(SELECT GROUP_CONCAT(t.id SEPARATOR \',\') FROM ' . ForumSetup::$forum_topics_table . ' t LEFT JOIN ' . ForumSetup::$forum_cats_table . ' c ON t.idcat = c.id WHERE id_parent = @id_cat)
			)
		) AS nbr_msg
		FROM ' . ForumSetup::$forum_cats_table . ' c
		LEFT JOIN ' . ForumSetup::$forum_topics_table . ' t ON t.id = c.last_topic_id
		LEFT JOIN ' . ForumSetup::$forum_view_table . ' v ON v.user_id = :user_id AND v.idtopic = t.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = t.last_user_id
		WHERE c.id IN :authorized_categories
		ORDER BY c.id_parent, c.c_order', array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories
		));
		
		$categories = array();
		while ($row = $result->fetch())
		{
			$categories[] = $row;
		}
		$result->dispose();
		$sorted_categories = parentChildSort('cid', 'id_parent', $categories);
		
		$display_sub_cats = false;
		$is_sub_forum = array();
		foreach ($sorted_categories as $row)
		{
			$tpl->assign_block_vars('forums_list', array());
			if ($row['id_parent'] == Category::ROOT_CATEGORY && $i > 0 && $display_sub_cats) //Fermeture de la catégorie racine.
			{
				$tpl->assign_block_vars('forums_list.endcats', array(
				));
			}
			$i++;

			if ($row['id_parent'] == Category::ROOT_CATEGORY) //Si c'est une catégorie
			{
				$tpl->assign_block_vars('forums_list.cats', array(
					'IDCAT' => $row['cid'],
					'NAME' => $row['name'],
					'U_FORUM_VARS' => PATH_TO_ROOT . '/forum/' . url('index.php?id=' . $row['cid'], 'cat-' . $row['cid'] . '+' . $row['rewrited_name'] . '.php')
				));
				$display_sub_cats = true;
			}
			else //On liste les sous-catégories
			{
				if (in_array($row['id_parent'], $is_sub_forum))
					$is_sub_forum[] = $row['cid'];
				
				if (($display_sub_cats || !empty($id_get)) && !in_array($row['cid'], $is_sub_forum))
				{
					if ($display_cat) //Affichage des forums d'une catégorie, ajout de la catégorie.
					{
						$tpl->assign_block_vars('forums_list.cats', array(
							'IDCAT' => $id_get,
							'NAME' => $row['name'],
							'U_FORUM_VARS' => PATH_TO_ROOT . '/forum/' . url('index.php?id=' . $id_get, 'cat-' . $id_get . '+' . $row['rewrited_name'] . '.php')
						));
						$display_cat = false;
					}
	
					$subforums = '';
					$tpl->put_all(array(
						'C_FORUM_ROOT_CAT' => false,
						'C_FORUM_CHILD_CAT' => true,
						'C_END_S_CATS' => false
					));
					
					$children = ForumService::get_categories_manager()->get_categories_cache()->get_childrens($row['cid']);
					if ($children)
					{
						foreach ($children as $id => $child) //Listage des sous forums.
						{
							if ($child->get_id_parent() == $row['cid'] && ForumAuthorizationsService::check_authorizations($child->get_id())->read()) //Sous forum distant d'un niveau au plus.
							{
								$is_sub_forum[] = $child->get_id();
								$link = $child->get_url() ? '<a href="' . $child->get_url() . '" class="small">' : '<a href="forum' . url('.php?id=' . $child->get_id(), '-' . $child->get_id() . '+' . $child->get_rewrited_name() . '.php') . '" class="small">';
								$subforums .= !empty($subforums) ? ', ' . $link . $child->get_name() . '</a>' : $link . $child->get_name() . '</a>';
							}
						}
						$subforums = '<strong>' . $LANG['subforum_s'] . '</strong>: ' . $subforums;
					}
	
					if (!empty($row['last_topic_id']))
					{
						//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
						if (!empty($row['last_view_id'])) //Calcul de la page du last_view_id réalisé dans topic.php
						{
							$last_msg_id = $row['last_view_id'];
							$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
							$last_page_rewrite = '-0-' . $row['last_view_id'];
						}
						else
						{
							$last_msg_id = $row['last_msg_id'];
							$last_page = ceil($row['t_nbr_msg'] / $config->get_number_messages_per_page());
							$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
							$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
						}
	
						$last_topic_title = (($config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $config->get_message_before_topic_title() : '') . ' ' . ucfirst($row['title']);
						$last_topic_title = (strlen(TextHelper::html_entity_decode($last_topic_title)) > 20) ? TextHelper::substr_html($last_topic_title, 0, 20) . '...' : $last_topic_title;
						$row['login'] = !empty($row['login']) ? $row['login'] : $LANG['guest'];
						$group_color = User::get_group_color($row['groups'], $row['user_level']);
						
						$last = '<a href="'. PATH_TO_ROOT . '/forum/topic' . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . Url::encode_rewrite($row['title'])  . '.php') . '" class="small">' . $last_topic_title . '</a><br />
						<a href="'. PATH_TO_ROOT . '/forum/topic' . url('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '"><i class="fa fa-hand-o-right"></i></a> ' . $LANG['on'] . ' ' . Date::to_format($row['last_timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) . '<br />' . $LANG['by'] . ' ' . ($row['last_user_id'] != '-1' ? '<a href="'. UserUrlBuilder::profile($row['last_user_id'])->rel() . '" class="small '.UserService::get_level_class($row['user_level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>');
					}
					else
					{
						$row['last_timestamp'] = '';
						$last = '<br />' . $LANG['no_message'] . '<br /><br />';
					}
	
					//Vérifications des topics Lu/non Lus.
					$img_announce = 'fa-announce';
					$blink = false;
					if (!$is_guest)
					{
						if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
						{
							$img_announce = $img_announce . '-new'; //Image affiché aux visiteurs.
							$blink = true;
						}
					}
					$img_announce .= ($row['status'] == '0') ? '-lock' : '';
	
					$total_topic += $row['nbr_topic'];
					$total_msg += $row['nbr_msg'];
					
	
					$tpl->assign_block_vars('forums_list.subcats', array(
						'C_BLINK' => $blink,
						'IMG_ANNOUNCE' => $img_announce,
						'NAME' => $row['name'],
						'DESC' => FormatingHelper::second_parse($row['subname']),
						'SUBFORUMS' => !empty($subforums) && !empty($row['subname']) ? '<br />' . $subforums : $subforums,
						'NBR_TOPIC' => $row['nbr_topic'],
						'NBR_MSG' => $row['nbr_msg'],
						'U_FORUM_URL' => $row['url'],
						'U_FORUM_VARS' => PATH_TO_ROOT .'/forum/' . url('forum.php?id=' . $row['cid'], 'forum-' . $row['cid'] . '+' . $row['rewrited_name'] . '.php'),
						'U_LAST_TOPIC' => $last
					));
				}
			}
		}
		
		if ($i > 0) //Fermeture de la catégorie racine.
		{
			$tpl->assign_block_vars('forums_list', array(
			));
			$tpl->assign_block_vars('forums_list.endcats', array(
			));
		}
		
		$site_path = GeneralConfig::get_default_site_path();
		if (GeneralConfig::load()->get_module_home_page() == 'forum')
		{
			list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script = '". $site_path ."/forum/' OR s.location_script = '". $site_path ."/forum/index.php' OR s.location_script = '". $site_path ."/index.php' OR s.location_script = '". $site_path ."/'");
		}
		else
		{
			$where = "AND s.location_script LIKE '%". $site_path ."/forum/%'";
			if (!empty($id_get))
			{
				$where = "AND s.location_script LIKE '%". $site_path . url('/forum/index.php?id=' . $id_get, '/forum/cat-' . $id_get . '+' . $category->get_rewrite_name() . '.php') ."'";
			}
			list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online($where);
		}
		
		//Liste des catégories.
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories_tree = ForumService::get_categories_manager()->get_select_categories_form_field('cats', '', $id_get, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$cat_list = '';
		$number_options = $categories_tree_options;
		foreach ($categories_tree_options as $option)
		{
			if ($option->get_raw_value())
			{
				$cat = ForumService::get_categories_manager()->get_categories_cache()->get_category($option->get_raw_value());
				if (!$cat->get_url() && $number_options)
					$cat_list .= $option->display()->render();
			}
		}
		
		$vars_tpl = array_merge($vars_tpl, array(
			'FORUM_NAME' => $config->get_forum_name(),
			'NBR_MSG' => $total_msg,
			'NBR_TOPIC' => $total_topic,
			'TOTAL_ONLINE' => $total_online,
			'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
			'ADMIN' => $total_admin,
			'MODO' => $total_modo,
			'MEMBER' => $total_member,
			'GUEST' => $total_visit,
			'SELECT_CAT' => !empty($id_get) ? $cat_list : '', //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
			'C_TOTAL_POST' => true,
			'U_ONCHANGE' => PATH_TO_ROOT ."/forum/" . url("index.php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),
			'U_ONCHANGE_CAT' => PATH_TO_ROOT ."/forum/" . url("/index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
			'L_FORUM_INDEX' => $LANG['forum_index'],
			'L_FORUM' => $LANG['forum'],
			'L_TOPIC' => ($total_topic > 1) ? $LANG['topic_s'] : $LANG['topic'],
			'L_MESSAGE' => ($total_msg > 1) ? $LANG['message_s'] : $LANG['message'],
			'L_LAST_MESSAGE' => $LANG['last_message'],
			'L_STATS' => $LANG['stats'],
			'L_DISPLAY_UNREAD_MSG' => $LANG['show_not_reads'],
			'L_MARK_AS_READ' => $LANG['mark_as_read'],
			'L_TOTAL_POST' => $LANG['nbr_message'],
			'L_DISTRIBUTED' => strtolower($LANG['distributed']),
			'L_AND' => $LANG['and'],
			'L_USER' => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
			'L_ADMIN' => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
			'L_MODO' => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
			'L_MEMBER' => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
			'L_GUEST' => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
			'L_AND' => $LANG['and'],
			'L_ONLINE' => strtolower($LANG['online'])
		));
		
		$tpl->put_all($vars_tpl);
		$tpl_top->put_all($vars_tpl);
		$tpl_bottom->put_all($vars_tpl);
		
		$tpl->put('forum_top', $tpl_top);
		$tpl->put('forum_bottom', $tpl_bottom);

		return $tpl;
	}
}
?>