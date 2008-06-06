<?php

define('PATH_TO_ROOT', '..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

if( !empty($_GET['preview']) ) //Prévisualisation des messages.
{
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$ftags = !empty($_POST['ftags']) ? trim($_POST['ftags']) : '';
	$contents = second_parse(stripslashes(strparse(utf8_decode($contents), explode(', ', $ftags))));

	echo !empty($contents) ? $contents : '';	
}
elseif( !empty($_GET['member']) || !empty($_GET['insert_member']) || !empty($_GET['add_member_auth']) || !empty($_GET['admin_member']) || !empty($_GET['warning_member']) || !empty($_GET['punish_member']) ) //Recherche d'un membre
{
	$login = !empty($_POST['login']) ? strprotect(utf8_decode($_POST['login'])) : '';
	$divid = !empty($_POST['divid']) ? strprotect(utf8_decode($_POST['divid'])) : '';
	$login = str_replace('*', '%', $login);
	if( !empty($login) )
	{
		$i = 0;
		$result = $Sql->Query_while("SELECT user_id, login FROM ".PREFIX."member WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			if( !empty($_GET['member']) )
				echo '<a href="member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['insert_member']) )	
				echo '<a href="#" onclick="document.getElementById(\'login\').value = \'' . addslashes($row['login']) .'\'">' . addslashes($row['login']) . '</a><br />';
			elseif( !empty($_GET['add_member_auth']) )	
				echo '<a href="javascript:XMLHttpRequest_add_member_auth(\'' . addslashes($divid) . '\', ' . $row['user_id'] . ', \'' . addslashes($row['login']) . '\', \'' . addslashes($LANG['alert_member_already_auth']) . '\');">' . addslashes($row['login']) . '</a><br />';
			elseif( !empty($_GET['admin_member']) )	
				echo '<a href="../admin/admin_members.php?id=' . $row['user_id'] . '#search">' . addslashes($row['login']) . '</a><br />';
			if( !empty($_GET['warning_member']) )
				echo '<a href="admin_members_punishment.php?action=users&amp;id=' . $row['user_id'] . '">' . addslashes($row['login']) . '</a><br />';
			elseif( !empty($_GET['punish_member']) )
				echo '<a href="admin_members_punishment.php?action=punish&amp;id=' . $row['user_id'] . '">' . addslashes($row['login']) . '</a><br />';
			$i++;
		}		
		if( $i == 0 ) //Aucun membre trouvé.
			echo $LANG['no_result'];
	}
	else
		echo $LANG['no_result'];
	
	$Sql->Sql_close(); //Fermeture de mysql*/
}
elseif( !empty($_GET['stats_referer']) ) //Recherche d'un membre pour envoyer le mp.
{
	$idurl = !empty($_GET['id']) ? numeric($_GET['id']) : '';
	$url = $Sql->Query("SELECT url FROM ".PREFIX."stats_referer WHERE id = '" . $idurl . "'", __LINE__, __FILE__);

	$result = $Sql->Query_while("SELECT url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
	FROM ".PREFIX."stats_referer
	WHERE url = '" . addslashes($url) . "' AND type = 0
	ORDER BY total_visit DESC", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{	
		$average = ($row['total_visit'] / $row['nbr_day']);
		if( $row['yesterday_visit'] > $average )
		{
			$trend_img = 'up.png';
			$sign = '+';
			$trend = number_round((($row['yesterday_visit'] * 100) / $average), 1) - 100;
		}
		elseif( $row['yesterday_visit'] < $average )
		{
			$trend_img = 'down.png';
			$sign = '-';
			$trend = 100 - number_round((($row['yesterday_visit'] * 100) / $average), 1);
		}
		else
		{	
			$trend_img = 'right.png';
			$sign = '+';
			$trend = 0;
		}

		echo '<table style="width:100%;border:none;border-collapse:collapse;">
			<tr>
				<td style="text-align:center;">		
					<a href="' . $row['url'] . $row['relative_url'] . '">' . $row['relative_url'] . '</a>					
				</td>
				<td style="width:112px;text-align:center;">
					' . $row['total_visit'] . '
				</td>
				<td style="width:112px;text-align:center;">
					' . number_round($average, 1) . '
				</td>
				<td style="width:102px;text-align:center;">
					' . gmdate_format('date_format_short', $row['last_update']) . '
				</td>
				<td style="width:105px;">
					<img src="../templates/' . $CONFIG['theme'] . '/images/admin/' . $trend_img . '" alt="" class="valign_middle" /> (' . $sign . $trend . '%)
				</td>
			</tr>
		</table>';
	}
	$Sql->Close($result);
	
	$Sql->Sql_close(); //Fermeture de mysql*/
}
elseif( !empty($_GET['stats_keyword']) ) //Recherche d'un membre pour envoyer le mp.
{
	$idkeyword = !empty($_GET['id']) ? numeric($_GET['id']) : '';
	$keyword = $Sql->Query("SELECT relative_url FROM ".PREFIX."stats_referer WHERE id = '" . $idkeyword . "'", __LINE__, __FILE__);

	$result = $Sql->Query_while("SELECT url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
	FROM ".PREFIX."stats_referer
	WHERE relative_url = '" . addslashes($keyword) . "' AND type = 1
	ORDER BY total_visit DESC", __LINE__, __FILE__);
	while($row = $Sql->Sql_fetch_assoc($result))
	{	
		$average = ($row['total_visit'] / $row['nbr_day']);
		if( $row['yesterday_visit'] > $average )
		{
			$trend_img = 'up.png';
			$sign = '+';
			$trend = number_round((($row['yesterday_visit'] * 100) / $average), 1) - 100;
		}
		elseif( $row['yesterday_visit'] < $average )
		{
			$trend_img = 'down.png';
			$sign = '-';
			$trend = 100 - number_round((($row['yesterday_visit'] * 100) / $average), 1);
		}
		else
		{	
			$trend_img = 'right.png';
			$sign = '+';
			$trend = 0;
		}

		echo '<table style="width:100%;border:none;border-collapse:collapse;">
			<tr>
				<td style="text-align:center;">		
					' . ucfirst($row['url']) . '					
				</td>
				<td style="width:112px;text-align:center;">
					' . $row['total_visit'] . '
				</td>
				<td style="width:112px;text-align:center;">
					' . number_round($average, 1) . '
				</td>
				<td style="width:102px;text-align:center;">
					' . gmdate_format('date_format_short', $row['last_update']) . '
				</td>
				<td style="width:105px;">
					<img src="../templates/' . $CONFIG['theme'] . '/images/admin/' . $trend_img . '" alt="" class="valign_middle" /> (' . $sign . $trend . '%)
				</td>
			</tr>
		</table>';
	}
	$Sql->Close($result);
	
	$Sql->Sql_close(); //Fermeture de mysql*/
}
elseif( !empty($_GET['new_folder']) ) //Ajout d'un dossier dans la gestion des fichiers.
{
	//Initialisation  de la class de gestion des fichiers.
	include_once(PATH_TO_ROOT . '/kernel/framework/files/files.class.php');
	$Files = new Files; 
	
	$id_parent = !empty($_POST['id_parent']) ? numeric($_POST['id_parent']) : '0';
	$user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $Member->Get_attribute('user_id');
	$name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';

	if( $Member->Get_attribute('user_id') != $user_id )
	{	
		if( $Member->Check_level(ADMIN_LEVEL) )
			echo $Files->Add_folder($id_parent, $user_id, $name);
		else
			echo $Files->Add_folder($id_parent, $Member->Get_attribute('user_id'), $name);		
	}
	else
		echo $Files->Add_folder($id_parent, $Member->Get_attribute('user_id'), $name);
}
elseif( !empty($_GET['rename_folder']) ) //Renomme un dossier dans la gestion des fichiers.
{
	//Initialisation  de la class de gestion des fichiers.
	include_once(PATH_TO_ROOT . '/kernel/framework/files/files.class.php');
	$Files = new Files; 
	
	$id_folder = !empty($_POST['id_folder']) ? numeric($_POST['id_folder']) : '0';
	$name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';
	$user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $Member->Get_attribute('user_id');
	$previous_name = !empty($_POST['previous_name']) ? strprotect(utf8_decode($_POST['previous_name'])) : '';
	
	if( !empty($id_folder) && !empty($name) )
	{
		if( $Member->Get_attribute('user_id') != $user_id )
		{	
			if( $Member->Check_level(ADMIN_LEVEL) )
				echo $Files->Rename_folder($id_folder, $name, $previous_name, $user_id, ADMIN_NO_CHECK);
			else
				echo $Files->Rename_folder($id_folder, $name, $previous_name, $Member->Get_attribute('user_id'), ADMIN_NO_CHECK);
		}
		else
			echo $Files->Rename_folder($id_folder, $name, $previous_name, $Member->Get_attribute('user_id'));
	}
	else 
		echo 0;
}
elseif( !empty($_GET['rename_file']) ) //Renomme un fichier d'un dossier dans la gestion des fichiers.
{
	//Initialisation  de la class de gestion des fichiers.
	include_once(PATH_TO_ROOT . '/kernel/framework/files/files.class.php');
	$Files = new Files; 
	
	$id_file = !empty($_POST['id_file']) ? numeric($_POST['id_file']) : '0';
	$user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $Member->Get_attribute('user_id');
	$name = !empty($_POST['name']) ? strprotect(utf8_decode($_POST['name'])) : '';
	$previous_name = !empty($_POST['previous_name']) ? strprotect(utf8_decode($_POST['previous_name'])) : '';
	
	if( !empty($id_file) && !empty($name) )
	{		
		if( $Member->Get_attribute('user_id') != $user_id )
		{	
			if( $Member->Check_level(ADMIN_LEVEL) )
				echo $Files->Rename_file($id_file, $name, $previous_name, $user_id, ADMIN_NO_CHECK);
			else
				echo $Files->Rename_file($id_file, $name, $previous_name, $Member->Get_attribute('user_id'), ADMIN_NO_CHECK);
		}
		else
			echo $Files->Rename_file($id_file, $name, $previous_name, $Member->Get_attribute('user_id'));		
	}
	else 
		echo 0;
}
elseif( !empty($_GET['warning_user']) || !empty($_GET['punish_user']) || !empty($_GET['ban_user']) ) //Recherche d'un membre
{
	$login = !empty($_POST['login']) ? strprotect(utf8_decode($_POST['login'])) : '';
	$login = str_replace('*', '%', $login);
	$admin = !empty($_POST['admin']) ? true : false;
	if( !empty($login) )
	{
		$i = 0;
		$result = $Sql->Query_while("SELECT user_id, login FROM ".PREFIX."member WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$url_warn = ($admin) ? 'admin_members_punishment.php?action=warning&amp;id=' . $row['user_id'] : transid('moderation_panel.php?action=warning&amp;id=' . $row['user_id']);
			$url_punish = ($admin) ? 'admin_members_punishment.php?action=punish&amp;id=' . $row['user_id'] : transid('moderation_panel.php?action=punish&amp;id=' . $row['user_id']);
			$url_ban = ($admin) ? 'admin_members_punishment.php?action=ban&amp;id=' . $row['user_id'] : transid('moderation_panel.php?action=ban&amp;id=' . $row['user_id']);
			
			if( !empty($_GET['warning_user']) )
				echo '<a href="' . $url_warn . '">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['punish_user']) )
				echo '<a href="' . $url_punish . '">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['ban_user']) )
				echo '<a href="' . $url_ban . '">' . $row['login'] . '</a><br />';
			$i++;
		}
		
		if( $i == 0 ) //Aucun membre trouvé.
			echo $LANG['no_result'];
	}
	else
		echo $LANG['no_result'];
	
	$Sql->Sql_close(); //Fermeture de mysql*/
}

?>