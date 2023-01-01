<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 02
 * @since       PHPBoost 3.0 - 2010 07 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class GeneralConfig extends AbstractConfigData
{
	const SITE_URL = 'site_url';
	const SITE_PATH = 'site_path';
	const SITE_NAME = 'site_name';
	const SITE_SLOGAN = 'site_slogan';
	const SITE_DESCRIPTION = 'site_description';
	const MODULE_HOME_PAGE = 'module_home_page';
	const OTHER_HOME_PAGE = 'other_home_page';
	const PHPBOOST_VERSION = 'phpboost_version';
	const SITE_INSTALL_DATE = 'site_install_date';
	const SITE_TIMEZONE = 'timezone';

	public function get_site_url()
	{
		return $this->get_property(self::SITE_URL);
	}

	public function get_complete_site_url()
	{
		return $this->get_property(self::SITE_URL) . $this->get_property(self::SITE_PATH);
	}

	public function is_site_url_https()
	{
		return TextHelper::substr($this->get_property(self::SITE_URL), 0, 8) == "https://";
	}

	/**
	 * @param string $url The URL must begin with a protocol (for instance http://) and must not end with a slash.
	 */
	public function set_site_url($url)
	{
		$this->set_property(self::SITE_URL, $url);
	}

	public function get_site_path()
	{
		return $this->get_property(self::SITE_PATH);
	}

	/**
	 * @param string $url The URL must begin with a slash but must not end with a slash.
	 */
	public function set_site_path($path)
	{
		$this->set_property(self::SITE_PATH, $path == '/' ? '' : $path);
	}

	public function get_phpboost_major_version()
	{
		return $this->get_property(self::PHPBOOST_VERSION);
	}

	public function set_phpboost_major_version($version)
	{
		$this->set_property(self::PHPBOOST_VERSION, $version);
	}

	/**
	 * @return Date
	 */
	public function get_site_install_date()
	{
		return $this->get_property(self::SITE_INSTALL_DATE);
	}

	public function set_site_install_date(Date $date)
	{
		$this->set_property(self::SITE_INSTALL_DATE, $date);
	}

	public function get_site_timezone()
	{
		return $this->get_property(self::SITE_TIMEZONE);
	}

	public function set_site_timezone($timezone)
	{
		$this->set_property(self::SITE_TIMEZONE, $timezone);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function get_default_values()
	{
		$site_url = self::get_default_site_url();
		$site_path = self::get_default_site_path();

		return array(
			self::SITE_URL => $site_url,
			self::SITE_PATH => $site_path,
			self::SITE_NAME => '',
			self::SITE_SLOGAN => '',
			self::SITE_DESCRIPTION => '',
			self::MODULE_HOME_PAGE => '',
			self::OTHER_HOME_PAGE => '',
			self::PHPBOOST_VERSION => '6.0',
			self::SITE_INSTALL_DATE => new Date(Date::DATE_NOW, Timezone::SERVER_TIMEZONE),
			self::SITE_TIMEZONE => 'Europe/Paris',
		);
	}

	public static function get_default_site_url()
	{
		return 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));
	}

	public static function get_default_site_path()
	{
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		if (!$server_path)
		{
			$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		}
		return self::remove_dirs_from_root($server_path);
	}

	private static function remove_dirs_from_root($path)
	{
        $root_path_fragments = array();
        $path_fragments = explode('/', $path);
        $depth = 1;
		if (!in_array(PATH_TO_ROOT, array('.', '', null)))
		{
			$depth = count(explode('/', PATH_TO_ROOT)) + 1;
		}
		$length = count($path_fragments) - $depth;
		for ($i = 0; $i < $length; $i++)
		{
			$root_path_fragments[] = $path_fragments[$i];
		}
		return implode('/', $root_path_fragments);
	}

	public function get_site_name()
	{
		return $this->get_property(self::SITE_NAME);
	}

	public function set_site_name($site_name)
	{
		$this->set_property(self::SITE_NAME, $site_name);
	}

	public function get_site_slogan()
	{
		return $this->get_property(self::SITE_SLOGAN);
	}

	public function set_site_slogan($site_slogan)
	{
		$this->set_property(self::SITE_SLOGAN, $site_slogan);
	}

	public function get_site_description()
	{
		return $this->get_property(self::SITE_DESCRIPTION);
	}

	public function set_site_description($site_description)
	{
		$this->set_property(self::SITE_DESCRIPTION, $site_description);
	}

	public function get_module_home_page()
	{
		return $this->get_property(self::MODULE_HOME_PAGE);
	}

	public function set_module_home_page($start_page)
	{
		$this->set_property(self::MODULE_HOME_PAGE, $start_page);
	}

	public function get_other_home_page()
	{
		return $this->get_property(self::OTHER_HOME_PAGE);
	}

	public function set_other_home_page($start_page)
	{
		$this->set_property(self::OTHER_HOME_PAGE, $start_page);
	}

	/**
	 * Returns the configuration.
	 * @return GeneralConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'general-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'general-config');
	}
}
?>
