<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 05 05
 * @since       PHPBoost 1.6 - 2007 03 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

require_once('../kernel/begin.php');

$Bread_crumb->add($LANG['user_s'], UserUrlBuilder::home()->rel());
$Bread_crumb->add($LANG['moderation_panel'], UserUrlBuilder::moderation_panel()->rel());

$action = retrieve(GET, 'action', 'warning', TSTRING_UNCHANGE);
$id_get = (int)retrieve(GET, 'id', 0);
$valid_user = (bool)retrieve(POST, 'valid_user', false);
$search_member = (bool)retrieve(POST, 'search_member', false);

switch ($action)
{
	case 'ban':
		$Bread_crumb->add($LANG['bans'], UserUrlBuilder::moderation_panel('ban')->rel());
		break;
	case 'punish':
		$Bread_crumb->add($LANG['punishment'], UserUrlBuilder::moderation_panel('punish')->rel());
		break;
	case 'warning':
	default:
		$Bread_crumb->add($LANG['warning'], UserUrlBuilder::moderation_panel('warning')->rel());
}

define('TITLE', $LANG['moderation_panel']);
require_once('../kernel/header.php');

if (!AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)) //Si il n'est pas modérateur
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$moderation_panel_template = new FileTemplate('user/moderation_panel.tpl');

$moderation_panel_template->put_all(array(
	'C_TINYMCE_EDITOR' => AppContext::get_current_user()->get_editor() == 'TinyMCE',
	'L_MODERATION_PANEL' => $LANG['moderation_panel'],
	'L_PUNISHMENT' => $LANG['punishment'],
	'L_WARNING' => $LANG['warning'],
	'L_BAN' => $LANG['bans'],
	'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
	'L_USERS_WARNING' => $LANG['warning_management'],
	'L_USERS_BAN' => $LANG['ban_management'],
	'U_WARNING' => UserUrlBuilder::moderation_panel('warning')->rel(),
	'U_PUNISH' => UserUrlBuilder::moderation_panel('punish')->rel(),
	'U_BAN' => UserUrlBuilder::moderation_panel('ban')->rel()
));

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('action_contents');

