<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 19
 * @since       PHPBoost 3.0 - 2009 12 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleConfiguration
{
	private $module_id;
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
	private $features;
	private $item_name;
	private $items_table_name;
	private $categories_table_name;

	public function __construct($config_ini_file, $desc_ini_file, $module_id)
	{
		$this->module_id = $module_id;
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

	public function get_url_rewrite_rules()
	{
		return $this->url_rewrite_rules;
	}

	public function get_documentation()
	{
		return $this->documentation;
	}

	public function get_features()
	{
		return $this->features;
	}

	public function feature_is_enabled($feature_id)
	{
		return in_array($feature_id, array_map('trim', $this->features), true);
	}

	public function get_item_name()
	{
		return $this->item_name;
	}

	public function has_items()
	{
		return $this->item_name && class_exists($this->item_name) && is_subclass_of($this->item_name, 'Item');
	}

	public function has_rich_items()
	{
		return $this->item_name && class_exists($this->item_name) && is_subclass_of($this->item_name, 'RichItem');
	}

	public function get_items_table_name()
	{
		return $this->items_table_name ? PREFIX . $this->items_table_name : '';
	}

	public function get_categories_table_name()
	{
		return PREFIX . $this->categories_table_name;
	}

	public function has_categories()
	{
		$categories_cache_class = TextHelper::ucfirst($this->module_id) . 'CategoriesCache';
		return ($this->feature_is_enabled('categories') || $this->feature_is_enabled('rich_categories') || (class_exists($categories_cache_class) && is_subclass_of($categories_cache_class, 'CategoriesCache')));
	}

	public function has_contribution()
	{
		return (bool)$this->contribution_interface;
	}

	public function get_configuration_name()
	{
		return $this->configuration_name;
	}

	public function has_rich_config_parameters()
	{
		return $this->configuration_name && class_exists($this->configuration_name) && is_subclass_of($this->configuration_name, 'DefaultRichModuleConfig');
	}

	public function get_configuration_parameters()
	{
		if ($this->configuration_name)
			return $this->configuration_name::load($this->module_id);
		else
			return DefaultModuleConfig::load($this->module_id);
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
		$this->features               = !empty($config['features']) ? explode(',', preg_replace('/\s/', '', $config['features'])) : array();
		$this->configuration_name     = !empty($config['configuration_name']) ? $config['configuration_name'] : $this->get_default_configuration_class_name();
		$this->admin_main_page        = !empty($config['admin_main_page']) ? $config['admin_main_page'] : ($this->configuration_name ? ModulesUrlBuilder::admin($this->module_id)->rel() : '');
		$this->admin_menu             = !empty($config['admin_menu']) ? $config['admin_menu'] : 'modules';
		$this->contribution_interface = !empty($config['contribution_interface']) ? $config['contribution_interface'] : ($this->feature_is_enabled('contribution') ? 'index.php?url=/add' : '');
		$this->url_rewrite_rules      = !empty($config['rewrite_rules']) ? $config['rewrite_rules'] : array();
		$this->item_name              = !empty($config['item_name']) ? $config['item_name'] : $this->get_default_item_class_name();
		$this->items_table_name       = !empty($config['items_table_name']) ? $config['items_table_name'] : ($this->item_name || $this->has_categories() ? $this->module_id : '');
		$this->categories_table_name  = !empty($config['categories_table_name']) ? $config['categories_table_name'] : ($this->has_categories() ? $this->module_id . '_cats' : '');
		$this->home_page              = !empty($config['home_page']) ? $config['home_page'] : ($this->item_name ? 'index.php' : '');
	}

	private function get_default_configuration_class_name()
	{
		$configuration_class_name = TextHelper::ucfirst($this->module_id) . 'Config';
		if (class_exists($configuration_class_name) && is_subclass_of($configuration_class_name, 'AbstractConfigData'))
			return $configuration_class_name;

		return '';
	}

	private function get_default_item_class_name()
	{
		$item_class_name = TextHelper::ucfirst($this->module_id);
		if (class_exists($item_class_name) && is_subclass_of($item_class_name, 'Item'))
			return $item_class_name;

		if (substr($item_class_name, -1) == 's')
		{
			$item_class_name = substr($item_class_name, 0, -1);
			if (class_exists($item_class_name) && is_subclass_of($item_class_name, 'Item'))
				return $item_class_name;
		}

		return '';
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
