<?php
/**
 * This class enables you to manages the PHPBoost packages which are nothing else than the modules.
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 17
 * @since       PHPBoost 2.0 - 2008 10 12
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModulesManager
{
	const GENERATE_CACHE_AFTER_THE_OPERATION = true;
	const DO_NOT_GENERATE_CACHE_AFTER_THE_OPERATION = false;
	const MODULE_UNINSTALLED = 0;
	const MODULE_INSTALLED = 1;
	const UNEXISTING_MODULE = 2;
	const MODULE_ALREADY_INSTALLED = 3;
	const CONFIG_CONFLICT = 4;
	const NOT_INSTALLED_MODULE = 5;
	const MODULE_FILES_COULD_NOT_BE_DROPPED = 6;
	const PHP_VERSION_CONFLICT = 7;
	const PHPBOOST_VERSION_CONFLICT = 8;
	const MODULE_NOT_UPGRADABLE = 9;
	const UPGRADE_FAILED = 10;
	const MODULE_UPDATED = 11;

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (activated or not)
	 */
	public static function get_installed_modules_map()
	{
		return ModulesConfig::load()->get_modules();
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (and activated )
	 */
	public static function get_activated_modules_map()
	{
		$activated_modules = array();
		foreach (ModulesConfig::load()->get_modules() as $module)
		{
			if ($module->is_activated())
				$activated_modules[$module->get_id()] = $module;
		}
		return $activated_modules;
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the uninstalled modules (activated or not)
	 */
	public static function get_uninstalled_modules_map()
	{
		throw new NotYetImplementedException();
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (activated or not)
	 * sorted by name
	 */
	public static function get_installed_modules_map_sorted_by_localized_name()
	{
		$modules = self::get_installed_modules_map();
		try {
			uasort($modules, array(__CLASS__, 'callback_sort_modules_by_name'));
		} catch (IOException $ex) {
		}
		return $modules;
	}

	/**
	 * @return Module[string] the Modules map (name => module) of the installed modules (and activated)
	 * sorted by name
	 */
	public static function get_activated_modules_map_sorted_by_localized_name()
	{
		$modules = self::get_activated_modules_map();
		try {
			uasort($modules, array(__CLASS__, 'callback_sort_modules_by_name'));
		} catch (IOException $ex) {
		}
		return $modules;
	}

	public static function callback_sort_modules_by_name(Module $module1, Module $module2)
	{
		if (TextHelper::strtolower($module1->get_configuration()->get_name()) > TextHelper::strtolower($module2->get_configuration()->get_name()))
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
		return array_keys(self::get_installed_modules_map());
	}

	/**
	 * @return string[] the names list of the installed modules (and activated)
	 */
	public static function get_activated_modules_ids_list()
	{
		return array_keys(self::get_activated_modules_map());
	}

	/**
	 * @return string[] the list of modules that have the requested feature
	 */
	public static function get_activated_feature_modules($feature)
	{
		$modules = array();
		foreach (self::get_activated_modules_map() as $module)
		{
			if ($module->get_configuration()->feature_is_enabled($feature))
				$modules[$module->get_id()] = $module;
		}
		return $modules;
	}

	/**
	 * Returns the requested module
	 * @param $module_id the id of the module
	 * @return Module the requested module
	 */
	public static function get_module($module_id)
	{
		return ModulesConfig::load()->get_module($module_id);
	}

	/**
	 * tells whether the requested module is installed (activated or not)
	 * @return bool true if the requested module is installed
	 */
	public static function is_module_installed($module_id)
	{
		return in_array($module_id, self::get_installed_modules_ids_list());
	}

	/**
	 * tells whether the requested module is activated
	 * @return bool true if the requested module is activated
	 */
	public static function is_module_activated($module_id)
	{
		return in_array($module_id, self::get_activated_modules_ids_list());
	}

	/**
	 * @static
	 * Installs a module.
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

		if (empty($module_identifier) || !is_dir(PATH_TO_ROOT . '/' . $module_identifier))
		{
			return self::UNEXISTING_MODULE;
		}

		if (self::is_module_installed($module_identifier))
		{
			return self::MODULE_ALREADY_INSTALLED;
		}

		$module = new Module($module_identifier, $enable_module);
		$configuration = $module->get_configuration();

		$phpversion = ServerConfiguration::get_phpversion();
		if (version_compare($phpversion, $configuration->get_php_version(), 'lt'))
		{
			return self::PHP_VERSION_CONFLICT;
		}

		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		if (version_compare($phpboost_version, $configuration->get_compatibility(), '!='))
		{
			return self::PHPBOOST_VERSION_CONFLICT;
		}

		self::execute_module_installation($module_identifier);

		ModulesConfig::load()->add_module($module);
		ModulesConfig::save();

		// TODO Force initialization ExtensionProviderService for PHPBoost installation
		AppContext::init_extension_provider_service();

		MenuService::add_mini_module($module_identifier, $generate_cache);

		// Resize picture and create mini picture if needed
		$picture = PATH_TO_ROOT . '/' . $module_identifier . '/' . $module_identifier . '.png';
		$file = new File($picture);
		if ($file->exists())
		{
			$resizer = new ImageResizer();
			$image = new Image($picture);
			if ($image->get_width() > 32 && $image->get_height() > 32)
			{
				try {
					$resizer->resize_with_max_values($image, 32, 32, $picture);
				} catch (UnsupportedOperationException $e) {}
			}
			$mini_picture = PATH_TO_ROOT . '/' . $module_identifier . '/' . $module_identifier . '_mini.png';
			$file = new File($mini_picture);
			if (!$file->exists())
			{
				try {
					$resizer->resize_with_max_values($image, 16, 16, $mini_picture);
				} catch (UnsupportedOperationException $e) {}
			}
			else
			{
				$image = new Image($mini_picture);
				if ($image->get_width() > 16 && $image->get_height() > 16)
				{
					try {
						$resizer->resize_with_max_values($image, 16, 16, $picture);
					} catch (UnsupportedOperationException $e) {}
				}
			}
		}

		if ($generate_cache)
		{
			MenuService::generate_cache();

			if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
			{
				HtaccessFileCache::regenerate();
				NginxFileCache::regenerate();
			}
		}

		return self::MODULE_INSTALLED;
	}

	/**
	 * @static
	 * Uninstalls a module.
	 * @param int $module_id Module id (in the DB_TABLE_MODULES table)
	 * @param bool $drop_files true if you want the module files to be dropped, otherwise false.
	 * @return int One of the following error codes:
	 * <ul>
	 * 	<li>MODULE_FILES_COULD_NOT_BE_DROPPED: the module files couldn't be deleted (probably due to an authorization issue) but it has been uninstalled .</li>
	 * 	<li>MODULE_UNINSTALLED: the module was successfully uninstalled.</li>
	 * 	<li>NOT_INSTALLED_MODULE: the module to uninstall doesn't exist!</li>
	 * </ul>
	 */
	public static function uninstall_module($module_id, $drop_files = false, $generate_cache = true)
	{
		if (!empty($module_id) && self::is_module_installed($module_id))
		{
			$error = self::execute_module_uninstallation($module_id);
			if ($error === null)
			{
				ContributionService::delete_contribution_module($module_id);

				NotationService::delete_notes_module($module_id);

				CommentsService::delete_comments_module($module_id);

				PersistenceContext::get_querier()->delete(DB_TABLE_CONFIGS, "WHERE name = :name", array('name' => $module_id));

				//Régénération des feeds.
				Feed::clear_cache($module_id);

				MenuService::delete_mini_module($module_id);
				MenuService::delete_module_feeds_menus($module_id);

				ModulesConfig::load()->remove_module_by_id($module_id);
				ModulesConfig::save();

				//Module home page ?
				$general_config = GeneralConfig::load();
				$module_home_page_selected = $general_config->get_module_home_page();
				if ($module_home_page_selected == $module_id)
				{
					$general_config->set_module_home_page('');
					$general_config->set_other_home_page('index.php');
				}

				//Suppression des fichiers du module
				if ($drop_files)
				{
					$folder = new Folder(PATH_TO_ROOT . '/' . $module_id);
					try
					{
						$folder->delete();
						self::update_class_list();
						AppContext::init_extension_provider_service();
					}
					catch (IOException $ex)
					{
						return self::MODULE_FILES_COULD_NOT_BE_DROPPED;
					}
				}

				if ($generate_cache)
				{
					AppContext::get_cache_service()->clear_cache();

					if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
					{
						HtaccessFileCache::regenerate();
						NginxFileCache::regenerate();
					}
				}

				return self::MODULE_UNINSTALLED;
			}
			return $error;
		}
		else
		{
			return self::NOT_INSTALLED_MODULE;
		}
	}

	public static function upgrade_module($module_identifier, $generate_cache = true)
	{
		self::update_class_list();
		
		if (!empty($module_identifier) && is_dir(PATH_TO_ROOT . '/' . $module_identifier))
		{
			if (self::is_module_installed($module_identifier))
			{
				if (self::module_is_upgradable($module_identifier))
				{
					$module = self::get_module($module_identifier);

					$version_upgrading = self::execute_module_upgrade($module_identifier, $module->get_installed_version());

					if ($version_upgrading !== null)
					{
						$module->set_installed_version($version_upgrading);
						ModulesConfig::load()->update($module);
						ModulesConfig::save();

						if ($generate_cache)
						{
							AppContext::get_cache_service()->clear_cache();

							if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
							{
								HtaccessFileCache::regenerate();
								NginxFileCache::regenerate();
							}
						}
					}
					else
					{
						return self::UPGRADE_FAILED;
					}
				}
				else
				{
					return self::MODULE_NOT_UPGRADABLE;
				}
			}
			else
			{
				return self::NOT_INSTALLED_MODULE;
			}
		}
		else
		{
			return self::UNEXISTING_MODULE;
		}

		return self::MODULE_UPDATED;
	}

	public static function module_is_upgradable($module_identifier)
	{
		if (!empty($module_identifier) && is_dir(PATH_TO_ROOT . '/' . $module_identifier))
		{
			if (self::is_module_installed($module_identifier))
			{
				$module = self::get_module($module_identifier);

				if (version_compare($module->get_installed_version(), $module->get_configuration()->get_version()) == -1 && $module->get_configuration()->get_compatibility() == GeneralConfig::load()->get_phpboost_major_version())
				{
					return true;
				}
			}
		}
		return false;
	}

	public static function update_module($module_id, $activated, $generate_cache = true)
	{
		$error = '';
		if (!$activated)
		{
			MenuService::delete_mini_module($module_id);
			MenuService::delete_module_feeds_menus($module_id);

			$general_config = GeneralConfig::load();
			$module_home_page_selected = $general_config->get_module_home_page();
			if ($module_home_page_selected == $module_id)
			{
				$general_config->set_module_home_page('');
				$general_config->set_other_home_page('index.php');
			}

			$editors = AppContext::get_content_formatting_service()->get_available_editors();
			if (in_array($module_id, $editors))
			{
				if (count($editors) > 1)
				{
					$default_editor = ContentFormattingConfig::load()->get_default_editor();
					if ($default_editor !== $module_id)
					{
						PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('editor' => $default_editor),
							'WHERE editor=:old_editor', array('old_editor' => $module_id
						));
					}
					else
						$error = LangLoader::get_message('warning.is.default.editor', 'warning-lang');
				}
				else
					$error = LangLoader::get_message('warning.last.editor.installed', 'warning-lang');
			}

			$captchas = AppContext::get_captcha_service()->get_available_captchas();
			if (in_array($module_id, $captchas))
			{
				if (count($captchas) > 1)
				{
					$default_captcha = ContentManagementConfig::load()->get_used_captcha_module();
					if ($default_captcha == $module_id)
						$error = LangLoader::get_message('warning.captcha.is.default', 'warning-lang');
				}
				else
					$error = LangLoader::get_message('warning.captcha.last.installed', 'warning-lang');
			}
		}
		else
		{
			$module = self::get_module($module_id);

			if ($module->get_configuration()->get_compatibility() != GeneralConfig::load()->get_phpboost_major_version())
				$error = LangLoader::get_message('addon.not.compatible', 'addon-lang');
		}

		if (empty($error))
		{
			$module = self::get_module($module_id);
			$module->set_activated($activated);
			ModulesConfig::load()->update($module);
			ModulesConfig::save();

			MenuService::add_mini_module($module_id);
			Feed::clear_cache($module_id);

			if ($generate_cache)
			{
				AppContext::get_cache_service()->clear_cache();

				if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled())
				{
					HtaccessFileCache::regenerate();
					NginxFileCache::regenerate();
				}
			}
		}

		return $error;
	}

	public static function set_module_activation($module_id, bool $activated)
	{
		$module = self::get_module($module_id);
		$module->set_activated($activated);
		ModulesConfig::load()->update($module);
		ModulesConfig::save();
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
		return $module_setup->uninstall();
	}

	private static function execute_module_upgrade($module_id, $installed_version)
	{
		$module_setup = self::get_module_setup($module_id);
		return $module_setup->upgrade($installed_version);
	}

	/**
	 *
	 * @param string $module_id
	 * @return ModuleSetup
	 */
	private static function get_module_setup($module_id)
	{
		$module_setup_classname = TextHelper::ucfirst($module_id) . 'Setup';
		if (self::module_setup_exists($module_setup_classname))
		{
			return new $module_setup_classname($module_id);
		}
		return new DefaultModuleSetup($module_id);
	}

	private static function module_setup_exists($module_setup_classname)
	{
		return ClassLoader::is_class_registered_and_valid($module_setup_classname);
	}

	private static function update_class_list()
	{
		ClassLoader::generate_classlist(true);
	}

	/**
	 * @return string[] the names list of the modules for unauthorized FormFieldSelectChoiceOption
	 * @param string $feature Feature for which display the modules list.
	 */
	public static function generate_unauthorized_module_option($feature)
	{
		$options = array();

		foreach (self::get_activated_feature_modules($feature) as $id => $module)
		{
			$options[] = new FormFieldSelectChoiceOption($module->get_configuration()->get_name(), $module->get_id());
		}
		return $options;
	}

}
?>
