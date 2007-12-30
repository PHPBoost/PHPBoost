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

$update = !empty($_GET['update']) ? true : false;

if( $update ) //Mise à jour du module
{	
	//Récupération de l'identifiant du module
	$module_name = '';
	foreach($_POST as $key => $value)
		if( $value == $LANG['update_module'] )
			$module_name = $key;
	
	$activ_module = isset($_POST[$module_name . 'activ']) ? numeric($_POST[$module_name . 'activ']) : '0';
	
	//Vérification de l'existance du module
	$ckeck_module = $sql->query("SELECT COUNT(*) FROM ".PREFIX."modules WHERE name = '" . securit($module_name) . "'", __LINE__, __FILE__);
	
	//Mise à jour du module
	if( !empty($ckeck_module) )
	{
		//Récupération des infos de config.
		$info_module = @parse_ini_file('../' . $module_name . '/lang/' . $CONFIG['lang'] . '/config.ini');
		
		//Récupération de l'ancienne version du module
		$previous_version = $sql->query("SELECT version FROM ".PREFIX."modules WHERE name = '" . securit($module_name) . "'", __LINE__, __FILE__);
	
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
		
		$filesupdate = array();
		//Récupération des fichiers de mises à jour des différentes versions.
		$dir_db = '../' . urldecode($module_name) . '/db/' . $dir_db_module . '/';
		if( is_dir($dir_db) ) //Si le dossier existe
		{
			$dh = @opendir($dir_db);
			while( !is_bool($file = readdir($dh)) )
			{	
				if( strpos($file, DBTYPE) !== false || strpos($file, '.php') !== false )
				{	
					$array_info = explode('_', $file);
					if( isset($array_info[1]) && version_compare($info_module['version'], $array_info[1], '>=') && version_compare($previous_version, $array_info[1], '<') )
						$filesupdate[$array_info[1]] = $file;					
				}
			}
		}	
		
		//Tri du tableau par odre des mises à jour.
		uksort($filesupdate, 'version_compare');
		
		//Execution des fichiers de mise à jour.	
		foreach($filesupdate as $key => $module_update_name)
		{	
			if( strpos($file, '.php') !== false ) //Parsage fichier php.
				@include_once($dir_db . $module_update_name);
			else //Requêtes sql de mise à jour.		
				$sql->sql_parse($dir_db . $module_update_name, PREFIX);
		}
		
		//Régénération du cache du module si il l'utilise
		if( $info_module['use_cache'] == '1' )
			$cache->generate_module_file($module_name);

		//Insertion du modules dans la bdd => module mis à jour.
		$sql->query_inject("UPDATE ".PREFIX."modules SET version = '" . $info_module['version'] . "' WHERE name = '" . $module_name . "'", __LINE__, __FILE__);
		
		//Génération du cache des modules
		$cache->generate_file('modules');
		
		//Mise à jour du .htaccess pour le mod rewrite, si il est actif et que le module le supporte
		if( $CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']) )
		{
			//Régénération du htaccess.
			$cache->generate_htaccess(); 
		}
		
		header('location:' . HOST . SCRIPT);	
		exit;		
	}
	else
	{
		header('location:' . HOST . DIR . '/admin/admin_modules_update.php?error=incomplete#errorh');
		exit;
	}
}			
elseif( !empty($_FILES['upload_module']['name']) ) //Upload et décompression de l'archive Zip/Tar
{
	$ext_name = strrchr($_FILES['upload_module']['name'], '.');
	$module_name = str_replace($ext_name, '', $_FILES['upload_module']['name']);
	
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../';
	if( !is_writable($dir) )
		@chmod($dir, 0755);
	if( !is_writable($dir . $module_name) )
		@chmod($dir . $module_name, 0755);

	@clearstatcache();
	$error = '';
	if( is_writable($dir) && is_writable($dir . $module_name) ) //Dossier en écriture, upload possible
	{
		if( !is_dir('../' . $_FILES['upload_module']['name']) )
		{
			include_once('../includes/upload.class.php');
			$upload = new Upload($dir);
			if( $upload->upload_file('upload_module', '`([a-z0-9_-])+\.(gzip|zip)+`i') )
			{					
				$archive_path = '../' . $upload->filename['upload_module'];
				//Place à la décompression.
				if( $upload->extension['upload_module'] == 'gzip' )
				{
					include_once('../includes/pcltar.lib.php');
					if( !$zip_files = PclTarExtract($upload->filename['upload_module'], '../') )
						$error = $upload->error;
				}
				elseif( $upload->extension['upload_module'] == 'zip' )
				{
					include_once('../includes/pclzip.lib.php');
					$zip = new PclZip($archive_path);
					if( !$zip_files = $zip->extract(PCLZIP_OPT_PATH, '../', PCLZIP_OPT_SET_CHMOD, 0666) )
						$error = $upload->error;
				}
				else
					$error = 'e_upload_invalid_format';
				
				//Suppression de l'archive désormais inutile.
				if( !@unlink($archive_path) )
					$error = 'e_unlink_disabled';
			}
			else
				$error = 'e_upload_error';
		}
		else
			$error = 'e_upload_already_exist';
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '?error=' . $error : '';
	header('location:' . HOST . SCRIPT . $error);	
	exit;
}
else
{			
	$template->set_filenames(array(
		'admin_modules_update' => '../templates/' . $CONFIG['theme'] . '/admin/admin_modules_update.tpl'
	));

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled');
	if( in_array($get_error, $array_error) )
		$errorh->error_handler($LANG[$get_error], E_USER_WARNING);
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	//Modules mis à jour
	$updated_modules = array();
	$result = $sql->query_while("SELECT name, version
	FROM ".PREFIX."modules
	WHERE activ = 1", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
		$updated_modules[$row['name']] = $row['version'];
	$sql->close($result);
	
	//Vérification des mises à jour du noyau  et des modules sur le site officiel.
	$get_info_update = @file_get_contents_emulate('http://www.phpboost.com/phpboost/updates.txt');
	$check_modules_update = false;
	$modules_update = array();
	if( !empty($get_info_update) )
	{	
		$array_infos = explode("\n", $get_info_update);
		foreach($array_infos as $key => $value)
		{
			$array_infos_modules = explode(':', $value);
			$name = $array_infos_modules[0];
			$version = $array_infos_modules[1];
			
			//Nouvelle version du module.
			if( isset($modules_config[$name]['version']) && $modules_config[$name]['version'] != $version )
				$modules_update[$name] = $version;
		}	
		
		if( count($modules_update) > 0 )
		{
			$check_modules_update = true;
			$l_modules_update = $LANG['module_update_available'];
		}
		else
			$l_modules_update = $LANG['no_module_update_available'];
	}
	else
		$l_modules_update = $LANG['unknow_update'];
	
	//Modules disponibles
	$root = '../';
	$i = 0;
	if( is_dir($root) ) //Si le dossier existe
	{
		$dh = @opendir($root);
		while( !is_bool($dir = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $dir) && array_key_exists($dir, $updated_modules) )
			{
				//Désormais on vérifie que le fichier de configuration est présent.
				if( is_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini') )
				{
					//Récupération des infos de config.
					$info_module = @parse_ini_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini');					
					if( $info_module['version'] != $updated_modules[$dir] )
					{
						$l_tables = ($info_module['sql_table'] > 1) ? $LANG['tables'] : $LANG['table'];
						$template->assign_block_vars('available', array(
							'ID' => $dir,
							'NAME' => ucfirst($info_module['name']),
							'ICON' => $dir,
							'VERSION' => $info_module['version'],
							'PREVIOUS_VERSION' => $updated_modules[$dir],
							'AUTHOR' => (!empty($info_module['author_mail']) ? '<a href="mailto:' . $info_module['author_mail'] . '">' . $info_module['author'] . '</a>' : $info_module['author']),
							'AUTHOR_WEBSITE' => (!empty($info_module['author_link']) ? '<a href="' . $info_module['author_link'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="" /></a>' : ''),
							'DESC' => $info_module['info'],
							'COMPAT' => $info_module['compatibility'],
							'USE_SQL' => (($info_module['sql_table'] > 0) ? $LANG['yes'] : $LANG['no']),
							'SQL_TABLE' => (($info_module['sql_table'] > 0) ? '(' . $info_module['sql_table'] . ' ' . $l_tables . ')' : ''),
							'USE_CACHE' => ($info_module['cache'] ? $LANG['yes'] : $LANG['no']),
							'ALTERNATIVE_CSS' => ($info_module['css'] ? $LANG['yes'] : $LANG['no']),	
							'STARTEABLE_PAGE' => ($info_module['starteable_page'] ? $LANG['yes'] : $LANG['no'])
						));
						$i++;
					}
				}				
			}
		}	
		closedir($dh); //On ferme le dossier
	}
	
	$template->assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'WARNING_MODULES' => ($check_modules_update) ? ' error_warning' : '',
		'UPDATE_MODULES_AVAILABLE' => ($check_modules_update) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/admin/update_available.png" alt="" class="valign_middle" />' : '',
		'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
		'L_ADD_MODULES' => $LANG['add_modules'],
		'L_UPDATE_MODULES' => $LANG['update_modules'],
		'L_UPLOAD_MODULE' => $LANG['upload_module'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
		'L_NAME' => $LANG['name'],
		'L_NEW_VERSION' => $LANG['new_version'],
		'L_INSTALLED_VERSION' => $LANG['installed_version'],
		'L_DESC' => $LANG['description'],
		'L_AUTHOR' => $LANG['author'],
		'L_COMPAT' => $LANG['compat'],
		'L_USE_SQL' => $LANG['use_sql'],
		'L_ADMIN' => $LANG['administration'],
		'L_USE_CACHE' => $LANG['use_cache'],
		'L_ALTERNATIVE_CSS' => $LANG['alternative_css'],
		'L_STARTEABLE_PAGE' => $LANG['starteable_page'],
		'L_MODULES_AVAILABLE' => $LANG['modules_available'],
		'L_NO_MODULES_AVAILABLE' => $LANG['no_modules_available'],
		'L_UPDATE' => $LANG['update_module'],
		'L_UPDATE_AVAILABLE' => $LANG['update_available'],
		'L_MODULES_UPDATE' => $l_modules_update	
	));
	
	//Listing des modules mis à jour.
	foreach($modules_update as $name => $version)
	{
		$template->assign_block_vars('update_modules_available', array(
			'ID' => $name,
			'NAME' => $modules_config[$name]['name'],
			'VERSION' => $version
		));
	}

	if( $i == 0 )
		$template->assign_block_vars('no_module', array(
		));
	else
		$template->assign_block_vars('modules_available', array(
		));
	
	$template->pparse('admin_modules_update'); 
}

require_once('../includes/admin_footer.php');

?>