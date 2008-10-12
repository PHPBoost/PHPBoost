<?php

/*##################################################
*                          packages_manager.class.php
*                            -------------------
*   begin                : October 12, 2008
*   copyright            :(C) 2008 Benoit Sautel
*   email                : ben.popeye@phpboost.com
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
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
###################################################*/

//Constants
define('MODULE_INSTALLED', 0);
define('UNEXISTING_MODULE', 1);
define('MODULE_ALREADY_INSTALLED', 2);
define('CONFIG_CONFLICT', 3);

define('GENERATE_CACHE_AFTER_THE_OPERATION', true);
define('DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION', false);

//Class
class PackagesManager
{
	/*static*/ function install_module($module_identifier, $generate_cache = GENERATE_CACHE_AFTER_THE_OPERATION)
	{
		global $Cache, $Sql, $CONFIG;
		
		if( empty($module_identifier) || !is_dir(PATH_TO_ROOT . '/' . $module_identifier) )
			return UNEXISTING_MODULE;
		
		//Vérification de l'unicité du module
		$check_module = (int)$Sql->query("SELECT COUNT(*) FROM ".PREFIX."modules WHERE name = '" . strprotect($module_identifier) . "'", __LINE__, __FILE__);
		if( $check_module > 0 )
			return MODULE_ALREADY_INSTALLED;
		
		//Récupération des infos de config.
		$info_module = load_ini_file('../' . $module_identifier . '/lang/', $CONFIG['lang']);
		if( empty($info_module) )
			return UNEXISTING_MODULE;
		
		//Si le dossier de base de données de la langue n'existe pas on prend le suivant existant.
		$dir_db_module = $CONFIG['lang'];
		$dir = '../' . $module_identifier . '/db';
		if( !is_dir($dir . '/' . $dir_db_module) )
		{
			require_once(PATH_TO_ROOT . '/kernel/framework/io/folder.class.php');
			$db_scripts_folder = new Folder($dir);
			
			$existing_db_files = $db_scripts_folder->get_folders('`[a-z_-]+`i');
			$dir_db_module = count($existing_db_files) > 0 ? $existing_db_files[0]->get_name() : '';
		}
			
		//Insertion de la configuration du module.
		$config = get_ini_config('../' . $module_identifier . '/lang/', $CONFIG['lang']); //Récupération des infos de config.
		if( !empty($config) )
		{	
			$config = trim(str_replace('config=', '', $config), '"');
			
			$check_config = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."configs WHERE name = '" . $module_identifier . "'", __LINE__, __FILE__);
			if( empty($check_config) ) 
				$Sql->query_inject("INSERT INTO ".PREFIX."configs (name, value) VALUES ('" . $module_identifier . "', '" . addslashes($config) . "');", __LINE__, __FILE__);
			else
				return CONFIG_CONFLICT;
		}
		
		//Parsage du fichier sql.
		if( file_exists('../' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.' . DBTYPE . '.sql') )
			$Sql->parse('../' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.' . DBTYPE . '.sql', PREFIX);
		
		//Parsage du fichier php.
		if( file_exists('../' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.php') )
			@include_once('../' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.php');
		
		//Génération du cache du module si il l'utilise
		$Cache->generate_module_file($module_identifier, NO_FATAL_ERROR_CACHE);
		
		$module_identifier = strprotect($module_identifier);
		//Installation du mini module s'il existe
		if( !empty($info_module['mini_module']) )
		{
			$array_menus = parse_ini_array($info_module['mini_module']);
			$links = '';
			foreach($array_menus as $path => $location)
			{
				$path = addslashes($path);
				$module_mini_path = '../' . $module_identifier . '/' . $path;
				if( file_exists($module_mini_path) )
				{
					$location = addslashes($location);
					$class = $Sql->query("SELECT MAX(class) FROM ".PREFIX."modules_mini WHERE location = '" .  $location . "'", __LINE__, __FILE__) + 1;
					$Sql->query_inject("INSERT INTO ".PREFIX."modules_mini (class, name, contents, location, auth, activ, added, use_tpl) VALUES ('" . $class . "', '" . $module_identifier . "', '" . $path . "', '" . $location . "', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', 1, 0, 0)", __LINE__, __FILE__);
				}
			}
		}

		//Insertion du modules dans la bdd => module installé.
		$Sql->query_inject("INSERT INTO ".PREFIX."modules (name, version, auth, activ) VALUES ('" . $module_identifier . "', '" . addslashes($info_module['version']) . "', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', '1')", __LINE__, __FILE__);
		
		//Génération du cache des modules
		if( $generate_cache )
		{
			$Cache->Generate_file('modules');
			$Cache->Generate_file('modules_mini');
			$Cache->Generate_file('css');
		
			//Mise à jour du .htaccess pour le mod rewrite, si il est actif et que le module le supporte
			if( $CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']) )
				$Cache->Generate_file('htaccess'); //Régénération du htaccess.
		}
		
		return MODULE_INSTALLED;
	}
}

?>