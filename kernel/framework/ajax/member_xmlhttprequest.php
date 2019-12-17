<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 19
 * @since       PHPBoost 1.6 - 2007 01 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '../../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$db_querier = PersistenceContext::get_querier();
$request = AppContext::get_request();

$member = $request->get_getint('member', 0);
$insert_member = $request->get_getint('insert_member', 0);
$add_member_auth = $request->get_getint('add_member_auth', 0);
$admin_member = $request->get_getint('admin_member', 0);
$warning_member = $request->get_getint('warning_member', 0);
$punish_member = $request->get_getint('punish_member', 0);
$warning_user = $request->get_getint('warning_user', 0);
$punish_user = $request->get_getint('punish_user', 0);
$ban_user = $request->get_getint('ban_user', 0);

$login = TextHelper::strprotect(utf8_decode($request->get_postvalue('login', '')));
$login = str_replace('*', '%', $login);
$divid = TextHelper::strprotect(utf8_decode($request->get_postvalue('divid', '')));
$admin = $request->get_postint('admin', 0);

if (!empty($member) || !empty($insert_member) || !empty($add_member_auth) || !empty($admin_member) || !empty($warning_member) || !empty($punish_member)) //Recherche d'un membre
{
	if (!empty($login))
	{
		$i = 0;
		$result = $db_querier->select("SELECT user_id, display_name FROM " . DB_TABLE_MEMBER . " WHERE display_name LIKE :login", array('login' => $login . '%'));
		while ($row = $result->fetch())
		{
			if (!empty($member))
			{
				echo '<a href="' . UserUrlBuilder::profile($row['user_id'])->rel() . '">' . $row['display_name'] . '</a><br />';
			}
			elseif (!empty($insert_member))
			{
				echo '<a href="#" onclick="document.getElementById(\'login\').value = \'' . addslashes($row['display_name']) .'\';return false">' . $row['display_name'] . '</a><br />';
			}
			elseif (!empty($add_member_auth))
			{
				echo '<a href="javascript:XMLHttpRequest_add_member_auth(\'' . addslashes($divid) . '\', ' . $row['user_id'] . ', \'' . addslashes($row['display_name']) . '\', \'' . addslashes($LANG['alert_member_already_auth']) . '\');">' . $row['display_name'] . '</a><br />';
			}
			elseif (!empty($admin_member))
			{
				echo '<a href="' . UserUrlBuilder::profile($row['user_id'])->rel() . '">' . $row['display_name'] . '</a><br />';
			}
			if (!empty($warning_member))
			{
				echo '<a href="admin_members_punishment.php?action=users&amp;id=' . $row['user_id'] . '">' . $row['display_name'] . '</a><br />';
			}
			elseif (!empty($punish_member))
			{
				echo '<a href="admin_members_punishment.php?action=punish&amp;id=' . $row['user_id'] . '">' . $row['display_name'] . '</a><br />';
			}
			$i++;
		}
		if ($i == 0) //Aucun membre trouvé.
		{
			echo $LANG['no_result'];
		}
	}
	else
	{
		echo $LANG['no_result'];
	}
}
elseif (!empty($warning_user) || !empty($punish_user) || !empty($ban_user)) //Recherche d'un membre
{
	if (!empty($login))
	{
		$i = 0;
		$result = $db_querier->select("SELECT user_id, display_name FROM " . DB_TABLE_MEMBER . " WHERE display_name LIKE :login", array('login' => $login . '%'));
		while ($row = $result->fetch())
		{
			$url_warn = ($admin) ? 'admin_members_punishment.php?action=warning&amp;id=' . $row['user_id'] : url('moderation_panel.php?action=warning&amp;id=' . $row['user_id']);
			$url_punish = ($admin) ? 'admin_members_punishment.php?action=punish&amp;id=' . $row['user_id'] : url('moderation_panel.php?action=punish&amp;id=' . $row['user_id']);
			$url_ban = ($admin) ? 'admin_members_punishment.php?action=ban&amp;id=' . $row['user_id'] : url('moderation_panel.php?action=ban&amp;id=' . $row['user_id']);

			if (!empty($warning_user))
			{
				echo '<a href="' . $url_warn . '">' . $row['display_name'] . '</a><br />';
			}
			elseif (!empty($punish_user))
			{
				echo '<a href="' . $url_punish . '">' . $row['display_name'] . '</a><br />';
			}
			elseif (!empty($ban_user))
			{
				echo '<a href="' . $url_ban . '">' . $row['display_name'] . '</a><br />';
			}
			$i++;
		}

		if ($i == 0) //Aucun membre trouvé.
		{
			echo $LANG['no_result'];
		}
	}
	else
	{
		echo $LANG['no_result'];
	}
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
