<?php
/**
 * @package     PHPBoost
 * @subpackage  Update
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 27
 * @since       PHPBoost 2.0 - 2008 08 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('CHECK_KERNEL', 0X01);
define('CHECK_MODULES', 0X02);
define('CHECK_THEMES', 0X04);
define('CHECK_ALL_UPDATES', CHECK_KERNEL|CHECK_MODULES|CHECK_THEMES);

class Updates
{
	private $repositories = array();
	private $apps = array();

	const PHPBOOST_OFFICIAL_REPOSITORY = 'https://www.phpboost.com/repository/main.xml';
	const PHPBOOST_OFFICIAL_MODULES_REPOSITORY = 'https://dl.phpboost.com/repository/modules.xml';
	const PHPBOOST_OFFICIAL_THEMES_REPOSITORY = 'https://dl.phpboost.com/repository/themes.xml';

	/**
	* constructor of the class
	* @param $checks
	*/
	public function __construct($checks = CHECK_ALL_UPDATES)
	{
		$this->load_apps($checks);
		$this->load_repositories();
		$this->check_repositories();
	}

	/**
	* Load Application Classes
	* @param $checks
	*/
	private function load_apps($checks = CHECK_ALL_UPDATES)
	{
		$user_locale = AppContext::get_current_user()->get_locale();
		if ($checks & CHECK_KERNEL)
		{
			// Add kernel repository to the check list
			$this->apps[] = new Application('kernel', $user_locale, Application::KERNEL_TYPE, Environment::get_phpboost_version(), self::PHPBOOST_OFFICIAL_REPOSITORY);
		}

		if ($checks & CHECK_MODULES)
		{
			// Add modules repository to the check list
			foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
			{
				$repository = $module->get_configuration()->get_repository();
				$repository = $repository ? $repository : self::PHPBOOST_OFFICIAL_MODULES_REPOSITORY;
				$this->apps[] = new Application($module->get_id(), $user_locale, Application::MODULE_TYPE, $module->get_configuration()->get_version(), $repository);
			}
		}

		if ($checks & CHECK_THEMES)
		{
			// Add themes repository to the check list
			foreach (ThemesManager::get_activated_themes_map() as $theme)
			{
				$repository = $theme->get_configuration()->get_repository();
				$repository = $repository ? $repository : self::PHPBOOST_OFFICIAL_THEMES_REPOSITORY;
				$this->apps[] = new Application($theme->get_id(), $user_locale, Application::TEMPLATE_TYPE, $theme->get_configuration()->get_version(), $repository);
			}
		}
	}

	/**
	* Load Repository Classes
	*/
	private function load_repositories()
	{
		foreach ($this->apps as $app)
		{
			$rep = $app->get_repository();
			if (!empty($rep) && !isset($this->repositories[$rep]))
				$this->repositories[$rep] = new Repository($rep);
		}
	}

	/**
	* Check Repository for Update Notification
	*/
	private function check_repositories()
	{
		foreach ($this->apps as $app)
		{
			$result = $this->repositories[$app->get_repository()]->check($app);
			if ($result !== null)
			{   // processing to the update notification
				$this->add_update_alert($result);
			}
		}
	}

	/**
	* Save an alert for Update Notification
	*/
	private function add_update_alert($app)
	{
		$identifier = $app->get_identifier();
		// We verify that the alert is not already registered
		if (AdministratorAlertService::find_by_identifier($identifier, 'updates', 'kernel') === null)
		{
			$alert = new AdministratorAlert();

			if ($app->get_type() == Application::KERNEL_TYPE)
				$alert->set_entitled(sprintf(LangLoader::get_message('admin.kernel.update', 'admin-lang'), $app->get_version()));
			else
				$alert->set_entitled(sprintf(LangLoader::get_message('admin.available.update', 'admin-lang'), $app->get_type(), $app->get_name(), $app->get_version()));

			$alert->set_fixing_url('/admin/updates/detail.php?identifier=' . $identifier);
			$alert->set_priority($app->get_priority());
			$alert->set_alert_properties(TextHelper::serialize_base64($app));
			$alert->set_type('updates');
			$alert->set_identifier($identifier);

			//Save
			AdministratorAlertService::save_alert($alert);
		}
	}
}
?>
