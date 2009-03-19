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
define('MODULE_UNINSTALLED', 0);
define('UNEXISTING_MODULE', 1);
define('MODULE_ALREADY_INSTALLED', 2);
define('CONFIG_CONFLICT', 3);
define('NOT_INSTALLED_MODULE', 4);
define('MODULE_FILES_COULD_NOT_BE_DROPPED', 5);

define('GENERATE_CACHE_AFTER_THE_OPERATION', true);
define('DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION', false);

//Class
class PackagesManager
{
	/*static*/ function install_module($module_identifier, $enable_module = true, $generate_cache = GENERATE_CACHE_AFTER_THE_OPERATION)
	{
		global $Cache, $Sql, $CONFIG, $MODULES;
		
		if (empty($module_identifier) || !is_dir(PATH_TO_ROOT . '/' . $module_identifier))
			return UNEXISTING_MODULE;
		
		//Vérification de l'unicité du module
		$check_module = (int)$Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MODULES . " WHERE name = '" . strprotect($module_identifier) . "'", __LINE__, __FILE__);
		if ($check_module > 0)
			return MODULE_ALREADY_INSTALLED;
		
		//Récupération des infos de config.
		$info_module = load_ini_file(PATH_TO_ROOT . '/' . $module_identifier . '/lang/', get_ulang());
		if (empty($info_module))
			return UNEXISTING_MODULE;
		
		//Si le dossier de base de données de la langue n'existe pas on prend le suivant existant.
		$dir_db_module = get_ulang();
		$dir = PATH_TO_ROOT . '/' . $module_identifier . '/db';
		if (!is_dir($dir . '/' . $dir_db_module))
		{
			import('io/filesystem/folder');
			$db_scripts_folder = new Folder($dir);
			
			$existing_db_files = $db_scripts_folder->get_folders('`[a-z_-]+`i');
			$dir_db_module = count($existing_db_files) > 0 ? $existing_db_files[0]->get_name() : '';
		}
			
		//Insertion de la configuration du module.
		$config = get_ini_config(PATH_TO_ROOT . '/' . $module_identifier . '/lang/', get_ulang()); //Récupération des infos de config.
		
