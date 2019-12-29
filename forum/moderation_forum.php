<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 1.5 - 2006 08 08
 * @contributor Regis VIARRE <crowkait@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$action = retrieve(GET, 'action', '');
$id_get = (int)retrieve(GET, 'id', 0);
$new_status = retrieve(GET, 'new_status', '');
$get_del = retrieve(GET, 'del', '');

$Bread_crumb->add($config->get_forum_name(), 'index.php');
if ($action == 'alert')
	$Bread_crumb->add($LANG['alert_management'], url('moderation_forum.php?action=alert'));
elseif ($action == 'users')
	$Bread_crumb->add($LANG['warning_management'], url('moderation_forum.php?action=warning'));
$Bread_crumb->add($LANG['moderation_panel'], '../forum/moderation_forum.php');

define('TITLE', $LANG['moderation_panel']);
require_once('../kernel/header.php');

//Au moins modérateur sur une catégorie du forum, ou modérateur global.
$check_auth_by_group = false;

foreach (CategoriesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY) as $id_category => $cat)
{
	if (ForumAuthorizationsService::check_authorizations($id_category)->moderation())
	{
		$check_auth_by_group = true;
		break;
	}
}

if (!AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL) && $check_auth_by_group !== true) //Si il n'est pas modérateur (total ou partiel)
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$tpl = new FileTemplate('forum/forum_moderation_panel.tpl');

$vars_tpl = array(
	'C_TINYMCE_EDITOR'   => AppContext::get_current_user()->get_editor() == 'TinyMCE',
	'FORUM_NAME'         => $config->get_forum_name(),
	'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
	'L_USERS_WARNING'    => $LANG['warning_management'],
	'L_ALERT_MANAGEMENT' => $LANG['alert_management'],
);

//Redirection changement de catégorie.
$change_cat = retrieve(POST, 'change_cat', '');
if (!empty($change_cat))
{
	$new_cat = '';
	try {
		$new_cat = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($change_cat);
	} catch (CategoryNotFoundException $e) { }
	AppContext::get_response()->redirect('/forum/forum' . url('.php?id=' . $change_cat, '-' . $change_cat . ($new_cat && ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? '+' . $new_cat->get_rewrited_name() : '') . '.php', '&'));
}

