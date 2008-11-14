<?php
/*##################################################
 *                         admin_modules_update.php
 *                            -------------------
 *   begin                : January 31, 2007
 *   copyright            : (C) 2007 Viarre Régis
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$update = !empty($_GET['update']) ? true : false;

if( $update ) //Mise à jour du module
{	
	//Récupération de l'identifiant du module
	$module_name = '';
	foreach($_POST as $key => $value)
		if( $value == $LANG['update_module'] )
			$module_name = $key;
	
	$activ_module = retrieve(POST, $module_name . 'activ', 0);
	
	//Vérification de l'existance du module
	$ckeck_module = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."modules WHERE name = '" . strprotect($module_name) . "'", __LINE__, __FILE__);
	
	//Mise à jour du module
	if( !empty($ckeck_module) )
	{
		//Récupération des infos de config.
		$info_module = load_ini_file('../' . $module_name . '/lang/', $CONFIG['lang']);
		
		//Récupération de l'ancienne version du module
		$previous_version = $Sql->query("SELECT version FROM ".PREFIX."modules WHERE name = '" . strprotect($module_name) . "'", __LINE__, __FILE__);
	
		//Si le dossier de base de données de la LANG n'existe pas on prend le suivant exisant.
		$dir_db_module = $CONFIG['lang'];
		$dir = '../' . $module_name . '/db';
		if( !is_dir($dir . '/' . $dir_db_module) )
		{	
			$dh = @opendir($dir);
			while( !is_bool($dir_db = @readdir($dh)) )
			{	
				if( strpos($dir_db, '.') === false )
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
				$Sql->parse($dir_db . $module_update_name, PREFIX);
		}
		
		//Régénération du cache du module si il l'utilise
		$Cache->generate_module_file($module_name, NO_FATAL_ERROR_CACHE);

		//Insertion du modules dans la bdd => module mis à jour.
		$Sql->query_inject("UPDATE ".PREFIX."modules SET version = '" . $info_module['version'] . "' WHERE name = '" . $module_name . "'", __LINE__, __FILE__);
		
		//Génération du cache des modules
		$Cache->Generate_file('modules');
		$Cache->Generate_file('menus');
		$Cache->Generate_file('css');
		
		//Mise à jour du .htaccess pour le mod rewrite, si il est actif et que le module le supporte
		if( $CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']) )
			$Cache->Generate_file('htaccess'); //Régénération du htaccess.	
		
		redirect(HOST . SCRIPT);	
	}
	else
		redirect(HOST . DIR . '/admin/admin_modules_update.php?error=incomplete#errorh');
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
			include_once('../kernel/framework/io/upload.class.php');
			$Upload = new Upload($dir);
			if( $Upload->file('upload_module', '`([a-z0-9()_-])+\.(gzip|zip)+$`i') )
			{					
				$archive_path = '../' . $Upload->filename['upload_module'];
				//Place à la décompression.
				if( $Upload->extension['upload_module'] == 'gzip' )
				{
					include_once('../kernel/framework/lib/pcl/pcltar.lib.php');
					if( !$zip_files = PclTarExtract($Upload->filename['upload_module'], '../') )
						$error = $Upload->error;
				}
				elseif( $Upload->extension['upload_module'] == 'zip' )
				{
					include_once('../kernel/framework/lib/pcl/pclzip.lib.php');
					$Zip = new PclZip($archive_path);
					if( !$zip_files = $Zip->extract(PCLZIP_OPT_PATH, '../', PCLZIP_OPT_SET_CHMOD, 0666) )
						$error = $Upload->error;
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
	redirect(HOST . SCRIPT . $error);	
}
else
{			
	$Template->set_filenames(array(
		'admin_modules_update'=> 'admin/admin_modules_update.tpl'
	));

    {   // Intégration du système d'updates des modules avec celui du site
        require_once('../kernel/framework/core/updates.class.php');
        $updates_availables = 0;

        if( phpversion() > PHP_MIN_VERSION_UPDATES )
        {
            // Retrieves all the update alerts from the database
            import('events/administrator_alert_service');
            import('core/application');
            $update_alerts = AdministratorAlertService::find_by_criteria(null, 'updates');
            
            $updates = array();
            $update_type = 'module';
            foreach( $update_alerts as $update_alert )
            {
                // Builds the asked updates (kernel updates, module updates, theme updates or all of them)
                $update = unserialize($update_alert->get_properties());
                if( $update_type == '' || $update->get_type() == $update_type )
                    $updates[] = $update;
            }

            foreach( $updates as $update )
            {
                switch( $update->get_priority() )
                {
                    case ADMIN_ALERT_VERY_HIGH_PRIORITY:
                        $priority = 'priority_very_high';
                        break;
                    case ADMIN_ALERT_HIGH_PRIORITY:
                        $priority = 'priority_high';
                        break;
                    case ADMIN_ALERT_MEDIUM_PRIORITY:
                        $priority = 'priority_medium';
                        break;
                    default:
                        $priority = 'priority_low';
                        break;
                }
                
                $short_description = $update->get_description();
                $maxlength = 300;
                $length = strlen($short_description) > $maxlength ?  $maxlength + strpos(substr($short_description, $maxlength), ' ') : 0;
                $length = $length > ($maxlength * 1.1) ? $maxlength : $length;
                
                $Template->assign_block_vars('apps', array(
                    'type' => $update->get_type(),
                    'name' => $update->get_name(),
                    'version' => $update->get_version(),
                    'short_description' => ($length > 0 ? substr($short_description, 0, $length) . '...' : $short_description),
                    'identifier' => $update->get_identifier(),
                    'L_PRIORITY' => $LANG[$priority],
                    'priority_css_class' => 'row_' . $priority,
                    'download_url' => $update->get_download_url(),
                    'update_url' => $update->get_update_url()
                ));
            }
            
            if( $updates_availables = (count($updates) > 0) )
            {
                $Template->assign_vars(array(
                    'L_UPDATES_ARE_AVAILABLE' => $LANG['updates_are_available'],
                    'L_AVAILABLES_UPDATES' => $LANG['availables_updates'],
                    'L_TYPE' => $LANG['type'],
                    'L_DESCRIPTION' => $LANG['description'],
                    'L_PRIORITY' => $LANG['priority'],
                    'L_UPDATE_DOWNLOAD' => $LANG['app_update__download'],
                    'L_NAME' => $LANG['name'],
                    'L_VERSION' => $LANG['version'],
                    'L_MORE_DETAILS' => $LANG['more_details'],
                    'L_DETAILS' => $LANG['details'],
                    'L_DOWNLOAD_PACK' => $LANG['app_update__download_pack'],
                    'L_DOWNLOAD_THE_COMPLETE_PACK' => $LANG['download_the_complete_pack'],
                    'L_UPDATE_PACK' => $LANG['app_update__update_pack'],
                    'L_DOWNLOAD_THE_UPDATE_PACK' => $LANG['download_the_update_pack'],
                    'C_ALL' => $update_type == ''
                ));
                
            }
            else
            {
                $Template->assign_vars(array('L_NO_AVAILABLES_UPDATES' => $LANG['no_availables_updates']));
            }
        }
        else
        {
            $Template->assign_vars(array(
                'L_INCOMPATIBLE_PHP_VERSION' => sprintf($LANG['incompatible_php_version'], PHP_MIN_VERSION_UPDATES),
                'C_INCOMPATIBLE_PHP_VERSION' => true,
            ));
        }

        $Template->assign_vars(array(
            'L_WEBSITE_UPDATES' => $LANG['website_updates'],
            'L_KERNEL' => $LANG['kernel'],
            'L_MODULES' => $LANG['modules'],
            'L_THEMES' => $LANG['themes'],
            'C_UPDATES' => $updates_availables
        ));
    }

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled');
	if( in_array($get_error, $array_error) )
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	if( $get_error == 'incomplete' )
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
		
	//Modules mis à jour
	$updated_modules = array();
	$result = $Sql->query_while("SELECT name, version
	FROM ".PREFIX."modules
	WHERE activ = 1", __LINE__, __FILE__);
	
	while( $row = $Sql->fetch_assoc($result) )
		$updated_modules[$row['name']] = $row['version'];
	
	$Sql->query_close($result);
	
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
			if( strpos($dir, '.') === false && array_key_exists($dir, $updated_modules) )
			{
				//Désormais on vérifie que le fichier de configuration est présent.
				if( is_file($root . $dir . '/lang/' . $CONFIG['lang'] . '/config.ini') )
				{
					//Récupération des infos de config.
					$info_module = load_ini_file($root . $dir . '/lang/', $CONFIG['lang']);					
					if( is_array($info_module) && $info_module['version'] != $updated_modules[$dir] )
					{
						$l_tables = ($info_module['sql_table'] > 1) ? $LANG['tables'] : $LANG['table'];
						$Template->assign_block_vars('available', array(
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
	
	$Template->assign_vars(array(
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
		$Template->assign_block_vars('update_modules_available', array(
			'ID' => $name,
			'NAME' => $modules_config[$name]['name'],
			'VERSION' => $version
		));
	}

	if( $i == 0 )
		$Template->assign_vars( array(
			'C_NO_MODULE' => true,
		));
	else
		$Template->assign_vars( array(
			'C_MODULES_AVAILABLE' => true,
		));
	
	$Template->pparse('admin_modules_update'); 
}

require_once('../admin/admin_footer.php');

?>