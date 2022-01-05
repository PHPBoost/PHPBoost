<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 05
 * @since       PHPBoost 1.6 - 2007 03 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');

$lang = LangLoader::get_all_langs();

$Bread_crumb->add($lang['user.users'], UserUrlBuilder::home()->rel());
$Bread_crumb->add($lang['user.moderation.panel'], UserUrlBuilder::moderation_panel()->rel());

$action = retrieve(GET, 'action', 'warning', TSTRING_UNCHANGE);
$id_get = (int)retrieve(GET, 'id', 0);
$valid_user = (bool)retrieve(POST, 'valid_user', false);
$search_member = (bool)retrieve(POST, 'search_member', false);

switch ($action)
{
	case 'ban':
		$Bread_crumb->add($lang['user.bans'], UserUrlBuilder::moderation_panel('ban')->rel());
		break;
	case 'punish':
		$Bread_crumb->add($lang['user.punishments'], UserUrlBuilder::moderation_panel('punish')->rel());
		break;
	case 'warning':
	default:
		$Bread_crumb->add($lang['user.warnings'], UserUrlBuilder::moderation_panel('warning')->rel());
}

define('TITLE', $lang['user.moderation.panel']);
require_once('../kernel/header.php');

if (!AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL)) // If user is not moderator
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$view = new FileTemplate('user/moderation_panel.tpl');
$view->add_lang($lang);

$view->put_all(array(
	'U_WARNING'          => UserUrlBuilder::moderation_panel('warning')->rel(),
	'U_PUNISH'           => UserUrlBuilder::moderation_panel('punish')->rel(),
	'U_BAN'              => UserUrlBuilder::moderation_panel('ban')->rel()
));

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('action_contents');