if ($action == 'punish')
{
	//Gestion des utilisateurs
	$readonly = (int)retrieve(POST, 'new_info', 0);
	$readonly = $readonly > 0 ? (time() + $readonly) : 0;
	$readonly_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if (!empty($id_get) && $valid_user) //On met à  jour le niveau d'avertissement
	{
		if ($id_get != AppContext::get_current_user()->get_id())
		{
			if (!empty($readonly_contents))
			{
				MemberSanctionManager::remove_write_permissions($id_get, $readonly, MemberSanctionManager::SEND_MP, str_replace('%date', Date::to_format($readonly, Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE), $readonly_contents));
			}
		}
		else
		{
			MemberSanctionManager::remove_write_permissions($id_get, $readonly, MemberSanctionManager::NO_SEND_CONFIRMATION, str_replace('%date', Date::to_format($readonly, Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE), $readonly_contents));
		}
		SessionData::recheck_cached_data_from_user_id($id_get);

		AppContext::get_response()->redirect(HOST . DIR . url('/user/moderation_panel.php?action=punish', '', '&'));
	}

	$moderation_panel_template->put_all(array(
		'C_MODO_PANEL_USER' => true,
		'L_ACTION_INFO' => $LANG['punishment_management'],
		'L_LOGIN' => LangLoader::get_message('display_name', 'user-common'),
		'L_INFO_MANAGEMENT' => $LANG['punishment_management'],
		'U_XMLHTTPREQUEST' => 'punish_user',
		'U_ACTION' => UserUrlBuilder::moderation_panel('punish')->rel()
	));

	if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
	{
		if ($search_member)
		{
			$login = retrieve(POST, 'login_mbr', '');

			$user_id = 0;
			try {
				$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name LIKE :name', array('name' => '%' . $login . '%'));
			} catch (RowNotFoundException $ex) {}

			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('punish', $user_id));
			else
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('punish'));
		}

		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_punish_until'],
			'L_ACTION_USER' => $LANG['punishment_management'],
			'L_PROFILE' => LangLoader::get_message('profile', 'user-common'),
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, groups, delay_readonly
		FROM " . PREFIX . "member
		WHERE delay_readonly > :now
		ORDER BY delay_readonly DESC", array(
			'now' => time()
		));
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);

			$moderation_panel_template->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'LOGIN' => $row['display_name'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO' => Date::to_format($row['delay_readonly'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_ACTION_USER' => '<a href="'. UserUrlBuilder::moderation_panel('punish', $row['user_id'])->rel() .'" class="fa fa-lock"></a>',
				'U_PM' => UserUrlBuilder::personnal_message($row['user_id'])->rel(),
			));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$moderation_panel_template->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_punish'],
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

		//On crée le formulaire select
		$select = '';
		//Durée de la sanction.
		$date_lang = LangLoader::get('date-common');
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
		$array_sanction = array(LangLoader::get_message('no', 'common'), '1 ' . $date_lang['minute'], '5 ' . $date_lang['minutes'], '15 ' . $date_lang['minutes'], '30 ' . $date_lang['minutes'], '1 ' . $date_lang['hour'], '2 ' . $date_lang['hours'], '1 ' . $date_lang['day'], '2 ' . $date_lang['days'], '1 ' . $date_lang['week'], '2 ' . $date_lang['weeks'], '1 ' . $date_lang['month'], '10 ' . TextHelper::strtolower($date_lang['years']));

		$diff = ($member['delay_readonly'] - time());
		$key_sanction = 0;
		if ($diff > 0)
		{
			//Retourne la sanction la plus proche correspondant au temp de bannissement.
			for ($i = 11; $i > 0; $i--)
			{
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if (($diff - $array_time[$i]) > $avg)
				{
					$key_sanction = $i + 1;
					break;
				}
			}
		}
		//Affichge des sanctions
		foreach ($array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$select .= '<option value="' . $time . '" ' . $selected . '>' . TextHelper::strtolower($array_sanction[$key]) . '</option>';
		}

		$group_color = User::get_group_color($member['groups'], $member['level']);
		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_INFO' => true,
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'LOGIN' => $member['display_name'],
			'USER_LEVEL_CLASS' => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR' => $group_color,
			'KERNEL_EDITOR' => $editor->display(),
			'ALTERNATIVE_PM' => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $LANG['user_readonly_changed']) : str_replace('%date%', '1 ' . LangLoader::get_message('minute', 'date-common'), $LANG['user_readonly_changed']),
			'INFO' => $array_sanction[$key_sanction],
			'SELECT' => $select,
			'REPLACE_VALUE' => 'replace_value = parseInt(replace_value);'. "\n" .
			'array_time = new Array(' . (implode(', ', $array_time)) . ');' . "\n" .
			'array_sanction = new Array(\'' . implode('\', \'', array_map('addslashes', $array_sanction)) . '\');'. "\n" .
			'var i;
			for (i = 0; i <= 12; i++)
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
			'document.getElementById(\'action_info\').innerHTML = replace_value;',
			'REGEX'=> '/[0-9]+ [a-zéèêA-Z]+/u',
			'U_PM' => url('.php?pm='. $id_get, '-' . $id_get . '.php'),
			'U_ACTION_INFO' => UserUrlBuilder::moderation_panel('punish', $id_get)->rel() . '&amp;token=' . AppContext::get_session()->get_token(),
			'U_PROFILE' => UserUrlBuilder::profile($id_get)->rel(),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_readonly_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => LangLoader::get_message('display_name', 'user-common'),
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['submit']
		));
	}
}
else if ($action == 'warning')
{
	$new_warning_level = (int)retrieve(POST, 'new_info', 0);
	$warning_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if ($new_warning_level >= 0 && $new_warning_level <= 100 && AppContext::get_request()->has_postparameter('new_info') && !empty($id_get) && $valid_user) //On met à  jour le niveau d'avertissement
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
			if ($new_warning_level <= 100) //Ne peux pas mettre des avertissements supérieurs à 100.
			{
				//Envoi d'un MP au membre pour lui signaler, si le membre en question n'est pas lui-même.
				if ($id_get != AppContext::get_current_user()->get_id())
				{
					MemberSanctionManager::caution($id_get, $new_warning_level, MemberSanctionManager::SEND_MP, $warning_contents);
				}
				else
				{
					MemberSanctionManager::caution($id_get, $new_warning_level, MemberSanctionManager::NO_SEND_CONFIRMATION, $warning_contents);
				}
				SessionData::recheck_cached_data_from_user_id($id_get);
			}
		}

		AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('warning'));
	}

	$moderation_panel_template->put_all(array(
		'C_MODO_PANEL_USER' => true,
		'L_ACTION_INFO' => $LANG['warning_management'],
		'L_LOGIN' => LangLoader::get_message('display_name', 'user-common'),
		'L_INFO_MANAGEMENT' => $LANG['warning_management'],
		'U_XMLHTTPREQUEST' => 'warning_user',
		'U_ACTION' => UserUrlBuilder::moderation_panel('warning')->rel() . '&amp;' . AppContext::get_session()->get_token()
	));

	if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
	{
		if ($search_member)
		{
			$login = retrieve(POST, 'login_mbr', '');

			$user_id = 0;
			try {
				$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name LIKE :name', array('name' => '%' . $login . '%'));
			} catch (RowNotFoundException $ex) {}

			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('warning', $user_id));
			else
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('warning'));
		}

		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_ACTION_USER' => $LANG['warning_management'],
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, groups, warning_percentage
		FROM " . PREFIX . "member
		WHERE warning_percentage > 0
		ORDER BY warning_percentage");
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);

			$moderation_panel_template->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'LOGIN' => $row['display_name'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO' => $row['warning_percentage'] . '%',
				'U_ACTION_USER' => '<a href="'. UserUrlBuilder::moderation_panel('warning', $row['user_id'])->rel() .'" class="fa fa-exclamation-triangle"></a>',
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_PM' => UserUrlBuilder::personnal_message($row['user_id'])->rel()
			));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$moderation_panel_template->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_user_warning'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		try {
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'groups', 'warning_percentage'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		//On crée le formulaire select
		$select = '';
		$j = 0;
		for ($j = 0; $j <=10; $j++)
		{
			if (10 * $j == $member['warning_percentage'])
				$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
			else
				$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
		}

		$group_color = User::get_group_color($member['groups'], $member['level']);

		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_INFO' => true,
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'LOGIN' => $member['display_name'],
			'USER_LEVEL_CLASS' => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR' => $group_color,
			'KERNEL_EDITOR' => $editor->display(),
			'ALTERNATIVE_PM' => str_replace('%level%', $member['warning_percentage'], $LANG['user_warning_level_changed']),
			'INFO' => $LANG['user_warning_level'] . ': ' . $member['warning_percentage'] . '%',
			'SELECT' => $select,
			'REPLACE_VALUE' => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($LANG['user_warning_level']) . ': \' + replace_value + \'%\';',
			'REGEX'=> '/ [0-9]+%/u',
			'U_ACTION_INFO' => UserUrlBuilder::moderation_panel('warning', $id_get)->rel() . '&amp;token=' . AppContext::get_session()->get_token(),
			'U_PM' => UserUrlBuilder::personnal_message($id_get)->rel(),
			'U_PROFILE' => UserUrlBuilder::profile($id_get)->rel(),
			'L_ALTERNATIVE_PM' => $LANG['user_alternative_pm'],
			'L_INFO_EXPLAIN' => $LANG['user_warning_explain'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_warning_level'],
			'L_PM' => $LANG['user_contact_pm'],
			'L_CHANGE_INFO' => $LANG['change_user_warning']
		));
	}
}
else
{
	$user_ban = retrieve(POST, 'user_ban', '', TSTRING_UNCHANGE);
	$user_ban = $user_ban > 0 ? (time() + $user_ban) : 0;
	if ($valid_user && !empty($id_get)) //On banni le membre
	{
		try {
			$info_mbr = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_id', 'display_name', 'warning_percentage', 'email'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		MemberSanctionManager::banish($id_get, $user_ban, MemberSanctionManager::SEND_MAIL);

		if ($user_ban == 0 && $info_mbr['warning_percentage'] == 100)
		{
			MemberSanctionManager::remove_write_permissions($id_get, 90, MemberSanctionManager::NO_SEND_CONFIRMATION);
		}
		SessionData::recheck_cached_data_from_user_id($id_get);

		AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('ban'));
	}

	$moderation_panel_template->put_all(array(
		'C_MODO_PANEL_USER' => true,
		'L_ACTION_INFO' => $LANG['ban_management'],
		'L_LOGIN' => LangLoader::get_message('display_name', 'user-common'),
		'L_INFO_MANAGEMENT' => $LANG['ban_management'],
		'U_XMLHTTPREQUEST' => 'ban_user',
		'U_ACTION' => UserUrlBuilder::moderation_panel('ban')->rel() . '&amp;token=' . AppContext::get_session()->get_token()
	));

	if (empty($id_get)) //On liste les membres qui ont déjà un avertissement
	{
		if ($search_member)
		{
			$login = retrieve(POST, 'login_mbr', '');

			$user_id = 0;
			try {
				$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name LIKE :name', array('name' => '%' . $login . '%'));
			} catch (RowNotFoundException $ex) {}

			if (!empty($user_id) && !empty($login))
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('ban', $user_id));
			else
				AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('ban'));
		}

		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_LIST' => true,
			'L_PM' => $LANG['user_contact_pm'],
			'L_INFO' => $LANG['user_ban_until'],
			'L_ACTION_USER' => $LANG['ban_management'],
			'L_PROFILE' => LangLoader::get_message('profile', 'user-common'),
			'L_SEARCH_USER' => $LANG['search_member'],
			'L_SEARCH' => $LANG['search'],
			'L_REQUIRE_LOGIN' => $LANG['require_pseudo']
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, groups, delay_banned, warning_percentage
		FROM " . PREFIX . "member
		WHERE delay_banned > :now OR warning_percentage = 100
		ORDER BY delay_banned", array(
			'now' => time()
		));
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);

			$moderation_panel_template->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),
				'LOGIN' => $row['display_name'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO' => ($row['warning_percentage'] != 100) ? Date::to_format($row['delay_banned'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) : $LANG['illimited'],
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_ACTION_USER' => '<a href="'. UserUrlBuilder::moderation_panel('ban', $row['user_id'])->rel()  .'" class="fa fa-minus-circle error"></a>',
				'U_PM' => UserUrlBuilder::personnal_message($row['user_id'])->rel(),
			));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$moderation_panel_template->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER' => $LANG['no_ban'],
			));
		}
	}
	else //On affiche les infos sur l'utilisateur
	{
		try {
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'groups', 'delay_banned', 'warning_percentage'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$group_color = User::get_group_color($member['groups'], $member['level']);

		$moderation_panel_template->put_all(array(
			'C_MODO_PANEL_USER_BAN' => true,
			'C_USER_GROUP_COLOR' => !empty($group_color),
			'LOGIN' => $member['display_name'],
			'USER_LEVEL_CLASS' => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR' => $group_color,
			'KERNEL_EDITOR' => $editor->display(),
			'U_PM' => UserUrlBuilder::personnal_message($id_get)->rel(),
			'U_ACTION_INFO' => UserUrlBuilder::moderation_panel('ban', $id_get)->rel() . '&amp;token=' . AppContext::get_session()->get_token(),
			'U_PROFILE' => UserUrlBuilder::profile($id_get)->rel(),
			'L_PM' => $LANG['user_contact_pm'],
			'L_LOGIN' => LangLoader::get_message('display_name', 'user-common'),
			'L_BAN' => $LANG['ban_user'],
			'L_DELAY_BAN' => $LANG['user_ban_delay'],
		));

		//Temps de bannissement.
		$date_lang = LangLoader::get('date-common');
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 326592000);
		$array_sanction = array(LangLoader::get_message('no', 'common'), '1 ' . $date_lang['minute'], '5 ' . $date_lang['minutes'], '15 ' . $date_lang['minutes'], '30 ' . $date_lang['minutes'], '1 ' . $date_lang['hour'], '2 ' . $date_lang['hours'], '1 ' . $date_lang['day'], '2 ' . $date_lang['days'], '1 ' . $date_lang['week'], '2 ' . $date_lang['weeks'], '1 ' . $date_lang['month'], $LANG['illimited']);

		$diff = ($member['delay_banned'] - time());
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
		if ($member['warning_percentage'] == 100)
			$key_sanction = 12;

		//Affichge des sanctions
		foreach ($array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$moderation_panel_template->assign_block_vars('select_ban', array(
				'TIME' => '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>'
			));
		}
	}
}

$moderation_panel_template->display();

require_once('../kernel/footer.php');

?>
