<?php
/*##################################################
 *                                forum.php
 *                            -------------------
 *   begin                : October 26, 2005
 *   copyright            : (C) 2005 Viarre Régis / Sautel Benoît
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
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

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$id_get = retrieve(GET, 'id', 0);

//Vérification de l'existance de la catégorie.
if (empty($id_get) || !isset($CAT_FORUM[$id_get]) || $CAT_FORUM[$id_get]['aprob'] == 0 || $CAT_FORUM[$id_get]['level'] == 0)
{
	$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), 
        $LANG['e_unexist_cat_forum']);
    DispatchManager::redirect($controller);
}
	
//Vérification des autorisations d'accès.
if (!AppContext::get_current_user()->check_auth($CAT_FORUM[$id_get]['auth'], READ_CAT_FORUM))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

if (!empty($CAT_FORUM[$id_get]['url']))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

//Récupération de la barre d'arborescence.
$Bread_crumb->add($config->get_forum_name(), 'index.php');
foreach ($CAT_FORUM as $idcat => $array_info_cat)
{
	if ($CAT_FORUM[$id_get]['id_left'] > $array_info_cat['id_left'] && $CAT_FORUM[$id_get]['id_right'] < $array_info_cat['id_right'] && $array_info_cat['level'] < $CAT_FORUM[$id_get]['level'])
		$Bread_crumb->add($array_info_cat['name'], ($array_info_cat['level'] == 0) ? url('index.php?id=' . $idcat, 'cat-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php') : 'forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . Url::encode_rewrite($array_info_cat['name']) . '.php'));
}
if (!empty($CAT_FORUM[$id_get]['name'])) //Nom de la catégorie courante.
	$Bread_crumb->add($CAT_FORUM[$id_get]['name'], $CAT_FORUM[$id_get]['url']);
if (!empty($id_get))
	define('TITLE', $CAT_FORUM[$id_get]['name']);
else
	define('TITLE', $LANG['title_forum']);	
require_once('../kernel/header.php'); 

//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
$rewrited_title = (ServerEnvironmentConfig::load()->is_url_rewriting_enabled() && !empty($CAT_FORUM[$id_get]['name'])) ? '+' . Url::encode_rewrite($CAT_FORUM[$id_get]['name']) : '';

//Redirection changement de catégorie.
$change_cat = retrieve(POST, 'change_cat', '');
if (!empty($change_cat))
	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $change_cat, '-' . $change_cat . $rewrited_title . '.php', '&'));
	
if (!empty($id_get))
{
	$tpl = new FileTemplate('forum/forum_forum.tpl');

	//Invité?	
	$is_guest = (AppContext::get_current_user()->get_id() !== -1) ? false : true;
	
	//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
	$max_time_msg = forum_limit_time_msg();
	
	//Affichage des sous forums s'il y en a.
	if (($CAT_FORUM[$id_get]['id_right'] - $CAT_FORUM[$id_get]['id_left']) > 1) //Intervalle > 1 => sous forum présent.
	{
		$tpl->put_all(array(
			'C_FORUM_SUB_CATS' => true
		));
		
		//Vérification des autorisations.
		$unauth_cats = array();
		if (is_array($AUTH_READ_FORUM))
		{
			foreach ($AUTH_READ_FORUM as $idcat => $auth)
			{
				if ($auth === false)
					$unauth_cats[] = $idcat;
			}
		}
		
		//On liste les sous-catégories.
		$result = PersistenceContext::get_querier()->select("SELECT c.id AS cid, c.name, c.subname, c.url, c.nbr_topic, c.nbr_msg, c.status, t.id AS tid, 
		t.idcat, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, m.user_id, m.display_name, m.level AS user_level, m.groups, v.last_view_id 
		FROM " . PREFIX . "forum_cats c
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = c.last_topic_id
		LEFT JOIN " . PREFIX . "forum_view v ON v.user_id = :user_id AND v.idtopic = t.id
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = t.last_user_id
		WHERE c.aprob = 1 AND c.id_left > :id_left AND c.id_right < :id_right AND c.level = :level + 1  " . (!empty($unauth_cats) ? " AND c.id NOT IN :unauth_cats" : '') . "
		ORDER BY c.id_left ASC", array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'id_left' => $CAT_FORUM[$id_get]['id_left'],
			'id_right' => $CAT_FORUM[$id_get]['id_right'],
			'level' => $CAT_FORUM[$id_get]['level'],
			'unauth_cats' => $unauth_cats,
		));
		while ($row = $result->fetch())
		{
			if ($row['nbr_msg'] !== '0')
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
				
				$group_color = User::get_group_color($row['groups'], $row['user_level']);
				
				$last = '<a href="topic' . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '+' . Url::encode_rewrite($row['title'])  . '.php') . '" class="small">' . ucfirst($last_topic_title) . '</a><br />
				<a href="topic' . url('.php?' . $last_page .  'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '+' . Url::encode_rewrite($row['title'])  . '.php') . '#m' .  $last_msg_id . '" title=""><i class="fa fa-hand-o-right"></i></a> ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br />
				' . $LANG['by'] . (!empty($row['login']) ? ' <a href="'. UserUrlBuilder::profile($row['last_user_id'])->rel() .'" class="small '.UserService::get_level_class($row['user_level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['login'], 13) . '</a>' : ' ' . $LANG['guest']);
			}
			else
			{
				$row['last_timestamp'] = '';
				$last = '<br />' . $LANG['no_message'] . '<br /><br />';
			}

			//Vérirication de l'existance de sous forums.
			$subforums = '';
			if ($CAT_FORUM[$row['cid']]['id_right'] - $CAT_FORUM[$row['cid']]['id_left'] > 1)
			{		
				foreach ($CAT_FORUM as $idcat => $key) //Listage des sous forums.
				{
					if ($CAT_FORUM[$idcat]['id_left'] > $CAT_FORUM[$row['cid']]['id_left'] && $CAT_FORUM[$idcat]['id_right'] < $CAT_FORUM[$row['cid']]['id_right'])
					{
						if ($CAT_FORUM[$idcat]['level'] == ($CAT_FORUM[$row['cid']]['level'] + 1))
						{
							if ($AUTH_READ_FORUM[$row['cid']]) //Autorisation en lecture.
							{
								$link = !empty($CAT_FORUM[$idcat]['url']) ? '<a href="' . $CAT_FORUM[$idcat]['url'] . '" class="small">' : '<a href="forum' . url('.php?id=' . $idcat, '-' . $idcat . '+' . Url::encode_rewrite($CAT_FORUM[$idcat]['name']) . '.php') . '" class="small">';
								$subforums .= !empty($subforums) ? ', ' . $link . $CAT_FORUM[$idcat]['name'] . '</a>' : $link . $CAT_FORUM[$idcat]['name'] . '</a>';		
							}
						}	
					}
				}	
				$subforums = '<strong>' . $LANG['subforum_s'] . '</strong>: ' . $subforums;
			}
			
			//Vérifications des topics Lu/non Lus.
			$img_announce = 'fa-announce';
			$blink = false;
			if (!$is_guest)
			{
				if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
				{
					$img_announce =  $img_announce . '-new'; //Image affiché aux visiteurs.
					$blink = true;
				}
			}
			$img_announce .= ($row['status'] == '0') ? '-lock' : '';
			
			$tpl->assign_block_vars('subcats', array(
				'C_BLINK' => $blink,
				'IMG_ANNOUNCE' => $img_announce,
				'NAME' => $row['name'],
				'DESC' => $row['subname'],
				'SUBFORUMS' => !empty($subforums) && !empty($row['subname']) ? '<br />' . $subforums : $subforums,
				'NBR_TOPIC' => $row['nbr_topic'],
				'NBR_MSG' => $row['nbr_msg'],
				'U_FORUM_URL' => $row['url'],
				'U_FORUM_VARS' => url('.php?id=' . $row['cid'], '-' . $row['cid'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
				'U_LAST_TOPIC' => $last
			));
		}
		$result->dispose();
	}
		
	//On vérifie si l'utilisateur a les droits d'écritures.
	$check_group_write_auth = AppContext::get_current_user()->check_auth($CAT_FORUM[$id_get]['auth'], WRITE_CAT_FORUM);
	$locked_cat = ($CAT_FORUM[$id_get]['status'] == 1 || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)) ? false : true;
	if (!$check_group_write_auth)
	{
		$tpl->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $LANG['e_cat_write']
		));
	}
	//Catégorie verrouillée?
	elseif ($locked_cat)
	{
		$tpl->assign_block_vars('error_auth_write', array(
			'L_ERROR_AUTH_WRITE' => $LANG['e_cat_lock_forum']
		));
	}
	
	$nbr_topic = PersistenceContext::get_querier()->count(PREFIX . 'forum_topics', 'WHERE idcat=:idcat', array('idcat' => $id_get));
	
	//On crée une pagination (si activé) si le nombre de forum est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_topic, $config->get_number_topics_per_page(), Pagination::LIGHT_PAGINATION);
	$pagination->set_url(new Url('/forum/forum.php?id=' . $id_get . '&amp;p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	//Affichage de l'arborescence des catégories.
	$i = 0;
	$forum_cats = '';
	foreach ($Bread_crumb->get_links() as $key => $array)
	{
		if ($i == 2)
			$forum_cats .= '<a href="' . $array[1] . '">' . $array[0] . '</a>';
		elseif ($i > 2)
			$forum_cats .= ' &raquo; <a href="' . $array[1] . '">' . $array[0] . '</a>';
		$i++;
	}
	
	//Si l'utilisateur a les droits d'édition.	
	$check_group_edit_auth = AppContext::get_current_user()->check_auth($CAT_FORUM[$id_get]['auth'], EDIT_CAT_FORUM);

	$vars_tpl = array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'FORUM_NAME' => $config->get_forum_name(),
		'PAGINATION' => $pagination->display(),
		'IDCAT' => $id_get,
		//'C_MASS_MODO_CHECK' => $check_group_edit_auth ? true : false,
		'C_MASS_MODO_CHECK' => false,
		'C_POST_NEW_SUBJECT' => ($check_group_write_auth && !$locked_cat),
		'U_MSG_SET_VIEW' => '<a class="small" href="../forum/action' . url('.php?read=1&amp;f=' . $id_get, '') . '" title="' . $LANG['mark_as_read'] . '" onclick="javascript:return Confirm_read_topics();">' . $LANG['mark_as_read'] . '</a>',
		'U_CHANGE_CAT'=> 'forum' . url('.php?id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token(), '-' . $id_get . $rewrited_title . '.php?token=' . AppContext::get_session()->get_token()),
		'U_ONCHANGE' => url(".php?id=' + this.options[this.selectedIndex].value + '", "-' + this.options[this.selectedIndex].value + '.php"),
		'U_ONCHANGE_CAT' => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),		
		'U_FORUM_CAT' => $forum_cats,
		'U_POST_NEW_SUBJECT' => 'post' . url('.php?new=topic&amp;id=' . $id_get, ''),
		'L_FORUM_INDEX' => $LANG['forum_index'],
		'L_SUBFORUMS' => $LANG['sub_forums'],
		'L_DISPLAY_UNREAD_MSG' => $LANG['show_not_reads'],
		'L_FORUM' => $LANG['forum'],
		'L_AUTHOR' => $LANG['author'],
		'L_TOPIC' => $LANG['topic_s'],
		'L_ANSWERS' => $LANG['replies'],
		'L_MESSAGE' => $LANG['message_s'],
		'L_POLL' => $LANG['poll'],
		'L_VIEW' => $LANG['views'],
		'L_LAST_MESSAGE' => $LANG['last_messages'],
		'L_POST_NEW_SUBJECT' => $LANG['post_new_subject'],
		'L_FOR_SELECTION' => $LANG['for_selection'],
		'L_CHANGE_STATUT_TO' => sprintf($LANG['change_status_to'], $config->get_message_before_topic_title()),
		'L_CHANGE_STATUT_TO_DEFAULT' => $LANG['change_status_to_default'],
		'L_MOVE_TO' => $LANG['move_to'],
		'L_DELETE' => $LANG['delete'],
		'L_LOCK' => $LANG['forum_lock'],
		'L_UNLOCK' => $LANG['forum_unlock'],
		'L_GO' => $LANG['go']
	);

	$nbr_topics_display = 0;
	$result = PersistenceContext::get_querier()->select("SELECT m1.display_name AS login, m1.level AS user_level, m1.groups AS user_groups, m2.display_name AS last_login, m2.level AS last_user_level, m2.groups AS last_user_groups, t.id, t.title, t.subtitle, t.user_id, t.nbr_msg, t.nbr_views, t.last_user_id , t.last_msg_id, t.last_timestamp, t.type, t.status, t.display_msg, v.last_view_id, p.question, tr.id AS idtrack
	FROM " . PREFIX . "forum_topics t
	LEFT JOIN " . PREFIX . "forum_view v ON v.user_id = :user_id AND v.idtopic = t.id
	LEFT JOIN " . DB_TABLE_MEMBER . " m1 ON m1.user_id = t.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = t.last_user_id
	LEFT JOIN " . PREFIX . "forum_poll p ON p.idtopic = t.id
	LEFT JOIN " . PREFIX . "forum_track tr ON tr.idtopic = t.id AND tr.user_id = :user_id
	WHERE t.idcat = :idcat
	ORDER BY t.type DESC , t.last_timestamp DESC
	LIMIT :number_items_per_page OFFSET :display_from", array(
		'user_id' => AppContext::get_current_user()->get_id(),
		'idcat' => $id_get,
		'number_items_per_page' => $pagination->get_number_items_per_page(),
		'display_from' => $pagination->get_display_from()
	));
	while ($row = $result->fetch())
	{
		//On définit un array pour l'appellation correspondant au type de champ
		$type = array('2' => $LANG['forum_announce'] . ':', '1' => $LANG['forum_postit'] . ':', '0' => '');
		
		//Vérifications des topics Lu/non Lus.
		$img_announce = 'fa-announce';
		$new_msg = false;
		$blink = false;
		if (!$is_guest) //Non visible aux invités.
		{
			if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
			{
				$img_announce =  $img_announce . '-new'; //Image affiché aux visiteurs.
				$new_msg = true;
				$blink = true;
			}
		}
		$img_announce .= ($row['type'] == '1') ? '-post' : '';
		$img_announce .= ($row['type'] == '2') ? '-top' : '';
		$img_announce .= ($row['status'] == '0' && $row['type'] == '0') ? '-lock' : '';

		//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
		//Puis calcul de la page du last_msg_id ou du last_view_id.
		if (!empty($row['last_view_id'])) 
		{
			$last_msg_id = $row['last_view_id']; 
			$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
			$last_page_rewrite = '-0-' . $row['last_view_id'];
		}
		else
		{
			$last_msg_id = $row['last_msg_id']; 
			$last_page = ceil( $row['nbr_msg'] / $config->get_number_messages_per_page() );
			$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
			$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
		}
		
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title_topic = ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . Url::encode_rewrite($row['title']) : '';
		
		//Affichage du dernier message posté.
		$last_group_color = User::get_group_color($row['last_user_groups'], $row['last_user_level']);
		$last_msg = '<a href="topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title_topic . '.php') . '#m' . $last_msg_id . '" title=""><i class="fa fa-hand-o-right"></i></a>' . ' ' . $LANG['on'] . ' ' . gmdate_format('date_format', $row['last_timestamp']) . '<br /> ' . $LANG['by'] . ' ' . (!empty($row['last_login']) ? '<a class="small '.UserService::get_level_class($row['last_user_level']).'"' . (!empty($last_group_color) ? ' style="color:' . $last_group_color . '"' : '') . ' href="'. UserUrlBuilder::profile($row['last_user_id'])->rel() .'">' . TextHelper::wordwrap_html($row['last_login'], 13) . '</a>' : '<em>' . $LANG['guest'] . '</em>');
		
		//Ancre ajoutée aux messages non lus.	
		$new_ancre = ($new_msg === true && !$is_guest) ? '<a href="topic' . url('.php?' . $last_page . 'id=' . $row['id'], '-' . $row['id'] . $last_page_rewrite . $rewrited_title_topic . '.php') . '#m' . $last_msg_id . '" title=""><i class="fa fa-hand-o-right"></i></a>' : '';
		
		//On crée une pagination (si activé) si le nombre de topics est trop important.
		$page = AppContext::get_request()->get_getint('pt', 1);
		$topic_pagination = new ModulePagination($page, $row['nbr_msg'], $config->get_number_messages_per_page(), Pagination::LIGHT_PAGINATION);
		$topic_pagination->set_url(new Url('/forum/topic.php?id=' . $row['id'] . '&amp;pt=%d'));
		
		$group_color = User::get_group_color($row['user_groups'], $row['user_level']);
		
		$tpl->assign_block_vars('topics', array(
			'C_PAGINATION' => $topic_pagination->has_several_pages(),
			'C_IMG_POLL' => !empty($row['question']),
			'C_IMG_TRACK' => !empty($row['idtrack']),
			'C_DISPLAY_MSG' => ($config->is_message_before_topic_title_displayed() && $config->is_message_before_topic_title_icon_displayed() && $row['display_msg']),
			'C_HOT_TOPIC' => ($row['type'] == '0' && $row['status'] != '0' && ($row['nbr_msg'] > $config->get_number_messages_per_page())),
			'C_BLINK' => $blink,
			'IMG_ANNOUNCE' => $img_announce,
			'ANCRE' => $new_ancre,
			'TYPE' => $type[$row['type']],
			'TITLE' => ucfirst($row['title']),
			'AUTHOR' => !empty($row['login']) ? '<a href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'" class="small '.UserService::get_level_class($row['user_level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $row['login'] . '</a>' : '<em>' . $LANG['guest'] . '</em>',
			'DESC' => $row['subtitle'],
			'PAGINATION' => $topic_pagination->display(),
			'MSG' => ($row['nbr_msg'] - 1),
			'VUS' => $row['nbr_views'],
			'L_DISPLAY_MSG' => ($config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $config->get_message_before_topic_title() : '', 
			'U_TOPIC_VARS' => url('.php?id=' . $row['id'], '-' . $row['id'] . $rewrited_title_topic . '.php'),
			'U_LAST_MSG' => $last_msg
		));
		$nbr_topics_display++;
	}
	$result->dispose();
		
	//Affichage message aucun topics.
	if ($nbr_topics_display == 0)
	{
		$tpl->put_all(array(
			'C_NO_TOPICS' => true,
			'L_NO_TOPICS' => $LANG['no_topics']
		));
	}

	//Listes les utilisateurs en lignes.
	list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" . url('/forum/forum.php?id=' . $id_get, '/forum/forum-' . $id_get) . "%'");

	$vars_tpl = array_merge($vars_tpl, array(
		'TOTAL_ONLINE' => $total_online,
		'USERS_ONLINE' => (($total_online - $total_visit) == 0) ? '<em>' . $LANG['no_member_online'] . '</em>' : $users_list,
		'ADMIN' => $total_admin,
		'MODO' => $total_modo,
		'MEMBER' => $total_member,
		'GUEST' => $total_visit,
		'SELECT_CAT' => forum_list_cat($id_get, $CAT_FORUM[$id_get]['level']), //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
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
		
	$tpl->display();
}
else
	AppContext::get_response()->redirect('/forum/index.php');

include('../kernel/footer.php');
?>