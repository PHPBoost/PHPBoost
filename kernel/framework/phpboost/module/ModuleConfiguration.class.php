<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 10
 * @since       PHPBoost 3.0 - 2009 12 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleConfiguration
{
	private $name;
	private $description;
	private $author;
	private $author_email;
	private $author_website;
	private $version;
	private $creation_date;
	private $last_update;
	private $compatibility;
	private $php_version;
	private $repository;
	private $admin_main_page;
	private $admin_menu;
	private $home_page;
	private $contribution_interface;
	private $url_rewrite_rules;
	private $documentation;
	private $enabled_features;

	public function __construct($config_ini_file, $desc_ini_file)
	{
		$this->load_configuration($config_ini_file);
		$this->load_description($desc_ini_file);
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function get_author()
	{
		return $this->author;
	}

	public function get_author_email()
	{
		return $this->author_email;
	}

	public function get_author_website()
	{
		return $this->author_website;
	}

	public function get_version()
	{
		return $this->version;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function get_last_update()
	{
		return $this->last_update;
	}

	public function get_compatibility()
	{
		return $this->compatibility;
	}

	public function get_php_version()
	{
		return $this->php_version;
	}

	public function get_repository()
	{
		return $this->repository;
	}

	public function get_admin_main_page()
	{
		return $this->admin_main_page;
	}

	public function get_admin_menu()
	{
		return $this->admin_menu;
	}

	public function get_home_page()
	{
		return $this->home_page;
	}

	public function get_contribution_interface()
	{
		return $this->contribution_interface;
	}

	public function get_mini_modules()
	{
		return $this->mini_modules;
	}

	public function get_url_rewrite_rules()
	{
		return $this->url_rewrite_rules;
	}

	public function get_documentation()
	{
		return $this->documentation;
	}

	public function get_enabled_features()
	{
		return $this->enabled_features;
	}

	public function feature_is_enabled($feature_id)
	{
		if (in_array($feature_id, array_map('trim', $this->enabled_features), true))
			return true;
		else
			return false;
	}

	private function load_configuration($config_ini_file)
	{
		$config = parse_ini_file($config_ini_file);
		$this->check_parse_ini_file($config, $config_ini_file);

		$this->author                 = $config['author'];
		$this->author_email           = $config['author_mail'];
		$this->author_website         = $config['author_website'];
		$this->version                = $config['version'];
		$this->creation_date          = isset($config['creation_date']) ? Date::to_format(strtotime($config['creation_date']),Date::FORMAT_DAY_MONTH_YEAR) : '';
		$this->last_update            = isset($config['last_update']) ? Date::to_format(strtotime($config['last_update']),Date::FORMAT_DAY_MONTH_YEAR) : '';
		$this->compatibility          = $config['compatibility'];
		$this->php_version            = !empty($config['php_version']) ? $config['php_version'] : ServerConfiguration::MIN_PHP_VERSION;
		$this->repository             = !empty($config['repository']) ? $config['repository'] : Updates::PHPBOOST_OFFICIAL_REPOSITORY;
		$this->admin_main_page        = !empty($config['admin_main_page']) ? $config['admin_main_page'] : '';
		$this->admin_menu             = !empty($config['admin_menu']) ? $config['admin_menu'] : '';
		$this->home_page              = !empty($config['home_page']) ? $config['home_page'] : '';
		$this->contribution_interface = !empty($config['contribution_interface']) ? $config['contribution_interface'] : '';
		$this->url_rewrite_rules      = !empty($config['rewrite_rules']) ? $config['rewrite_rules'] : array();
		$this->enabled_features       = !empty($config['enabled_features']) ? explode(',', $config['enabled_features']) : array();
	}

	private function load_description($desc_ini_file)
	{
		$desc = @parse_ini_file($desc_ini_file);
		$this->check_parse_ini_file($desc, $desc_ini_file);
		$this->name = $desc['name'];
		$this->description = $desc['desc'];
		$this->documentation = !empty($desc['documentation']) ? $desc['documentation'] : '';
	}

	private function check_parse_ini_file($parse_result, $ini_file)
	{
		if ($parse_result === false)
		{
			throw new IOException('Parse ini file ' . $ini_file . ' failed');
		}
	}
}
?>
