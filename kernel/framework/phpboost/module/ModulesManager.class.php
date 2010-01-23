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

//Constants
define('MODULE_INSTALLED', 					0);
define('MODULE_UNINSTALLED', 				0);
define('UNEXISTING_MODULE', 				1);
define('MODULE_ALREADY_INSTALLED', 			2);
define('CONFIG_CONFLICT', 					3);
define('NOT_INSTALLED_MODULE', 				4);
define('MODULE_FILES_COULD_NOT_BE_DROPPED',	5);
define('PHP_VERSION_CONFLICT', 				6);

/**
 * @package modules
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class enables you to manages the PHPBoost packages which are nothing else than the modules.
 *
 */
class ModulesManager
{
	const GENERATE_CACHE_AFTER_THE_OPERATION = true;
	const DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION = false;

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (activated or not)
	 */
	public static function get_installed_modules_map()
	{
		return ModulesConfig::load()->get_modules();
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the uninstalled modules (activated or not)
	 */
	public static function get_uninstalled_modules_map()
	{
		$installed = self::get_installed_modules_map();
		return $not_installed;
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (activated or not)
	 * sorted by name
	 */
	public static function get_installed_modules_map_sorted_by_localized_name()
	{
		$modules = ModulesConfig::load()->get_modules();
		usort($modules, array(__CLASS__, 'callback_sort_modules_by_name'));
		return $modules;
	}

	public static function callback_sort_modules_by_name(Module $module1, Module $module2)
	{
		if ($module1->get_configuration()->get_name() > $module2->get_configuration()->get_name())
		{
			return 1;
		}
		return -1;
	}

	/**
	 * @return string[] the names list of the installed modules (activated or not)
	 */
	public static function get_installed_modules_ids_list()
	{
		return array_keys(ModulesConfig::load()->get_modules());
	}

	/**
	 * @desc Returns the requested module
	 * @param $module_id the id of the module
	 * @return Module the requested module
	 */
	public static function get_module($module_id)
	{
		return ModulesConfig::load()->get_module($module_id);
	}

	/**
	 * @desc tells whether the current user is authorized to access the requested module
	 * @param $module_id the id of the module
	 * @return bool true if the current user is authorized to access the requested module
	 */
	public static function check_module_auth($module_id)
	{
		return ModulesConfig::load()->get_module($module_id)->check_auth();
	}

	public static function is_module_installed($module_id)
	{
		return in_array($module_id, self::get_installed_modules_ids_list());
	}

	/**
	 * @static
	 * @desc Installs a module.
	 * @param string $module_identifier Module identifier (name of its folder)
	 * @param bool $enable_module true if you want the module to be enabled, otherwise false.
	 * @return int One of the following error codes:
	 * <ul>
	 * 	<li>MODULE_INSTALLED: the installation succeded</li>
	 * 	<li>MODULE_ALREADY_INSTALLED: the module is already installed</li>
	 * 	<li>UNEXISTING_MODULE: the module you want to install doesn't exist</li>
	 * 	<li>PHP_VERSION_CONFLICT: the server PHP version is two old to be able to run the module code (config set in the config.ini module file)</li>
	 * 	<li>CONFIG_CONFLICT: the configuration field is already used</i>
	 * </ul>
	 */
	public static function install_module($module_identifier, $enable_module = true, $generate_cache = true)
	{
		self::update_class_list();

		global $Cache, $Sql, $CONFIG;

		if (empty($module_identifier) || !is_dir(PATH_TO_ROOT . '/' . $module_identifier))
		{
			return UNEXISTING_MODULE;
		}

		if (self::is_module_installed($module_identifier))
		{
			return MODULE_ALREADY_INSTALLED;
		}

		$authorizations = array('r-1' => 1, 'r0' => 1, 'r1' => 1);
		$module = new Module($module_identifier, $enable_module, $authorizations);
		$configuration = $module->get_configuration();

		$phpversion = phpversion();
		if (strpos(phpversion(), '-') !== FALSE)
		{
			$phpversion = substr($phpversion, 0, strpos(phpversion(), '-'));
		}
		if (version_compare($phpversion, $configuration->get_php_version(), 'lt'))
		{
			return PHP_VERSION_CONFLICT;
		}

		self::execute_module_installation($module_identifier);
		// @deprecated
		//Insertion de la configuration du module.
		$config = get_ini_config(PATH_TO_ROOT . '/' . $module_identifier . '/lang/', get_ulang()); //Récupération des infos de config.

		if (!empty($config))
		{
			$check_config = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_CONFIGS . " WHERE name = '" . $module_identifier . "'", __LINE__, __FILE__);
			if (empty($check_config))
			{
				$Sql->query_inject("INSERT INTO " . DB_TABLE_CONFIGS . " (name, value) VALUES ('" . $module_identifier . "', '" . addslashes($config) . "');", __LINE__, __FILE__);
			}
			else
			{
				return CONFIG_CONFLICT;
			}
		}

		//Si le dossier de base de données de la langue n'existe pas on prend le suivant existant.
		$dir_db_module = get_ulang();
		$dir = PATH_TO_ROOT . '/' . $module_identifier . '/db';
		$db_scripts_folder = new Folder($dir);
		if ($db_scripts_folder->exists())
		{
			if (!file_exists($dir . '/' . $dir_db_module))
			{
				$existing_db_files = $db_scripts_folder->get_folders('`[a-z_-]+`i');
				$dir_db_module = count($existing_db_files) > 0 ? $existing_db_files[0]->get_name() : '';
			}

			//Parsage du fichier sql.
			$sql_file = PATH_TO_ROOT . '/' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.' . DBTYPE . '.sql';
			if (file_exists($sql_file))
			{
				$Sql->parse($sql_file, PREFIX);
			}

			//Parsage du fichier php.
			$php_file = PATH_TO_ROOT . '/' . $module_identifier . '/db/' . $dir_db_module . '/' . $module_identifier . '.php';
			if (file_exists($php_file))
			{
				if (!Debug::is_debug_mode_enabled())
				{
					@include_once($php_file);
				}
				else
				{
					include_once($php_file);
				}
			}
		}
		// @endDeprecated

		ModulesConfig::load()->add_module($module);
		ModulesConfig::save();
		MenuService::add_mini_module($module_identifier);

		if ($generate_cache)
		{
			ModulesCssFilesCache::invalidate();
			MenuService::generate_cache();

			$rewrite_rules = $configuration->get_url_rewrite_rules();
			if ($CONFIG['rewrite'] == 1 && !empty($rewrite_rules))
			{
				HtaccessFileCache::regenerate();
			}
		}

		$Cache->generate_module_file($module_identifier, NO_FATAL_ERROR_CACHE);

		return MODULE_INSTALLED;
	}

	/**
	 * @static
	 * @desc Uninstalls a module.
	 * @param int $module_id Module id (in the DB_TABLE_MODULES table)
	 * @param bool $drop_files true if you want the module files to be dropped, otherwise false.
	 * @return int One of the following error codes:
	 * <ul>
	 * 	<li>MODULE_FILES_COULD_NOT_BE_DROPPED: the module files couldn't be deleted (probably due to an authorization issue) but it has been uninstalled .</li>
	 * 	<li>MODULE_UNINSTALLED: the module was successfully uninstalled.</li>
	 * 	<li>NOT_INSTALLED_MODULE: the module to uninstall doesn't exist!</li>
	 * </ul>
	 */
	public static function uninstall_module($module_id, $drop_files)
	{
		global $Cache, $Sql, $CONFIG, $MODULES;

		if (!empty($module_id))
		{
			self::execute_module_uninstallation($module_id);

			// @deprecated
			//Récupération des infos de config.
			$info_module = load_ini_file(PATH_TO_ROOT . '/' . $module_id . '/lang/', get_ulang());

			//Suppression du fichier cache
			$Cache->delete_file($module_id);

			//Suppression des commentaires associés.
			if (!empty($info_module['com']))
			{
				$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE script = '" . addslashes($info_module['com']) . "'");
			}

			$Sql->query_inject("DELETE FROM " . DB_TABLE_CONFIGS . " WHERE name = '" . addslashes($module_id) . "'");

			$dir_db_module = get_ulang();
			$dir = PATH_TO_ROOT . '/' . $module_id . '/db';

			if (!file_exists($dir . '/' . $dir_db_module) && file_exists($dir))
			{
				//Si le dossier de base de données de la LANG n'existe pas on prend le suivant exisant.
				$folder_path = new Folder($dir);
				foreach ($folder_path->get_folders('`^[a-z0-9_ -]+$`i') as $dir)
				{
					$dir_db_module = $dir->get_name();
					break;
				}
			}

			if (file_exists(PATH_TO_ROOT . '/' . $module_id . '/db/' . $dir_db_module . '/uninstall_' . $module_id . '.' . DBTYPE . '.sql'))
			{   //Parsage du fichier sql de désinstallation.
				$Sql->parse(PATH_TO_ROOT . '/' . $module_id . '/db/' . $dir_db_module . '/uninstall_' . $module_id . '.' . DBTYPE . '.sql', PREFIX);
			}

			if (file_exists(PATH_TO_ROOT . '/' . $module_id . '/db/' . $dir_db_module . '/uninstall_' . $module_id . '.php'))
			{   //Parsage fichier php de désinstallation.
				@include_once(PATH_TO_ROOT . '/' . $module_id . '/db/' . $dir_db_module . '/uninstall_' . $module_id . '.php');
			}
			// @endDeprecated


			ModulesCssFilesCache::invalidate();

			MenuService::generate_cache();

			//Régénération des feeds.
			Feed::clear_cache($module_id);

			$rewrite_rules = self::get_module($module_id)->get_configuration()->get_url_rewrite_rules();
			if ($CONFIG['rewrite'] == 1 && !empty($rewrite_rules))
			{
				HtaccessFileCache::regenerate();
			}

			MenuService::delete_mini_module($module_id);
			MenuService::delete_module_feeds_menus($module_id);

			ModulesConfig::load()->remove_module_by_id($module_id);
			ModulesConfig::save();

			//Suppression des fichiers du module
			if ($drop_files)
			{
				$folder = new Folder(PATH_TO_ROOT . '/' . $module_id);
				try
				{
					$folder->delete();
				}
				catch (IOException $ex)
				{
					return MODULE_FILES_COULD_NOT_BE_DROPPED;
				}
			}

			return MODULE_UNINSTALLED;
		}
		else
		{
			return NOT_INSTALLED_MODULE;
		}

		self::update_class_list();
	}

	public static function update_module_authorizations($module_id, $activated, array $authorizations)
	{
		$module = ModulesConfig::load()->get_module($module_id);
		$module->set_activated($activated);
		$module->set_authorizations($authorizations);
		ModulesConfig::save($module);
	}

	private static function execute_module_installation($module_id)
	{
		$module_setup = self::get_module_setup($module_id);
		$environment_check = $module_setup->check_environment();
		if (!$environment_check->has_errors())
		{
			$module_setup->install();
		}
		else
		{
			// TODO process module installation errors
		}
	}

	private static function execute_module_uninstallation($module_id)
	{
		$module_setup = self::get_module_setup($module_id);
		$module_setup->uninstall();
	}

	/**
	 * @desc
	 * @param string $module_id
	 * @return ModuleSetup
	 */
	private static function get_module_setup($module_id)
	{
		$module_setup_classname = ucfirst($module_id) . 'Setup';
		if (self::module_setup_exists($module_setup_classname))
		{
			return new $module_setup_classname();
		}
		return new DefaultModuleSetup();
	}

	private static function module_setup_exists($module_setup_classname)
	{
		return class_exists($module_setup_classname);
	}

	private static function update_class_list()
	{
		ClassLoader::generate_classlist();
	}
}

?>