if ($action == 'punish')
{
	// User management
	$readonly_duration = (int)retrieve(POST, 'new_info', 0);
	$readonly = $readonly_duration > 0 ? (time() + $readonly_duration) : 0;
	$readonly_contents = retrieve(POST, 'action_contents', '', TSTRING_UNCHANGE);
	if (!empty($id_get) && $valid_user) // Update of warning level
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
		$user = UserService::get_user($id_get);
		$sanctions_duration = FormFieldMemberSanction::get_sanctions_duration();
		HooksService::execute_hook_action('user_punishment', $user->get_id(), array_merge($user->get_properties(), array('title' => $user->get_display_name(), 'url' => UserUrlBuilder::profile($user->get_id())->rel(), 'delay_readonly' => $readonly_duration)), isset($sanctions_duration[$readonly_duration]) ? $sanctions_duration[$readonly_duration] : '');

		AppContext::get_response()->redirect(HOST . DIR . url('/user/moderation_panel.php?action=punish', '', '&'));
	}

	$view->put_all(array(
		'C_USER' => true,
		'L_ACTION_INFO'     => $lang['user.punishments.management'],
		'U_XMLHTTPREQUEST'  => 'punish_user',
		'U_ACTION'          => UserUrlBuilder::moderation_panel('punish')->rel()
	));

	if (empty($id_get)) // Warned member list
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

		$view->put_all(array(
			'C_USER_LIST' => true,
			'L_TITLE'				 => $lang['user.punishments.management'],
			'L_INFO'                 => $lang['user.punish.until'],
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, user_groups, delay_readonly
		FROM " . PREFIX . "member
		WHERE delay_readonly > :now
		ORDER BY delay_readonly DESC", array(
			'now' => time()
		));
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);

			$view->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),

				'LOGIN'            => $row['display_name'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO'             => Date::to_format($row['delay_readonly'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),

				'U_PROFILE'     => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_ACTION_USER' => UserUrlBuilder::moderation_panel('punish', $row['user_id'])->rel(),
				'U_PM'          => UserUrlBuilder::personnal_message($row['user_id'])->rel(),
			));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$view->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER'    => $lang['user.no.punished.user'],
			));
		}
	}
	else // Display of user infos
	{
		try {
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'user_groups', 'delay_readonly'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		// Creating select form
		$select = '';
		// Warning duration
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 5184000, 326592000);
		$array_sanction = array($lang['common.no'], '1 ' . $lang['date.minute'], '5 ' . $lang['date.minutes'], '15 ' . $lang['date.minutes'], '30 ' . $lang['date.minutes'], '1 ' . $lang['date.hour'], '2 ' . $lang['date.hours'], '1 ' . $lang['date.day'], '2 ' . $lang['date.days'], '1 ' . $lang['date.week'], '2 ' . $lang['date.weeks'], '1 ' . $lang['date.month'], '2 ' . $lang['date.month'], '10 ' . TextHelper::strtolower($lang['date.years']));

		$diff = ($member['delay_readonly'] - time());
		$key_sanction = 0;
		if ($diff > 0)
		{
			// Returns the closest warning corresponding to the ban time.
			for ($i = 12; $i > 0; $i--)
			{
				$avg = ceil(($array_time[$i] + $array_time[$i-1])/2);
				if (($diff - $array_time[$i]) > $avg)
				{
					$key_sanction = $i + 1;
					break;
				}
			}
		}
		// Display of warnings
		foreach ($array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$select .= '<option value="' . $time . '" ' . $selected . '>' . TextHelper::strtolower($array_sanction[$key]) . '</option>';
		}

		$group_color = User::get_group_color($member['user_groups'], $member['level']);
		$view->put_all(array(
			'C_USER_INFO' => true,
			'C_USER_GROUP_COLOR'     => !empty($group_color),
			'LOGIN'                  => $member['display_name'],
			'USER_LEVEL_CLASS'       => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR'       => $group_color,
			'KERNEL_EDITOR'          => $editor->display(),
			'ALTERNATIVE_PM'         => ($key_sanction > 0) ? str_replace('%date%', $array_sanction[$key_sanction], $lang['user.readonly.changed']) : str_replace('%date%', '1 ' . $lang['date.minute'], $lang['user.readonly.changed']),
			'INFO'                   => $array_sanction[$key_sanction],
			'SELECT'                 => $select,
			'REPLACE_VALUE' => 'replace_value = parseInt(replace_value);'. "\n" .
				'array_time = new Array(' . (implode(', ', $array_time)) . ');' . "\n" .
				'array_sanction = new Array(\'' . implode('\', \'', array_map('addslashes', $array_sanction)) . '\');'. "\n" .
				'var i;
				for (i = 0; i <= ' . count($array_time) . '; i++)
				{
					if (array_time[i] == replace_value)
					{
						replace_value = array_sanction[i];
						break;
					}
				}' . "\n" .
				'if (replace_value != \'' . addslashes($lang['common.no']) . '\')' . "\n" .
				'{' . "\n" .
					'contents = contents.replace(regex, replace_value);' . "\n" .
					'document.getElementById(\'action_contents\').disabled = \'\'' . "\n" .
				'} else' . "\n" .
				'	document.getElementById(\'action_contents\').disabled = \'disabled\';' . "\n" .
				'document.getElementById(\'action_info\').innerHTML = replace_value;',
			'REGEX'            => '/[0-9]+ [a-zéèêA-Z]+/u',
			'U_PM'             => UserUrlBuilder::personnal_message($id_get)->rel(),
			'U_ACTION_INFO'    => UserUrlBuilder::moderation_panel('punish', $id_get)->rel() . '&amp;token=' . AppContext::get_session()->get_token(),
			'U_PROFILE'        => UserUrlBuilder::profile($id_get)->rel()
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

		// Moderators can't warn administrators
		if (!empty($info_mbr['user_id']) && ($info_mbr['level'] < 2 || AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL)))
		{
			if ($new_warning_level <= 100) // Warning level limit
			{
				// Sending PM to notify user, if user is not sender.
				if ($id_get != AppContext::get_current_user()->get_id())
				{
					MemberSanctionManager::caution($id_get, $new_warning_level, MemberSanctionManager::SEND_MP, $warning_contents);
				}
				else
				{
					MemberSanctionManager::caution($id_get, $new_warning_level, MemberSanctionManager::NO_SEND_CONFIRMATION, $warning_contents);
				}
				SessionData::recheck_cached_data_from_user_id($id_get);
				$user = UserService::get_user($id_get);
				HooksService::execute_hook_action('user_warning', $user->get_id(), array_merge($user->get_properties(), array('title' => $user->get_display_name(), 'url' => UserUrlBuilder::profile($user->get_id())->rel(), 'warning_percentage' => $new_warning_level)), $user->get_warning_percentage() . ' %');
			}
		}

		AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('warning'));
	}

	$view->put_all(array(
		'C_USER' => true,
		'L_ACTION_INFO'     => $lang['user.warnings.management'],
		'U_XMLHTTPREQUEST'  => 'warning_user',
		'U_ACTION'          => UserUrlBuilder::moderation_panel('warning')->rel() . '&amp;' . AppContext::get_session()->get_token()
	));

	if (empty($id_get)) // List of warned members
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

		$view->put_all(array(
			'C_USER_LIST' => true,
			'L_TITLE'				 => $lang['user.warnings.management'],
			'L_INFO'                 => $lang['user.warning.level'],
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, user_groups, warning_percentage
		FROM " . PREFIX . "member
		WHERE warning_percentage > 0
		ORDER BY warning_percentage");
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);

			$view->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),

				'LOGIN'            => $row['display_name'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO'             => $row['warning_percentage'] . '%',

				'U_ACTION_USER' => UserUrlBuilder::moderation_panel('warning', $row['user_id'])->rel(),
				'U_PROFILE'     => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_PM'          => UserUrlBuilder::personnal_message($row['user_id'])->rel()
			));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$view->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER'    => $lang['user.no.user.warning'],
			));
		}
	}
	else // Display of user infos
	{
		try {
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'user_groups', 'warning_percentage'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		// Creating select form
		$select = '';
		$j = 0;
		for ($j = 0; $j <=10; $j++)
		{
			if (10 * $j == $member['warning_percentage'])
				$select .= '<option value="' . 10 * $j . '" selected="selected">' . 10 * $j . '%</option>';
			else
				$select .= '<option value="' . 10 * $j . '">' . 10 * $j . '%</option>';
		}

		$group_color = User::get_group_color($member['user_groups'], $member['level']);

		$view->put_all(array(
			'C_USER_INFO' => true,
			'C_USER_GROUP_COLOR'     => !empty($group_color),
			'LOGIN'                  => $member['display_name'],
			'USER_LEVEL_CLASS'       => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR'       => $group_color,
			'KERNEL_EDITOR'          => $editor->display(),
			'ALTERNATIVE_PM'         => str_replace('%level%', $member['warning_percentage'], $lang['user.warning.level.changed']),
			'INFO'                   => $lang['user.warning.level'] . ': ' . $member['warning_percentage'] . '%',
			'SELECT'                 => $select,
			'REPLACE_VALUE'          => 'contents = contents.replace(regex, \' \' + replace_value + \'%\');' . "\n" . 'document.getElementById(\'action_info\').innerHTML = \'' . addslashes($lang['user.warning.level']) . ': \' + replace_value + \'%\';',
			'REGEX'                  => '/ [0-9]+%/u',
			'U_ACTION_INFO'          => UserUrlBuilder::moderation_panel('warning', $id_get)->rel() . '&amp;token=' . AppContext::get_session()->get_token(),
			'U_PM'                   => UserUrlBuilder::personnal_message($id_get)->rel(),
			'U_PROFILE'              => UserUrlBuilder::profile($id_get)->rel(),
			'L_INFO'                 => $lang['user.warning.level'],
		));
	}
}
else
{
	$user_ban_duration = retrieve(POST, 'user_ban', '', TSTRING_UNCHANGE);
	$user_ban = $user_ban_duration > 0 ? (time() + $user_ban_duration) : 0;
	if ($valid_user && !empty($id_get)) // User ban
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
		$user = UserService::get_user($id_get);
		$sanctions_duration = FormFieldMemberSanction::get_sanctions_duration();
		HooksService::execute_hook_action('user_ban', $user->get_id(), array_merge($user->get_properties(), array('title' => $user->get_display_name(), 'url' => UserUrlBuilder::profile($user->get_id())->rel(), 'delay_banned' => $user_ban_duration)), isset($sanctions_duration[$user_ban_duration]) ? $sanctions_duration[$user_ban_duration] : '');

		AppContext::get_response()->redirect(UserUrlBuilder::moderation_panel('ban'));
	}

	$view->put_all(array(
		'C_USER' => true,
		'L_ACTION_INFO'     => $lang['user.bans.management'],

		'U_XMLHTTPREQUEST'  => 'ban_user',
		'U_ACTION'          => UserUrlBuilder::moderation_panel('ban')->rel() . '&amp;token=' . AppContext::get_session()->get_token()
	));

	if (empty($id_get)) // List of already warned users
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

		$view->put_all(array(
			'C_USER_LIST' => true,

			'L_TITLE' => $lang['user.bans.management'],
			'L_INFO'  => $lang['user.ban.until'],
		));

		$i = 0;
		$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, user_groups, delay_banned, warning_percentage
		FROM " . PREFIX . "member
		WHERE delay_banned > :now OR warning_percentage = 100
		ORDER BY delay_banned", array(
			'now' => time()
		));
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);

			$view->assign_block_vars('member_list', array(
				'C_USER_GROUP_COLOR' => !empty($group_color),

				'LOGIN'            => $row['display_name'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $group_color,
				'INFO'             => ($row['warning_percentage'] != 100) ? Date::to_format($row['delay_banned'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE) : $lang['user.unlimited'],

				'U_PROFILE'     => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_ACTION_USER' => UserUrlBuilder::moderation_panel('ban', $row['user_id'])->rel(),
				'U_PM'          => UserUrlBuilder::personnal_message($row['user_id'])->rel(),
			));

			$i++;
		}
		$result->dispose();

		if ($i === 0)
		{
			$view->put_all(array(
				'C_EMPTY_LIST' => true,
				'L_NO_USER'    => $lang['user.no.ban'],
			));
		}
	}
	else // Display of user infos
	{
		try {
			$member = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('display_name', 'level', 'user_groups', 'delay_banned', 'warning_percentage'), 'WHERE user_id=:id', array('id' => $id_get));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$group_color = User::get_group_color($member['user_groups'], $member['level']);

		$view->put_all(array(
			'C_USER_BAN'         => true,
			'C_USER_GROUP_COLOR' => !empty($group_color),

			'LOGIN'            => $member['display_name'],
			'USER_LEVEL_CLASS' => UserService::get_level_class($member['level']),
			'USER_GROUP_COLOR' => $group_color,
			'KERNEL_EDITOR'    => $editor->display(),

			'U_PM'             => UserUrlBuilder::personnal_message($id_get)->rel(),
			'U_ACTION_INFO'    => UserUrlBuilder::moderation_panel('ban', $id_get)->rel() . '&amp;token=' . AppContext::get_session()->get_token(),
			'U_PROFILE'        => UserUrlBuilder::profile($id_get)->rel(),
		));

		// Ban duration
		$array_time = array(0, 60, 300, 900, 1800, 3600, 7200, 86400, 172800, 604800, 1209600, 2419200, 5184000, 326592000);
		$array_sanction = array($lang['common.no'], '1 ' . $lang['date.minute'], '5 ' . $lang['date.minutes'], '15 ' . $lang['date.minutes'], '30 ' . $lang['date.minutes'], '1 ' . $lang['date.hour'], '2 ' . $lang['date.hours'], '1 ' . $lang['date.day'], '2 ' . $lang['date.days'], '1 ' . $lang['date.week'], '2 ' . $lang['date.weeks'], '1 ' . $lang['date.month'], '2 ' . $lang['date.month'], $lang['user.unlimited']);

		$diff = ($member['delay_banned'] - time());
		$key_sanction = 0;
		if ($diff > 0)
		{
			// Returns the closest warning corresponding to the ban time.
			for ($i = 12; $i >= 0; $i--)
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
			$key_sanction = count($array_time);

		// Display of warnings
		foreach ($array_time as $key => $time)
		{
			$selected = ($key_sanction == $key) ? 'selected="selected"' : '' ;
			$view->assign_block_vars('select_ban', array(
				'TIME' => '<option value="' . $time . '" ' . $selected . '>' . $array_sanction[$key] . '</option>'
			));
		}
	}
}

$view->display();

require_once('../kernel/footer.php');

?>
