<?php
header('Content-type: text/html; charset=iso-8859-15');

include_once('../includes/begin.php');
define('TITLE', 'Ajax');
include_once('../includes/header_no_display.php');


if( !empty($_GET['preview']) ) //Prévisualisation des messages.
{
	$contents = second_parse(stripslashes(parse(utf8_decode($_POST['contents']), explode(', ', $_POST['ftags']))));
	
	echo !empty($contents) ? $contents : '';	
}
elseif( !empty($_GET['pm']) ) //Recherche d'un membre pour envoyer le mp.
{
	$login = !empty($_POST['login']) ? securit(utf8_decode($_POST['login'])) : '';
	if( !empty($login) )
	{
		$i = 0;
		$result = $sql->query_while("SELECT login FROM ".PREFIX."member WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			echo '<a href="#" onclick="insert_XMLHttpRequest(\'' . $row['login'] .'\')">' . $row['login'] . '</a><br />';
			$i++;
		}
		
		if( $i == 0 ) //Aucun membre trouvé.
			echo $LANG['no_result'];
	}
	else
	{
		echo $LANG['no_result'];
	}
	
	$sql->sql_close(); //Fermeture de mysql*/
}
elseif( !empty($_GET['member']) || !empty($_GET['group_member']) || !empty($_GET['admin_member']) || !empty($_GET['warning_member']) || !empty($_GET['punish_member']) ) //Recherche d'un membre
{
	$login = !empty($_POST['login']) ? securit(utf8_decode($_POST['login'])) : '';
	$login = str_replace('*', '%', $login);
	if( !empty($login) )
	{
		$i = 0;
		$result = $sql->query_while("SELECT user_id, login FROM ".PREFIX."member WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			if( !empty($_GET['member']) )
				echo '<a href="member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['group_member']) )	
				echo '<a href="javascript:insert_XMLHttpRequest(\'' . $row['login'] .'\')">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['admin_member']) )	
				echo '<a href="../admin/admin_members.php?id=' . $row['user_id'] . '#search">' . $row['login'] . '</a><br />';
			if( !empty($_GET['warning_member']) )
				echo '<a href="admin_members_punishment.php?action=users&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['punish_member']) )
				echo '<a href="admin_members_punishment.php?action=punish&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			$i++;
		}
		
		if( $i == 0 ) //Aucun membre trouvé.
			echo $LANG['no_result'];
	}
	else
	{
		echo $LANG['no_result'];
	}
	
	$sql->sql_close(); //Fermeture de mysql*/
}
elseif( !empty($_GET['new_folder']) ) //Ajout d'un dossier dans la gestion des fichiers.
{
	//Initialisation  de la class de gestion des fichiers.
	include_once('../includes/files.class.php');
	$files = new Files($sql->req); 
	
	$id_parent = !empty($_POST['id_parent']) ? numeric($_POST['id_parent']) : '0';
	$user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $session->data['user_id'];
	$name = !empty($_POST['name']) ? securit(utf8_decode($_POST['name'])) : '';

	if( $session->data['user_id'] != $user_id )
	{	
		if( $session->check_auth($session->data, 2) )
			echo $files->add_folder($id_parent, $user_id, $name);
		else
			echo $files->add_folder($id_parent, $session->data['user_id'], $name);		
	}
	else
		echo $files->add_folder($id_parent, $session->data['user_id'], $name);
}
elseif( !empty($_GET['rename_folder']) ) //Renomme un dossier dans la gestion des fichiers.
{
	//Initialisation  de la class de gestion des fichiers.
	include_once('../includes/files.class.php');
	$files = new Files($sql->req); 
	
	$id_folder = !empty($_POST['id_folder']) ? numeric($_POST['id_folder']) : '0';
	$name = !empty($_POST['name']) ? securit(utf8_decode($_POST['name'])) : '';
	$user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $session->data['user_id'];
	$previous_name = !empty($_POST['previous_name']) ? securit(utf8_decode($_POST['previous_name'])) : '';
	
	if( !empty($id_folder) && !empty($name) )
	{
		if( $session->data['user_id'] != $user_id )
		{	
			if( $session->check_auth($session->data, 2) )
				echo $files->rename_folder($id_folder, $name, $previous_name, $user_id, ADMIN_NO_CHECK);
			else
				echo $files->rename_folder($id_folder, $name, $previous_name, $session->data['user_id'], ADMIN_NO_CHECK);
		}
		else
			echo $files->rename_folder($id_folder, $name, $previous_name, $session->data['user_id']);
	}
	else 
		echo 0;
}
elseif( !empty($_GET['rename_file']) ) //Renomme un fichier d'un dossier dans la gestion des fichiers.
{
	//Initialisation  de la class de gestion des fichiers.
	include_once('../includes/files.class.php');
	$files = new Files($sql->req); 
	
	$id_file = !empty($_POST['id_file']) ? numeric($_POST['id_file']) : '0';
	$user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : $session->data['user_id'];
	$name = !empty($_POST['name']) ? securit(utf8_decode($_POST['name'])) : '';
	$previous_name = !empty($_POST['previous_name']) ? securit(utf8_decode($_POST['previous_name'])) : '';
	
	if( !empty($id_file) && !empty($name) )
	{		
		if( $session->data['user_id'] != $user_id )
		{	
			if( $session->check_auth($session->data, 2) )
				echo $files->rename_file($id_file, $name, $previous_name, $user_id, ADMIN_NO_CHECK);
			else
				echo $files->rename_file($id_file, $name, $previous_name, $session->data['user_id'], ADMIN_NO_CHECK);
		}
		else
			echo $files->rename_file($id_file, $name, $previous_name, $session->data['user_id']);		
	}
	else 
		echo 0;
}
elseif( !empty($_GET['warning_user']) || !empty($_GET['punish_user']) || !empty($_GET['ban_user']) ) //Recherche d'un membre
{
	$login = !empty($_POST['login']) ? securit(utf8_decode($_POST['login'])) : '';
	$login = str_replace('*', '%', $login);
	$admin = !empty($_POST['admin']) ? true : false;
	if( !empty($login) )
	{
		$i = 0;
		$result = $sql->query_while("SELECT user_id, login FROM ".PREFIX."member WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
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
	
	$sql->sql_close(); //Fermeture de mysql*/
}

?>