		if (!empty($config))
		{			
			$check_config = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_CONFIGS . " WHERE name = '" . $module_identifier . "'", __LINE__, __FILE__);
			if (empty($check_config))
				$Sql->query_inject("INSERT INTO " . DB_TABLE_CONFIGS . " (name, value) VALUES ('" . $module_identifier . "', '" . addslashes($config) . "');", __LINE__, __FILE__);
			else
				return CONFIG_CONFLICT;
		}
		
		//Parsage du fichier sql.
		$sql_file = PATH_TO_ROOT . '/' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.' . DBTYPE . '.sql';
		if (file_exists($sql_file))
			$Sql->parse($sql_file, PREFIX);
		
		$module_identifier = strprotect($module_identifier);
		
		//Installation du mini module s'il existe
        import('core/menu_service');
		MenuService::add_mini_module($module_identifier);

		//Insertion du modules dans la bdd => module installé.
		$Sql->query_inject("INSERT INTO " . DB_TABLE_MODULES . " (name, version, auth, activ) VALUES ('" . $module_identifier . "', '" . addslashes($info_module['version']) . "', 'a:4:{s:3:\"r-1\";i:1;s:2:\"r0\";i:1;s:2:\"r1\";i:1;s:2:\"r2\";i:1;}', '" . ((int)$enable_module) . "')", __LINE__, __FILE__);
		
		//Parsage du fichier php.
		$php_file = PATH_TO_ROOT . '/' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.php';
		if (file_exists($php_file)) {
			if (!DEBUG) {
				@include_once($php_file);
			} else {
				include_once($php_file);				
			}
		}
		
		//Génération du cache des modules
		if ($generate_cache)
		{
			$Cache->Generate_file('modules');
			$Cache->load('modules', RELOAD_CACHE);
			$Cache->Generate_file('css');
            MenuService::generate_cache();
		
			//Mise à jour du .htaccess pour le mod rewrite, si il est actif et que le module le supporte
			if ($CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']))
				$Cache->Generate_file('htaccess'); //Régénération du htaccess.
		}
		
		//Génération du cache du module si il l'utilise
		$Cache->generate_module_file($module_identifier, NO_FATAL_ERROR_CACHE);
		
		return MODULE_INSTALLED;
	}
	
	//Désinstallation d'un module
	/*static*/ function uninstall_module($module_id, $drop_files)
	{
		global $Cache, $Sql, $CONFIG, $MODULES;
		//Suppression du modules dans la bdd => module désinstallé.
		$module_name = $Sql->query("SELECT name FROM " . DB_TABLE_MODULES . " WHERE id = '" . $module_id . "'", __LINE__, __FILE__);
		
		//Désinstallation du module
		if (!empty($module_id) && !empty($module_name))
		{
			$Sql->query_inject("DELETE FROM " . DB_TABLE_MODULES . " WHERE id = '" . $module_id . "'", __LINE__, __FILE__);
			
			//Récupération des infos de config.
			$info_module = load_ini_file(PATH_TO_ROOT . '/' . $module_name . '/lang/', get_ulang());
			
			//Suppression du fichier cache
			$Cache->delete_file($module_name);
			
			//Suppression des commentaires associés.
			if (!empty($info_module['com']))
				$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = '" . addslashes($info_module['com']) . "'", __LINE__, __FILE__);
			
			//Suppression de la configuration.
			$config = get_ini_config(PATH_TO_ROOT . '/news/lang/', get_ulang()); //Récupération des infos de config.
			if (!empty($config))
				$Sql->query_inject("DELETE FROM " . DB_TABLE_CONFIGS . " WHERE name = '" . addslashes($module_name) . "'", __LINE__, __FILE__);
			
			//Suppression du module mini.
            import('core/menu_service');
            MenuService::delete_mini_module($module_name);
            	
			$dir_db_module = get_ulang();
			$dir = PATH_TO_ROOT . '/' . $module_name . '/db';

			//Si le dossier de base de données de la LANG n'existe pas on prend le suivant exisant.
			import('io/filesystem/folder');
			$folder_path = new Folder($dir . '/' . $dir_db_module);
			foreach ($folder_path->get_folders('`^[a-z_]+$`i') as $dir)
			{	
				$dir_db_module = $dir->get_name();
				break;
			}
		
			if (file_exists(PATH_TO_ROOT . '/' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.' . DBTYPE . '.sql')) //Parsage du fichier sql de désinstallation.
				$Sql->parse(PATH_TO_ROOT . '/' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.' . DBTYPE . '.sql', PREFIX);
			
			if (file_exists(PATH_TO_ROOT . '/' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.php')) //Parsage fichier php de désinstallation.
				@include_once(PATH_TO_ROOT . '/' . $module_name . '/db/' . $dir_db_module . '/uninstall_' . $module_name . '.php');
			
			$Cache->Generate_file('modules');
			$Cache->Generate_file('css');
            MenuService::generate_cache();

			import('content/syndication/feed'); //Régénération des feeds.
			Feed::clear_cache();
		
			//Mise à jour du .htaccess pour le mod rewrite, si il est actif et que le module le supporte
			if ($CONFIG['rewrite'] == 1 && !empty($info_module['url_rewrite']))
				$Cache->Generate_file('htaccess'); //Régénération du htaccess.
			
			//Suppression des fichiers du module
			if ($drop_files)
			{
				if (!delete_directory(PATH_TO_ROOT . '/' . $module_name, PATH_TO_ROOT . '/' . $module_name))
					return MODULE_FILES_COULD_NOT_BE_DROPPED;
			}
			
			return MODULE_UNINSTALLED;
		}
		else
			return NOT_INSTALLED_MODULE;
	}
}

?>