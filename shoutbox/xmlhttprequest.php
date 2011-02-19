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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
require_once('../shoutbox/shoutbox_begin.php');
require_once('../kernel/header_no_display.php');
require_once('shoutbox_constants.php');

$add = !empty($_GET['add']) ? true : false;
$del = !empty($_GET['del']) ? true : false;
$refresh = !empty($_GET['refresh']) ? true : false;

$config_shoutbox = ShoutboxConfig::load();
if ($add)
{
	//Membre en lecture seule?
	if ($User->get_attribute('user_readonly') > time()) 
	{
		echo -6;
		exit;
	}
		
	$shout_pseudo = !empty($_POST['pseudo']) ? TextHelper::strprotect(utf8_decode($_POST['pseudo'])) : $LANG['guest'];
	$shout_contents = !empty($_POST['contents']) ? trim(utf8_decode($_POST['contents'])) : '';
	if (!empty($shout_pseudo) && !empty($shout_contents))
	{
		//Accès pour poster.		
		if ($User->check_auth($config_shoutbox->get_authorization(), AUTH_SHOUTBOX_WRITE))
		{
			//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
			$check_time = ($User->get_id() !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "shoutbox WHERE user_id = '" . $User->get_id() . "'", __LINE__, __FILE__) : '';
			if (!empty($check_time) && !$User->check_max_value(AUTH_FLOOD))
			{
				if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
				{
					echo -2;
					exit;
				}
			}
			
			//Vérifie que le message ne contient pas du flood de lien.
			$shout_contents = FormatingHelper::strparse($shout_contents, $config_shoutbox->get_forbidden_formatting_tags());		
			if (!TextHelper::check_nbr_links($shout_pseudo, 0)) //Nombre de liens max dans le pseudo.
			{	
				echo -3;
				exit;
			}
			if (!TextHelper::check_nbr_links($shout_contents, $config_shoutbox->get_max_links_number_per_message())) //Nombre de liens max dans le message.
			{	
				echo -4;
				exit;
			}
			
			$Sql->query_inject("INSERT INTO " . PREFIX . "shoutbox (login, user_id, level, contents, timestamp) VALUES('" . $shout_pseudo . "', '" . $User->get_id() . "', '" . $User->get_level() . "', '" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
			$last_msg_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "shoutbox"); 
			
			$array_class = array('member', 'modo', 'admin');
			if ($User->get_id() !== -1)
				$shout_pseudo = '<a href="javascript:Confirm_del_shout(' . $last_msg_id . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a> <a style="font-size:10px;" class="' . $array_class[$User->get_level()] . '" href="../member/member' . url('.php?id=' . $User->get_id(), '-' . $User->get_id() . '.php') . '">' . (!empty($shout_pseudo) ? TextHelper::wordwrap_html($shout_pseudo, 16) : $LANG['guest'])  . '</a>';
			else
				$shout_pseudo = '<span class="text_small" style="font-style: italic;">' . (!empty($shout_pseudo) ? TextHelper::wordwrap_html($shout_pseudo, 16) : $LANG['guest']) . '</span>';
			
			echo "array_shout[0] = '" . $shout_pseudo . "';";
			echo "array_shout[1] = '" . addslashes(FormatingHelper::second_parse(str_replace(array("\n", "\r"), array('', ''), ucfirst(stripslashes($shout_contents))))) . "';";
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
	$result = $Sql->query_while("SELECT id, login, user_id, level, contents 
	FROM " . PREFIX . "shoutbox 
	ORDER BY timestamp DESC 
	" . $Sql->limit(0, 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$row['user_id'] = (int)$row['user_id'];		
		if ($User->check_level(MODO_LEVEL) || ($row['user_id'] === $User->get_id() && $User->get_id() !== -1))
			$del = '<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . get_utheme() . '/images/delete_mini.png" alt="" /></a>';
		else
			$del = '';
	
		if ($row['user_id'] !== -1) 
			$row['login'] = $del . ' <a style="font-size:10px;" class="' . $array_class[$row['level']] . '" href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 16) : $LANG['guest'])  . '</a>';
		else
			$row['login'] = $del . ' <span class="text_small" style="font-style: italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 16) : $LANG['guest']) . '</span>';
		
		echo '<p id="shout_container_' . $row['id'] . '">' . $row['login'] . '<span class="text_small">: ' . str_replace(array("\n", "\r"), array('', ''), ucfirst(FormatingHelper::second_parse($row['contents']))) . '</span></p>' . "\n";
	}
	$Sql->query_close($result);
}
elseif ($del)
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$shout_id = !empty($_POST['idmsg']) ? NumberHelper::numeric($_POST['idmsg']) : '';
	if (!empty($shout_id))
	{
		$user_id = (int)$Sql->query("SELECT user_id FROM " . PREFIX . "shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
		if ($User->check_level(MODO_LEVEL) || ($user_id === $User->get_id() && $User->get_id() !== -1))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			echo 1;
		}
	}
}

require_once('../kernel/footer_no_display.php');

?>
