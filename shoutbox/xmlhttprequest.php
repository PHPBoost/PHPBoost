<?php
/*##################################################
 *                                xmlhttprequest.php
 *                            -------------------
 *   begin                : December 20, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/header_no_display.php');
load_module_lang('shoutbox'); //Chargement de la langue du module.

$add = !empty($_GET['add']) ? true : false;
$del = !empty($_GET['del']) ? true : false;
$refresh = !empty($_GET['refresh']) ? true : false;
$display_date = isset($_GET['display_date']) && ($_GET['display_date'] == '1');

$config_shoutbox = ShoutboxConfig::load();
if ($add)
{
	//Membre en lecture seule?
	if (AppContext::get_current_user()->get_delay_readonly() > time()) 
	{
		echo -6;
		exit;
	}
	
	$shout_pseudo = !empty($_POST['pseudo']) ? TextHelper::strprotect(utf8_decode($_POST['pseudo'])) : $LANG['guest'];
	
	$shout_contents = htmlentities(retrieve(POST, 'contents', ''), ENT_COMPAT, 'UTF-8');
	$shout_contents = htmlspecialchars_decode(stripslashes(html_entity_decode($shout_contents, ENT_COMPAT, 'ISO-8859-1')));
	
	if (!empty($shout_pseudo) && !empty($shout_contents))
	{
		//Accès pour poster.
		if (ShoutboxAuthorizationsService::check_authorizations()->write())
		{
			//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
			$check_time = (AppContext::get_current_user()->get_id() !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) ? PersistenceContext::get_querier()->get_column_value(PREFIX . "shoutbox", 'MAX(timestamp)', 'WHERE user_id = :id', array('id' => AppContext::get_current_user()->get_id())) : '';
			if (!empty($check_time) && !AppContext::get_current_user()->check_max_value(AUTH_FLOOD))
			{
				if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
				{
					echo -2;
					exit;
				}
			}
			
			//Vérifie que le message ne contient pas du flood de lien.
			$shout_contents = FormatingHelper::strparse($shout_contents, $config_shoutbox->get_forbidden_formatting_tags());
			if (!TextHelper::check_nbr_links($shout_contents, $config_shoutbox->get_max_links_number_per_message(), true)) //Nombre de liens max dans le message.
			{	
				echo -4;
				exit;
			}
			
			$result = PersistenceContext::get_querier()->insert(PREFIX . "shoutbox", array('login' => $shout_pseudo, 'user_id' => AppContext::get_current_user()->get_id(), 'level' => AppContext::get_current_user()->get_level(), 'contents' => $shout_contents, 'timestamp' => time()));
			$last_msg_id = $result->get_last_inserted_id(); 
			
			$date = new Date(DATE_TIMESTAMP, Timezone::SERVER_TIMEZONE, time());
			$date = $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
			
			$array_class = array('member', 'modo', 'admin');
			if (AppContext::get_current_user()->get_id() !== -1)
			{
				$group_color = User::get_group_color(AppContext::get_current_user()->get_groups(), AppContext::get_current_user()->get_level(), true);
				$style = $group_color ? 'style="color:'.$group_color.'"' : '';
				$shout_pseudo = ($display_date ? '<span class="small">' . $date . ' : </span>' : '') . '<a href="javascript:Confirm_del_shout(' . $last_msg_id . ');" title="' . $LANG['delete'] . '" class="small"><i class="fa fa-remove"></i></a> <a class="small ' . UserService::get_level_class(AppContext::get_current_user()->get_level()) . '" '.$style.' href="' . UserUrlBuilder::profile(AppContext::get_current_user()->get_id())->rel() . '">' . (!empty($shout_pseudo) ? TextHelper::wordwrap_html($shout_pseudo, 16) : $LANG['guest'])  . ' </a>';
			}
			else
				$shout_pseudo = ($display_date ? '<span class="small">' . $date . ' : </span>' : '') . '<span class="small" style="font-style: italic;">' . (!empty($shout_pseudo) ? TextHelper::wordwrap_html($shout_pseudo, 16) : $LANG['guest']) . ' </span>';
			
			echo "array_shout[0] = '" . $shout_pseudo . "';";
			echo "array_shout[1] = '" . FormatingHelper::second_parse($shout_contents) . "';";
			echo "array_shout[2] = '" . $last_msg_id . "';";
		}
		else //utilisateur non autorisé!
			echo -1;
	}
	else
		echo -5;
}
elseif ($refresh)
{
	$array_class = array('member', 'modo', 'admin');
	$result = PersistenceContext::get_querier()->select("SELECT s.id, s.display_name, s.user_id, s.level, s.contents, s.timestamp, m.user_groups
	FROM " . PREFIX . "shoutbox s
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
	ORDER BY timestamp DESC 
	LIMIT 25 OFFSET 0");
	while ($row = $result->fetch())
	{
		$row['user_id'] = (int)$row['user_id'];
		if (ShoutboxAuthorizationsService::check_authorizations()->moderation() || ($row['user_id'] === AppContext::get_current_user()->get_id() && AppContext::get_current_user()->get_id() !== -1))
			$del = '<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '" class="small"><i class="fa fa-remove"></i></a>';
		else
			$del = '';
			
		$date = new Date(DATE_TIMESTAMP, Timezone::SERVER_TIMEZONE, $row['timestamp']);
		$date = $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE);
		
		if ($row['user_id'] !== -1) 
		{
			$group_color = User::get_group_color($row['groups'], $row['level']);
			$style = $group_color ? 'style="color:'.$group_color.'"' : '';
			$row['login'] = ($display_date ? '<span class="small">' . $date . ' : </span>' : '') . $del . ' <a class="small ' . UserService::get_level_class($row['level']) . '" '.$style.' href="'. UserUrlBuilder::profile($row['user_id'])->rel()  . '">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 16) : $LANG['guest'])  . ' </a>';
		}
		else
			$row['login'] = ($display_date ? '<span class="small">' . $date . ' : </span>' : '') . $del . ' <span class="small" style="font-style: italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 16) : $LANG['guest']) . ' </span>';
		
		echo '<p id="shout-container-' . $row['id'] . '">' . $row['login'] . '<span class="small">: ' . FormatingHelper::second_parse($row['contents']) . '</span></p>' . "\n";
	}
	$result->dispose();
}
elseif ($del)
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$shout_id = !empty($_POST['idmsg']) ? NumberHelper::numeric($_POST['idmsg']) : '';
	if (!empty($shout_id))
	{
		$user_id = (int)PersistenceContext::get_querier()->get_column_value(PREFIX . "shoutbox", 'user_id', 'WHERE id = :id', array('id' => $shout_id));
		if (ShoutboxAuthorizationsService::check_authorizations()->moderation() || ($user_id === AppContext::get_current_user()->get_id() && AppContext::get_current_user()->get_id() !== -1))
		{
			PersistenceContext::get_querier()->delete(PREFIX . 'shoutbox', 'WHERE id=:id', array('id' => $shout_id));
			echo 1;
		}
	}
}

require_once('../kernel/footer_no_display.php');
?>