if ($action == 'alert') //Gestion des alertes
{
	//Changement de statut ou suppression
	if ((!empty($id_get) && ($new_status == '0' || $new_status == '1')) || !empty($get_del))
	{
		//Instanciation de la class du forum.
		$Forumfct = new Forum();

		if (!empty($get_del))
		{
			$hist = false;
			$result = PersistenceContext::get_querier()->select("SELECT id
			FROM " . PREFIX . "forum_alerts");

			while ($row = $result->fetch())
			{
				if ($request->has_postparameter('a' . $row['id']) && $request->get_postvalue('a' . $row['id']) == 'on' && is_numeric($row['id']))
					$Forumfct->Del_alert_topic($row['id']);
			}
			$result->dispose();
		}
		else
		{
			if ($new_status == '0') //On le passe en non lu
				$Forumfct->Wait_alert_topic($id_get);
			elseif ($new_status == '1') //On le passe en résolu
				$Forumfct->Solve_alert_topic($id_get);
		}

		if (!empty($get_del))
			$get_id = '';
		else
			$get_id = '&id=' . $id_get;

		AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=alert' . $get_id, '', '&'));
	}

	$tpl->put_all(array(
		'L_MODERATION_PANEL'        => $LANG['moderation_panel'],
		'L_MODERATION_FORUM'        => $LANG['moderation_forum'],
		'L_FORUM'                   => $LANG['forum'],
		'L_LOGIN'                   => LangLoader::get_message('display_name', 'user-common'),
		'C_HOME'                    => false,
		'L_ALERT'                   => $LANG['alert_management'],
		'U_MODERATION_FORUM_ACTION' => 'moderation_forum.php'. url('?action=alert&amp;token=' . AppContext::get_session()->get_token()),
		'U_ACTION_ALERT'            => url('.php?action=alert&amp;del=1&amp;' . AppContext::get_session()->get_token())
	));

	if (empty($id_get)) //On liste les alertes
	{
		$tpl->put_all(array(
			'C_FORUM_ALERTS'    => true,
			'L_TITLE'           => $LANG['alert_title'],
			'L_TOPIC'           => $LANG['alert_concerned_topic'],
			'L_LOGIN'           => $LANG['alert_login'],
			'L_TIME'            => LangLoader::get_message('date', 'date-common'),
			'L_STATUS'          => $LANG['status'],
			'L_ALERT_SOLVED'    => $LANG['alert_solved'],
			'L_ALERT_NOTSOLVED' => $LANG['alert_not_solved'],
			'L_DELETE'          => LangLoader::get_message('delete', 'common'),
			'L_DELETE_MESSAGE'  => $LANG['delete_several_alerts']
		));

		//Vérification des autorisations.
		$authorized_categories = CategoriesService::get_authorized_categories();

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT
			ta.id, ta.title, ta.timestamp, ta.status, ta.user_id, ta.idtopic, ta.idmodo,
			m2.display_name AS login_modo, m2.level AS modo_level, m2.groups AS modo_groups,
			m.display_name, m.level AS user_level, m.groups,
			t.title AS topic_title,
			c.id AS cid
		FROM " . PREFIX . "forum_alerts ta
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = ta.idtopic
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = ta.user_id
		LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = ta.idmodo
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
		WHERE c.id IN :authorized_categories
		ORDER BY ta.status ASC, ta.timestamp DESC", array(
			'authorized_categories' => $authorized_categories
		));
		while ($row = $result->fetch())
		{
			$modo_group_color = ($row['status'] != 0) ? User::get_group_color($row['modo_groups'], $row['modo_level']) : '';
			$group_color = User::get_group_color($row['groups'], $row['user_level']);
			$time = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

			$tpl->assign_block_vars('alert_list', array_merge(
				Date::get_array_tpl_vars($time, 'TIME'), array(
				'TITLE'              => stripslashes($row['title']),
				'U_TITLE'            => 'moderation_forum' . url('.php?action=alert&amp;id=' . $row['id']),
				'TOPIC'              => $row['topic_title'],
				'U_TOPIC'            => 'topic' . url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . '+' . Url::encode_rewrite($row['topic_title']) . '.php'),
				'USER_ID'            => UserUrlBuilder::profile($row['user_id'])->rel(),
				'USER_CSSCLASS'      => UserService::get_level_class($row['user_level']),
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'USER_GROUP_COLOR'   => $group_color,
				'LOGIN_USER'         => $row['display_name'],
				'BACKGROUND_COLOR'   => $row['status'] == 1 ? 'bgc success' : 'bgc warning',
				'ID'                 => $row['id'],
				'C_STATUS'           => $row['status'] != 0,
				'U_IDMODO_REL'       => UserUrlBuilder::profile($row['idmodo'])->rel(),
				'MODO_CSSCLASS'      => UserService::get_level_class($row['modo_level']),
				'C_MODO_GROUP_COLOR' => !empty($modo_group_color),
				'MODO_GROUP_COLOR'   => $modo_group_color,
				'LOGIN_MODO'         => $row['login_modo']
			)));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$tpl->put_all(array(
				'C_FORUM_NO_ALERT' => true,
				'L_NO_ALERT'       => LangLoader::get_message('no_item_now', 'common'),
			));
		}
	}
	else //On affiche les informations sur une alerte
	{
		//Vérification des autorisations.
		$authorized_categories = CategoriesService::get_authorized_categories();

		$result = PersistenceContext::get_querier()->select("
		SELECT ta.id, ta.title, ta.timestamp, ta.status, ta.user_id, ta.idtopic, ta.idmodo, m2.display_name AS login_modo, m2.level AS modo_level, m2.groups AS modo_groups, m.display_name, m.level AS user_level, m.groups, t.title AS topic_title, t.id_category, c.id AS cid, ta.contents
		FROM " . PREFIX . "forum_alerts ta
		LEFT JOIN " . PREFIX . "forum_topics t ON t.id = ta.idtopic
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = ta.user_id
		LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = ta.idmodo
		LEFT JOIN " . PREFIX . "forum_cats c ON c.id = t.id_category
		WHERE ta.id = :id AND c.id IN :authorized_categories", array(
			'id' => $id_get,
			'authorized_categories' => $authorized_categories
		));
		$row = $result->fetch();
		$result->dispose();
		if (!empty($row))
		{
			$category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($row['id_category']);
			//Le sujet n'existe plus, on vire l'alerte.
			if (empty($row['id_category']))
			{
				//Instanciation de la class du forum.
				$Forumfct = new Forum();

				$Forumfct->Del_alert_topic($id_get);
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=alert', '', '&'));
			}

			$modo_group_color = ($row['status'] != 0) ? User::get_group_color($row['modo_groups'], $row['modo_level']) : '';
			$group_color = User::get_group_color($row['groups'], $row['user_level']);
			$time = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

			$tpl->put_all(array_merge(
				Date::get_array_tpl_vars($time, 'TIME'), array(
				'ID'                 => $id_get,
				'TITLE'              => stripslashes($row['title']),
				'TOPIC'              => $row['topic_title'],
				'U_TOPIC'            => 'topic' . url('.php?id=' . $row['idtopic'], '-' . $row['idtopic'] . '+' . Url::encode_rewrite($row['topic_title']) . '.php'),
				'CONTENTS'           => FormatingHelper::second_parse($row['contents']),
				'C_STATUS'           => $row['status'] != 0,
				'L_ALERT_SOLVED'     => $LANG['alert_solved'],
				'L_ALERT_NOTSOLVED'  => $LANG['alert_not_solved'],
				'U_IDMODO_REL'       => UserUrlBuilder::profile($row['idmodo'])->rel(),
				'MODO_CSSCLASS'      => UserService::get_level_class($row['modo_level']),
				'C_MODO_GROUP_COLOR' => !empty($modo_group_color),
				'MODO_GROUP_COLOR'   => $modo_group_color,
				'LOGIN_MODO'         => $row['login_modo'],
				'USER_ID'            => UserUrlBuilder::profile($row['user_id'])->rel(),
				'USER_CSSCLASS'      => UserService::get_level_class($row['user_level']),
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'USER_GROUP_COLOR'   => $group_color,
				'LOGIN_USER'         => $row['display_name'],

				'CAT_NAME'           => $category->get_name(),
				'U_CAT'              => 'forum' . url('.php?id=' . $row['id_category'], '-' . $row['id_category'] . '+' . $category->get_rewrited_name() . '.php'),
				'C_FORUM_ALERT_LIST' => true,
				'U_CHANGE_STATUS'    => ($row['status'] == '0') ? 'moderation_forum.php' . url('?action=alert&amp;id=' . $id_get . '&amp;new_status=1&amp;token=' . AppContext::get_session()->get_token()) : 'moderation_forum.php' . url('?action=alert&amp;id=' . $id_get . '&amp;new_status=0&amp;token=' . AppContext::get_session()->get_token()),
				'L_CHANGE_STATUS'    => ($row['status'] == '0') ? $LANG['change_status_to_1'] : $LANG['change_status_to_0'],
				'L_TITLE'            => $LANG['alert_title'],
				'L_TOPIC'            => $LANG['alert_concerned_topic'],
				'L_CONTENTS'         => $LANG['alert_msg'],
				'L_LOGIN'            => $LANG['alert_login'],
				'L_TIME'             => LangLoader::get_message('date', 'date-common'),
				'L_STATUS'           => $LANG['status'],
				'L_STATUS_1'         => $LANG['change_status_to_1'],
				'L_CAT'              => $LANG['alert_concerned_cat']
			)));
		}
		else //Groupe, modérateur partiel qui n'a pas accès à cette alerte car elle ne concerne pas son forum
		{
			$tpl->put_all(array(
				'C_FORUM_ALERT_NOT_AUTH' => true,
				'L_NO_ALERT'             => $LANG['alert_not_auth']
			));
		}
	}
}
elseif ($action == 'punish') //Gestion des utilisateurs
{
	$readonly = (int)retrieve(POST, 'new_info', 0);
	$readonly = $readonly > 0 ? (time() + $readonly) : 0;
	$readonly_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if (!empty($id_get) && retrieve(POST, 'valid_user', false)) //On met à  jour le niveau d'avertissement
	{
		try {
			$info_mbr = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_id', 'level', 'email'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		//Modérateur ne peux avertir l'admin (logique non?).
		if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)))
		{
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('delay_readonly' => $readonly), ' WHERE user_id = :user_id', array('user_id' => $info_mbr['user_id']));

			//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
			if ($info_mbr['user_id'] != AppContext::get_current_user()->get_id())
			{
				if (!empty($readonly_contents) && !empty($readonly))
				{
					//Envoi du message.
					PrivateMsg::start_conversation($info_mbr['user_id'], addslashes($LANG['read_only_title']), nl2br(str_replace('%date', Date::to_format($readonly, Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE), $readonly_contents)), '-1', PrivateMsg::SYSTEM_PM);
				}
			}

			//Insertion de l'action dans l'historique.
			forum_history_collector(H_READONLY_USER, $info_mbr['user_id'], 'moderation_forum.php?action=punish&id=' . $info_mbr['user_id']);
		}

		AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=punish', '', '&'));
	}

	$tpl->put_all(array(
		'L_FORUM'                   => $LANG['forum'],
		'L_LOGIN'                   => LangLoader::get_message('display_name', 'user-common'),
		'L_MODERATION_PANEL'        => $LANG['moderation_panel'],
		'L_MODERATION_FORUM'        => $LANG['moderation_forum'],
		'C_HOME'                    => false,
		'L_ALERT'                   => $LANG['punishment_management'],
		'U_XMLHTTPREQUEST'          => 'punish_moderation_panel',
		'U_MODERATION_FORUM_ACTION' => 'moderation_forum.php' . url('?action=punish&amp;token=' . AppContext::get_session()->get_token()),
		'U_ACTION'                  => url('.php?action=punish&amp;token=' . AppContext::get_session()->get_token())
	));

	if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
	{
		if (retrieve(POST, 'search_member', false))
		{
			$login = retrieve(POST, 'login_mbr', '');
			$user_id = 0;
			try {
				$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name LIKE :login', array('login' => '%' . $login .'%'));
			} catch (RowNotFoundException $e) {}

			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=punish&id=' . $user_id, '', '&'));
			else
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=punish', '', '&'));
		}

		$tpl->put_all(array(
			'C_FORUM_USER_LIST' => true,
			'L_PM'              => $LANG['user_contact_pm'],
			'L_INFO'            => $LANG['user_punish_until'],
			'L_PM'              => $LANG['user_contact_pm'],
			'L_ACTION_USER'     => $LANG['punishment_management'],
			'L_PROFILE'         => LangLoader::get_message('profile', 'user-common'),
			'L_SEARCH_USER'     => $LANG['search_member'],
			'L_SEARCH'          => $LANG['search'],
			'L_REQUIRE_LOGIN'   => $LANG['require_pseudo']
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, groups, delay_readonly
		FROM " . PREFIX . "member
		WHERE delay_readonly > :timestamp_now
		ORDER BY delay_readonly", array(
			'timestamp_now' => time()
		));
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);
			$info = new Date($row['delay_readonly'], Timezone::SERVER_TIMEZONE);

			$tpl->assign_block_vars('user_list', array_merge(
				Date::get_array_tpl_vars($info, 'INFO'), array(
				'LOGIN'         => $row['display_name'],
				'LEVEL_CLASS'   => UserService::get_level_class($row['level']),
				'C_GROUP_COLOR' => !empty($group_color),
				'GROUP_COLOR'   => $group_color,
				'U_PROFILE'     => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_ACTION_USER' => 'moderation_forum.php' . url('?action=punish&amp;id=' . $row['user_id'] . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_PM'          => url('.php?pm='. $row['user_id'], '-' . $row['user_id'] . '.php'),
			)));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$tpl->put_all( array(
				'C_FORUM_NO_USER' => true,
				'L_NO_USER'       => $LANG['no_punish'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		try {
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'groups', 'delay_readonly'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		//Durée de la sanction.
		$date_lang = LangLoader::get('date-common');
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
		$array_sanction = array(LangLoader::get_message('no', 'common'), '1 ' . $date_lang['minute'], '5 ' . $date_lang['minutes'], '15 ' . $date_lang['minutes'], '30 ' . $date_lang['minutes'], '1 ' . $date_lang['hour'], '2 ' . $date_lang['hours'], '1 ' . $date_lang['day'], '2 ' . $date_lang['days'], '1 ' . $date_lang['week'], '2 ' . $date_lang['weeks'], '1 ' . $date_lang['month'], '2 ' . $date_lang['month'], $LANG['life']);

		$diff = ($member['delay_readonly'] - time());
		$key_sanction = 0;
		if ($diff > 0)
		{
			//Retourne la sanction la plus proche correspondant au temp de bannissement.
			for ($i = 11; $i >= 0; $i--)
			{
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if (($diff - $array_time[$i]) > $avg)
				{
					$key_sanction = $i + 1;
					break;
				}
			}
		}

		//On crée le formulaire select
		$select = '';
		foreach ($array_time as $key => $time)
		{
			$selected = ( $key_sanction == $key ) ? 'selected="selected"' : '' ;
			$select .= '<option value="' . $time . '" ' . $selected . '>' . TextHelper::strtolower($array_sanction[$key]) . '</option>';
		}

		array_pop($array_sanction);

		$editor = AppContext::get_content_formatting_service()->get_default_editor();
		$editor->set_identifier('action_contents');

		$group_color = User::get_group_color($member['groups'], $member['level']);

		$tpl->put_all(array(
			'C_FORUM_USER_INFO' => true,
			'KERNEL_EDITOR'     => $editor->display(),
			'ALTERNATIVE_PM'    => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $LANG['user_readonly_changed']) : str_replace('%date%', '1 ' . LangLoader::get_message('minute', 'date-common'), $LANG['user_readonly_changed']),
			'USER_ID'            => UserUrlBuilder::profile($id_get)->rel(),
			'USER_CSSCLASS'      => UserService::get_level_class($member['level']),
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'USER_GROUP_COLOR'   => $group_color,
			'LOGIN_USER'         => $member['display_name'],
			'INFO'              => $array_sanction[$key_sanction],
			'SELECT' => $select,
			'REPLACE_VALUE' => 'replace_value = parseInt(replace_value);'. "\n" .
			'if (replace_value != \'326592000\'){'. "\n" .
			'array_time = new Array(' . (implode(', ', $array_time)) . ');' . "\n" .
			'array_sanction = new Array(\'' . implode('\', \'', array_map('addslashes', $array_sanction)) . '\');'. "\n" .
			'var i;
			for (i = 0; i <= 11; i++)
			{
				if (array_time[i] == replace_value)
				{
					replace_value = array_sanction[i];
					break;
				}
			}' . "\n" .
			'if (replace_value != \'' . addslashes(LangLoader::get_message('no', 'common')) . '\')' . "\n" .
			'{' . "\n" .
				'contents = contents.replace(regex, replace_value);' . "\n" .
				'document.getElementById(\'action_contents\').disabled = \'\'' . "\n" .
			'} else' . "\n" .
			'	document.getElementById(\'action_contents\').disabled = \'disabled\';' . "\n" .
			'document.getElementById(\'action_info\').innerHTML = replace_value;}',
			'REGEX'            => '/[0-9]+ [a-zA-Z]+/',
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN'   => $LANG['user_readonly_explain'],
			'L_PM'             => $LANG['user_contact_pm'],
			'L_LOGIN'          => LangLoader::get_message('display_name', 'user-common'),
			'L_PM'             => $LANG['user_contact_pm'],
			'L_CHANGE_INFO'    => $LANG['submit'],
			'U_PM'             => UserUrlBuilder::personnal_message($id_get)->rel(),
			'U_ACTION_INFO'    => url('.php?action=punish&amp;id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token())
		));
	}
}
elseif ($action == 'warning') //Gestion des utilisateurs
{
	$new_warning_level = (int)retrieve(POST, 'new_info', 0);
	$warning_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if ($new_warning_level >= 0 && $new_warning_level <= 100 && !empty($id_get) && (bool)retrieve(POST, 'valid_user', false)) //On met à  jour le niveau d'avertissement
	{
		try {
			$info_mbr = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_id', 'level', 'email'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		//Modérateur ne peux avertir l'admin (logique non?).
		if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)))
		{
			if ($new_warning_level < 100) //Ne peux pas mettre des avertissements supérieurs à 100.
			{
				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('warning_percentage' => $new_warning_level), ' WHERE user_id = :user_id', array('user_id' => $info_mbr['user_id']));

				//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
				if ($info_mbr['user_id'] != AppContext::get_current_user()->get_id())
				{
					if (!empty($warning_contents))
					{
						//Envoi du message.
						PrivateMsg::start_conversation($info_mbr['user_id'], addslashes($LANG['warning_title']), nl2br($warning_contents), '-1', PrivateMsg::SYSTEM_PM);
					}
				}

				//Insertion de l'action dans l'historique.
				forum_history_collector(H_SET_WARNING_USER, $info_mbr['user_id'], 'moderation_forum.php?action=warning&id=' . $info_mbr['user_id']);
			}
			elseif ($new_warning_level == 100) //Ban => on supprime sa session et on le banni (pas besoin d'envoyer de pm :p).
			{
				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('warning_percentage' => 100), ' WHERE user_id = :user_id', array('user_id' => $info_mbr['user_id']));
				PersistenceContext::get_querier()->delete(DB_TABLE_SESSIONS, 'WHERE user_id=:id', array('id' => $info_mbr['user_id']));

				//Insertion de l'action dans l'historique.
				forum_history_collector(H_BAN_USER, $info_mbr['user_id'], 'moderation_forum.php?action=warning&id=' . $info_mbr['user_id']);

				//Envoi du mail

				AppContext::get_mail_service()->send_from_properties($info_mbr['email'], addslashes($LANG['ban_title_mail']), sprintf(addslashes($LANG['ban_mail']), HOST, addslashes(MailServiceConfig::load()->get_mail_signature())));
			}
		}

		AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=warning', '', '&'));
	}

	$tpl->put_all(array(
		'L_FORUM'                   => $LANG['forum'],
		'L_LOGIN'                   => LangLoader::get_message('display_name', 'user-common'),
		'L_MODERATION_PANEL'        => $LANG['moderation_panel'],
		'L_MODERATION_FORUM'        => $LANG['moderation_forum'],
		'C_HOME'                    => false,
		'L_ALERT'                   => $LANG['warning_management'],
		'U_XMLHTTPREQUEST'          => 'warning_moderation_panel',
		'U_MODERATION_FORUM_ACTION' => 'moderation_forum.php' . url('?action=warning&amp;token=' . AppContext::get_session()->get_token()),
		'U_ACTION'                  => url('.php?action=warning&amp;token=' . AppContext::get_session()->get_token())
	));

	if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
	{
		if (retrieve(POST, 'search_member', false))
		{
			$login = retrieve(POST, 'login_member', '');
			$user_id = 0;
			try {
				$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name LIKE :login', array('login' => '%' . $login .'%'));
			} catch (RowNotFoundException $e) {}

			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=warning&id=' . $user_id, '', '&'));
			else
				AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php?action=warning', '', '&'));
		}

		$tpl->put_all(array(
			'C_FORUM_USER_LIST' => true,
			'L_PM'              => $LANG['user_contact_pm'],
			'L_INFO'            => $LANG['user_warning_level'],
			'L_PM'              => $LANG['user_contact_pm'],
			'L_ACTION_USER'     => $LANG['change_user_warning'],
			'L_SEARCH_USER'     => $LANG['search_member'],
			'L_SEARCH'          => $LANG['search'],
			'L_REQUIRE_LOGIN'   => $LANG['require_pseudo']
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, groups, warning_percentage
		FROM " . PREFIX . "member
		WHERE warning_percentage > 0
		ORDER BY warning_percentage");
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);

			$tpl->assign_block_vars('user_list', array(
				'C_GROUP_COLOR' => !empty($group_color),
				'LOGIN'         => $row['display_name'],
				'LEVEL_CLASS'   => UserService::get_level_class($row['level']),
				'GROUP_COLOR'   => $group_color,
				'INFO'          => $row['warning_percentage'] . '%',
				'U_ACTION_USER' => 'moderation_forum.php' . url('?action=warning&amp;id=' . $row['user_id'] . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_PROFILE'     => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_PM'          => UserUrlBuilder::personnal_message($row['user_id'])->rel()
			));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$tpl->put_all( array(
				'C_FORUM_NO_USER' => true,
				'L_NO_USER'       => $LANG['no_user_warning'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		try {
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'groups', 'delay_readonly', 'warning_percentage'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$select = '';
		$j = 0;
		for ($j = 0; $j <= 10; $j++) //On crée le formulaire select
		{
			if ((10 * $j) == $member['warning_percentage'])
				$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
			else
				$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
		}

		$editor = AppContext::get_content_formatting_service()->get_default_editor();
		$editor->set_identifier('action_contents');

		$group_color = User::get_group_color($member['groups'], $member['level']);

		$tpl->put_all(array(
			'C_FORUM_USER_INFO' => true,
			'KERNEL_EDITOR'     => $editor->display(),
			'ALTERNATIVE_PM'    => str_replace('%level%', $member['warning_percentage'], $LANG['user_warning_level_changed']),
			'USER_ID'            => UserUrlBuilder::profile($id_get)->rel(),
			'USER_CSSCLASS'      => UserService::get_level_class($member['level']),
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'USER_GROUP_COLOR'   => $group_color,
			'LOGIN_USER'         => $member['display_name'],
			'INFO'              => $LANG['user_warning_level'] . ': ' . $member['warning_percentage'] . '%',
			'SELECT'            => $select,
			'REPLACE_VALUE'     => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($LANG['user_warning_level']) . ': \' + replace_value + \'%\';',
			'REGEX'             => '/ [0-9]+%/',
			'U_ACTION_INFO'     => url('.php?action=warning&amp;id=' . $id_get . '&amp;token=' . AppContext::get_session()->get_token()),
			'U_PM'              => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'L_ALTERNATIVE_PM'  => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN'    => $LANG['user_warning_explain'],
			'L_PM'              => $LANG['user_contact_pm'],
			'L_INFO'            => $LANG['user_warning_level'],
			'L_PM'              => $LANG['user_contact_pm'],
			'L_CHANGE_INFO'     => $LANG['change_user_warning']
		));
	}
}
elseif (retrieve(GET, 'del_h', false) && AppContext::get_current_user()->check_level(User::ADMIN_LEVEL)) //Suppression de l'historique.
{
	PersistenceContext::get_dbms_utils()->truncate(PREFIX . 'forum_history');

	AppContext::get_response()->redirect('/forum/moderation_forum' . url('.php', '', '&'));
}
else //Panneau de modération
{
	$get_more = (int)retrieve(GET, 'more', 0);

	$tpl->put_all(array(
		'C_FORUM_MODO_MAIN' => true,
		'C_HOME'            => true,
		'U_ACTION_HISTORY'  => url('.php?del_h=1&amp;token=' . AppContext::get_session()->get_token()),
		'U_MORE_ACTION'     => !empty($get_more) ? url('.php?more=' . ($get_more + 100)) : url('.php?more=100')
	));

	//Bouton de suppression de l'historique, visible uniquement pour l'admin.
	if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
	{
		$tpl->put_all(array(
			'C_FORUM_ADMIN' => true
		));
	}

	$tpl->put_all(array(
		'L_DEL_HISTORY'      => $LANG['alert_history'],
		'L_MODERATION_PANEL' => $LANG['moderation_panel'],
		'L_MODERATION_FORUM' => $LANG['moderation_forum'],
		'L_FORUM'            => $LANG['forum'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_ALERT_MANAGEMENT' => $LANG['alert_management'],
		'L_USERS_MANAGEMENT' => $LANG['warning_management'],
		'L_HISTORY'          => $LANG['history'],
		'L_MODO'             => $LANG['modo'],
		'L_ACTION'           => $LANG['action'],
		'L_USER_CONCERN'     => $LANG['history_member_concern'],
		'L_DATE'             => LangLoader::get_message('date', 'date-common'),
		'L_DELETE'           => LangLoader::get_message('delete', 'common'),
		'L_MORE_ACTION'      => $LANG['more_action']
	));

	$end = !empty($get_more) ? $get_more : 15; //Limit.
	$i = 0;

	$result = PersistenceContext::get_querier()->select("SELECT h.action, h.user_id, h.user_id_action, h.url, h.timestamp, m.display_name, m.level AS user_level, m.groups, m2.display_name as member, m2.level as member_level, m2.groups as member_groups
	FROM " . PREFIX . "forum_history h
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = h.user_id
	LEFT JOIN " . DB_TABLE_MEMBER . " m2 ON m2.user_id = h.user_id_action
	ORDER BY h.timestamp DESC
	LIMIT :limit", array(
		'limit' => $end
	));
	while ($row = $result->fetch())
	{
		$group_color = User::get_group_color($row['groups'], $row['user_level']);
		$member_group_color = User::get_group_color($row['member_groups'], $row['member_level']);
		$date = new Date($row['timestamp'], Timezone::SERVER_TIMEZONE);

		$tpl->assign_block_vars('action_list', array_merge(
			Date::get_array_tpl_vars($date, 'DATE'), array(
			'C_GROUP_COLOR'              => !empty($group_color),
			'LOGIN'                      => !empty($row['display_name']) ? $row['display_name'] : $LANG['guest'],
			'LEVEL_CLASS'                => UserService::get_level_class($row['user_level']),
			'GROUP_COLOR'                => $group_color,
			'C_ACTION'                   => !empty($row['url']),
			'L_ACTION'                   => $LANG[$row['action']],
			'U_ACTION'                   => PATH_TO_ROOT . '/forum/' . $row['url'],
			'U_USER_PROFILE'             => UserUrlBuilder::profile($row['user_id'])->rel(),
			'C_USER_CONCERN'             => !empty($row['user_id_action']),
			'U_USER_CONCERN'             => UserUrlBuilder::profile($row['user_id_action'])->rel(),
			'USER_CONCERN_CSSCLASS'      => UserService::get_level_class($row['member_level']),
			'C_USER_CONCERN_GROUP_COLOR' => !empty($member_group_color),
			'USER_CONCERN_GROUP_COLOR'   => $member_group_color,
			'USER_LOGIN'                 => $row['member']
		)));

		$i++;
	}
	$result->dispose();

	$tpl->put_all(array(
		'C_DISPLAY_LINK_MORE_ACTION' => $i == $end,
		'C_FORUM_NO_ACTION'          => $i == 0,
		'L_NO_ACTION'                => $LANG['no_action']
	));
}

//Listes les utilisateurs en ligne.
list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script LIKE '%" ."/forum/moderation_forum.php%'");

//Liste des catégories.
$search_category_children_options = new SearchCategoryChildrensOptions();
$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
$categories_tree = CategoriesService::get_categories_manager()->get_select_categories_form_field('cats', '', Category::ROOT_CATEGORY, $search_category_children_options);
$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
$method->setAccessible(true);
$categories_tree_options = $method->invoke($categories_tree);
$cat_list = '';
foreach ($categories_tree_options as $option)
{
	if ($option->get_raw_value())
	{
		$cat = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($option->get_raw_value());
		if (!$cat->get_url())
			$cat_list .= $option->display()->render();
	}
}

$vars_tpl = array_merge($vars_tpl, array(
	'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
	'TOTAL_ONLINE'     => $total_online,
	'C_NO_USER_ONLINE' => (($total_online - $total_visit) == 0),
	'USERS_ONLINE'     => $users_list,
	'ADMIN'            => $total_admin,
	'MODO'             => $total_modo,
	'MEMBER'           => $total_member,
	'GUEST'            => $total_visit,
	'SELECT_CAT'       => $cat_list, //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.
	'L_USER'           => ($total_online > 1) ? $LANG['user_s'] : $LANG['user'],
	'L_ADMIN'          => ($total_admin > 1) ? $LANG['admin_s'] : $LANG['admin'],
	'L_MODO'           => ($total_modo > 1) ? $LANG['modo_s'] : $LANG['modo'],
	'L_MEMBER'         => ($total_member > 1) ? $LANG['member_s'] : $LANG['member'],
	'L_GUEST'          => ($total_visit > 1) ? $LANG['guest_s'] : $LANG['guest'],
	'L_AND'            => $LANG['and'],
	'L_ONLINE'         => TextHelper::strtolower($LANG['online']),
	'L_FORUM_INDEX'    => $LANG['forum_index'],
	'U_ONCHANGE'       => url(".php?id=' + this.options[this.selectedIndex].value + '", "forum-' + this.options[this.selectedIndex].value + '.php"),
	'U_ONCHANGE_CAT'   => url("index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),
));

$tpl->put_all($vars_tpl);
$tpl_top->put_all($vars_tpl);
$tpl_bottom->put_all($vars_tpl);

$tpl->put('forum_top', $tpl_top);
$tpl->put('forum_bottom', $tpl_bottom);

$tpl->display();

include('../kernel/footer.php');

?>
