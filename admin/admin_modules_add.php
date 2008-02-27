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

$install = !empty($_GET['install']) ? true : false;

if( $install ) //Installation du module
{	
	//Récupération de l'identifiant du module
	$module_name = '';
	foreach($_POST as $key => $value)
		if( $value == $LANG['install'] )
			$module_name = str_replace('module_', '', $key);

	$activ_module = isset($_POST[$module_name . 'activ']) ? numeric($_POST[$module_name . 'activ']) : '0';
	
	//Vérification de l'unicité du module
	$ckeck_module = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."modules WHERE name = '" . securit($module_name) . "'", __LINE__, __FILE__);
	
	//Installation du module
	if( !empty($module_name) && empty($ckeck_module) )
	{
		//Récupération des infos de config.
		$info_module = load_ini_file('../' . $module_name . '/lang/', $CONFIG['lang']);
		
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
			
		//Parsage du fichier sql.
		if( file_exists('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.' . DBTYPE . '.sql') )
			$Sql->Sql_parse('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.' . DBTYPE . '.sql', PREFIX);
		
		if( file_exists('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.php') )
			@include_once('../' . $module_name . '/db/' . $dir_db_module . '/' . $module_name . '.php');
		
		//Génération du cache du module si il l'utilise
		if( !empty($info_module['cache']) )
			$Cache->Generate_module_file($module_name);

		//Insertion du modules dans la bdd => module installé.
		$Sql->Query_inject("INSERT INTO ".PREFIX."modules (name, version, auth, activ) VALUES ('" . securit($module_name) . "', '" . securit($info_module['version']) . "', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', '" . $activ_module . "')", __LINE__, __FILE__);
		
		//Génération du cache des modules
		$Cache->Generate_file('modules');
		$Cache->Generate_file('modules_mini');
		
		//Mise à jour du .htaccess pour le mod rewrite, si il est actif et que le module le supporte
		if( $CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']) )
		{
			//Régénération du htaccess.
			$Cache->Generate_htaccess(); 				
		}
		
		redirect(HOST . SCRIPT);	
	}
	else
		redirect(HOST . DIR . '/admin/admin_modules_add.php?error=incomplete#errorh');
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
		$ckeck_module = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."modules WHERE name = '" . securit($module_name) . "'", __LINE__, __FILE__);
		if( empty($ckeck_module) && !is_dir('../' . $module_name) )
		{
			include_once('../includes/upload.class.php');
			$Upload = new Upload($dir);
			if( $Upload->Upload_file('upload_module', '`([a-z0-9_-])+\.(gzip|zip)+`i') )
			{					
				$archive_path = '../' . $Upload->filename['upload_module'];
				//Place à la décompression.
				if( $Upload->extension['upload_module'] == 'gzip' )
				{
					include_once('../includes/pcltar.lib.php');
					if( !$zip_files = PclTarExtract($Upload->filename['upload_module'], '../') )
						$error = $Upload->error;
				}
				elseif( $Upload->extension['upload_module'] == 'zip' )
				{
					include_once('../includes/pclzip.lib.php');
					$Zip = new PclZip($archive_path);
					if( !$zip_files = $Zip->extract(PCLZIP_OPT_PATH, '../', PCLZIP_OPT_SET_CHMOD, 0666) )
						$error = $Upload->error;
				}
				else
					$error = 'e_upload_invalid_format';
				
				//Suppression de l'archive désormais inutile.
				if( !@unlink($archive_path) )
					$error = 'unlink_disabled';
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
	redirect(HOST . SCRIPT . $error);	
}
else
{			
	$Template->Set_filenames(array(
		'admin_modules_add' => '../templates/' . $CONFIG['theme'] . '/admin/admin_modules_add.tpl'
	));

	$Template->Assign_vars(array(
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'L_MODULES_MANAGEMENT' => $LANG['modules_management'],
		'L_ADD_MODULES' => $LANG['add_modules'],
		'L_UPDATE_MODULES' => $LANG['update_modules'],
		'L_UPLOAD_MODULE' => $LANG['upload_module'],
		'L_EXPLAIN_ARCHIVE_UPLOAD' => $LANG['explain_archive_upload'],
		'L_UPLOAD' => $LANG['upload'],
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
		'L_UPDATE' => $LANG['update'],
		'L_MODULES_AVAILABLE' => $LANG['modules_available'],
		'L_NO_MODULES_AVAILABLE' => $LANG['no_modules_available'],
		'L_INSTALL' => $LANG['install']
	));

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled');
	if( in_array($get_error, $array_error) )
		$Errorh->Error_handler($LANG[$get_error], E_USER_WARNING);
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	//Modules installé
	$i = 0;
	$installed_modules = array();
	$result = $Sql->Query_while("SELECT id, name
	FROM ".PREFIX."modules
	WHERE activ = 1", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
		$installed_modules[] = $row['name'];
	$Sql->Close($result);
	
	//Modules disponibles
	$root = '../';
	$i = 0;
	if( is_dir($root) ) //Si le dossier existe
	{
		$dh = @opendir($root);
		while( !is_bool($dir = readdir($dh)) )
		{	
			//Si c'est un repertoire, on affiche.
			if( !preg_match('`\.`', $dir) && !in_array($dir, $installed_modules) )
			{
				//Désormais on vérifie que le fichier de configuration est présent.
				if( is_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini') )
				{
					//Récupération des infos de config.
					$info_module = load_ini_file($root . $dir . '/lang/', $CONFIG['lang']);
					if( isset($info_module['info']) )
					{
						$l_tables = ($info_module['sql_table'] > 1) ? $LANG['tables'] : $LANG['table'];
						$Template->Assign_block_vars('available', array(
							'ID' => $dir,
							'NAME' => ucfirst($info_module['name']),
							'ICON' => $dir,
							'VERSION' => $info_module['version'],
							'AUTHOR' => (!empty($info_module['author_mail']) ? '<a href="mailto:' . $info_module['author_mail'] . '">' . $info_module['author'] . '</a>' : $info_module['author']),
							'AUTHOR_WEBSITE' => (!empty($info_module['author_link']) ? '<a href="' . $info_module['author_link'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/user_web.png" alt="" /></a>' : ''),
							'DESC' => $info_module['info'],
							'COMPAT' => $info_module['compatibility'],
							'USE_SQL' => (($info_module['sql_table'] > 0) ? $LANG['yes'] : $LANG['no']),
							'SQL_TABLE' => (($info_module['sql_table'] > 0) ? '(' . $info_module['sql_table'] . ' ' . $l_tables . ')' : ''),
							'USE_CACHE' => ($info_module['cache'] ? $LANG['yes'] : $LANG['no']),
							'ALTERNATIVE_CSS' => ($info_module['css'] ? $LANG['yes'] : $LANG['no']),	
							'STARTEABLE_PAGE' => ($info_module['starteable_page'] ? $LANG['yes'] : $LANG['no']),
							'ACTIV_ENABLED' => ($row['activ'] == 1 ? 'checked="checked"' : ''),
							'ACTIV_DISABLED' => ($row['activ'] == 0 ? 'checked="checked"' : '')
						));
						$i++;
					}
				}				
			}
		}	
		closedir($dh); //On ferme le dossier
	}
	
	if( $i == 0 )
		$Template->Assign_block_vars('no_module', array(
		));
	else
		$Template->Assign_block_vars('modules_available', array(
		));
	
	$Template->Pparse('admin_modules_add'); 
}

require_once('../includes/admin_footer.php');

?>