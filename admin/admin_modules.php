<?php
/*##################################################
 *                               admin_modules_management.php
 *                            -------------------
 *   begin                : January 31, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
###################################################*/

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

$uninstall = !empty($_GET['uninstall']) ? true : false;
$id = !empty($_GET['id']) ? numeric($_GET['id']) : '0';
$error = !empty($_GET['error']) ? trim($_GET['error']) : ''; 
 
if( isset($_POST['valid']) )		
{
	$result = $Sql->Query_while("SELECT id, name, auth, activ 
	FROM ".PREFIX."modules", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$activ = isset($_POST['activ' . $row['id']]) ? numeric($_POST['activ' . $row['id']]) : '0';
		$array_auth_all = $Group->Return_array_auth_simple(ACCESS_MODULE, $row['id']);
		
		$Sql->Query_inject("UPDATE ".PREFIX."modules SET activ = '" . $activ . "', auth = '" . securit(serialize($array_auth_all), HTML_NO_PROTECT) . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
	}
	//Génération du cache des modules
	$Cache->Generate_file('modules');
	
	redirect(HOST . SCRIPT);	
}
elseif( $uninstall ) //Désinstallation du module
{
	if( !empty($_POST['valid_del']) )
	{
		$idmodule = !empty($_POST['idmodule']) ? numeric($_POST['idmodule']) : '0';
		$drop_files = !empty($_POST['drop_files']) ? true : false; 		
		//Suppression du modules dans la bdd => module désinstallé.
		$module_name = $Sql->Query("SELECT name FROM ".PREFIX."modules WHERE id = '" . $idmodule . "'", __LINE__, __FILE__);
		
		//Désinstallation du module
		if( !empty($idmodule) && !empty($module_name) )
		{
			$Sql->Query_inject("DELETE FROM ".PREFIX."modules WHERE id = '" . $idmodule . "'", __LINE__, __FILE__);
			
			//Récupération des infos de config.
			$info_module = load_ini_file('../' . $module_name . '/lang/', $CONFIG['lang']);
			
			//Suppression du fichier cache si il en  aun
			if( !empty($info_module['cache']) )
				$Cache->Delete_file($module_name);
			
			//Suppression des commentaires associés.
			if( !empty($info_module['com']) )
				$Sql->Query_inject("DELETE FROM ".PREFIX."com WHERE script = '" . securit($info_module['com']) . "'", __LINE__, __FILE__);
			
			//Suppression de la configuration.
			if( !empty($info_module['config']) )
				$Sql->Query_inject("DELETE FROM ".PREFIX."configs WHERE name = '" . securit($module_name) . "'", __LINE__, __FILE__);
			
			//Suppression du module mini.
			$Sql->Query_inject("DELETE FROM ".PREFIX."modules_mini WHERE name = '" . securit($module_name) . "'", __LINE__, __FILE__);
			
			//Si le dossier de base de données de la LANG n'existe pas on prend le suivant exisant.
			$dir_db_module = $CONFIG['lang'];
			$dir = '../' . $module_name . '/db';
			if( !is_dir($dir . '/' . $dir_db_module) )
			{	
				$dh = @opendir($dir);
				while( !is_bool($dir_db = @readdir($dh)) )
				{	
					if( !preg_match('`\.`', $dir_db) )
					{
						$dir_db_module = $dir_db;
						break;
					}
				}	
				@closedir($dh);
			}

			if( file_exists('../' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.' . DBTYPE . '.sql') ) //Parsage du fichier sql de désinstallation.
				$Sql->Sql_parse('../' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.' . DBTYPE . '.sql', PREFIX);
			
			if( file_exists('../' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.php') ) //Parsage fichier php de désinstallation.
				@include_once('../' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.php');
				
			$Cache->Generate_file('modules');
			$Cache->Generate_file('modules_mini');

			//Mise à jour du .htaccess pour le mod rewrite, si il est actif et que le module le supporte
			if( $CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']) )
				$Cache->Generate_htaccess(); //Régénération du htaccess.	 	
			
			//Suppression des fichiers du module
			if( $drop_files )
			{
				if( !delete_directory('../' . $module_name, '../' . $module_name) )
					$error = 'files_del_failed';
			}
			
			$error = !empty($error) ? '?error=' . $error : '';
			redirect(HOST . SCRIPT . $error);	
		}
		else
			redirect(HOST . DIR . '/admin/admin_modules.php?error=incomplete#errorh');
	}
	else
	{
		//Récupération de l'identifiant du module
		$idmodule = '';
		foreach($_POST as $key => $value)
			if( $value == $LANG['uninstall'] )
				$idmodule = $key;
				
		$Template->Set_filenames(array(
			'admin_modules_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_modules_management.tpl'
		));
		
		$Template->Assign_vars(array(
			'C_MODULES_DEL' => true,
			'THEME' => $CONFIG['theme'],
			'LANG' => $CONFIG['lang'],
			'IDMODULE' => $idmodule,
			'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
			'L_ADD_MODULES' => $LANG['add_modules'],
			'L_UPDATE_MODULES' => $LANG['update_modules'],
			'L_DEL_MODULE' => $LANG['del_module'],
			'L_DEL_DATA' => $LANG['del_module_data'],
			'L_DEL_FILE' => $LANG['del_module_files'],
			'L_NAME' => $LANG['name'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_SUBMIT' => $LANG['submit']
		));

		$Template->Pparse('admin_modules_management'); 
	}
}	
else
{			
	$Template->Set_filenames(array(
		'admin_modules_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_modules_management.tpl'
	));
	
	$Template->Assign_vars(array(
		'C_MODULES_LIST' => true,
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
		'L_ADD_MODULES' => $LANG['add_modules'],
		'L_UPDATE_MODULES' => $LANG['update_modules'],
		'L_MODULES_INSTALLED' => $LANG['modules_installed'],
		'L_NAME' => $LANG['name'],
		'L_DESC' => $LANG['description'],
		'L_ACTIV' => $LANG['activ'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_USE_SQL' => $LANG['use_sql'],
		'L_ADMIN' => $LANG['administration'],
		'L_USE_CACHE' => $LANG['use_cache'],
		'L_ALTERNATIVE_CSS' => $LANG['alternative_css'],
		'L_STARTEABLE_PAGE' => $LANG['starteable_page'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_AUTH_ACCESS' => $LANG['auth_access'],
		'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_UPDATE' => $LANG['update'],
		'L_MODULES_AVAILABLE' => $LANG['modules_available'],
		'L_NO_MODULES_INSTALLED' => $LANG['no_modules_installed'],
		'L_UNINSTALL' => $LANG['uninstall'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif( !empty($get_error) && isset($LANG[$get_error]) )
		$Errorh->Error_handler($LANG[$get_error], E_USER_WARNING);
		
	$array_groups = $Group->Create_groups_array(); //Création du tableau des groupes.

	//Modules installé
	$i = 0;	
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);
	$result = $Sql->Query_while("SELECT id, name, auth, activ 
	FROM ".PREFIX."modules
	ORDER BY name", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		//Récupération des infos de config.
		$info_module = load_ini_file('../' . $row['name'] . '/lang/', $CONFIG['lang']);
		
		//Récupération des tableaux des autorisations et des groupes.
		$array_auth = !empty($row['auth']) ? unserialize($row['auth']) : array();
		
		$l_tables = ($info_module['sql_table'] > 1) ? $LANG['tables'] : $LANG['table'];
		$Template->Assign_block_vars('installed', array(
			'ID' => $row['id'],
			'NAME' => ucfirst($info_module['name']),
			'ICON' => $row['name'],
			'VERSION' => $info_module['version'],
			'AUTHOR' => (!empty($info_module['author_mail']) ? '<a href="mailto:' . $info_module['author_mail'] . '">' . $info_module['author'] . '</a>' : $info_module['author']),
			'AUTHOR_WEBSITE' => (!empty($info_module['author_link']) ? '<a href="' . $info_module['author_link'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="" /></a>' : ''),
			'DESC' => $info_module['info'],
			'COMPAT' => $info_module['compatibility'],
			'ADMIN' => ($info_module['admin'] ? $LANG['yes'] : $LANG['no']),
			'USE_SQL' => (($info_module['sql_table'] > 0) ? $LANG['yes'] : $LANG['no']),
			'SQL_TABLE' => (($info_module['sql_table'] > 0) ? '(' . $info_module['sql_table'] . ' ' . $l_tables . ')' : ''),
			'USE_CACHE' => ($info_module['cache'] ? $LANG['yes'] : $LANG['no']),
			'ALTERNATIVE_CSS' => ($info_module['css'] ? $LANG['yes'] : $LANG['no']),	
			'STARTEABLE_PAGE' => ($info_module['starteable_page'] ? $LANG['yes'] : $LANG['no']),
			'ACTIV_ENABLED' => ($row['activ'] == 1 ? 'checked="checked"' : ''),
			'ACTIV_DISABLED' => ($row['activ'] == 0 ? 'checked="checked"' : ''),
			'AUTH_MODULES' => $Group->Generate_select_auth(ACCESS_MODULE, $array_auth, array(2 => true), $row['id']),
		));
		$i++;
	}
	$Sql->Close($result);

	if( $i == 0 )
		$Template->Assign_vars(array(
			'C_NO_MODULE_INSTALLED' => true
		));
	else
		$Template->Assign_vars(array(
			'C_MODULES_INSTALLED' => true
		));
	
	$Template->Pparse('admin_modules_management'); 
}

require_once('../includes/admin_footer.php');

?>