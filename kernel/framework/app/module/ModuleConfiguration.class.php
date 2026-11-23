<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2009 12 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      xela <xela@phpboost.com>
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleConfiguration
{
    private string $addon_type;
    private string $module_id;
    private string $name;
    private string $genre;
    private string $description;
    private string $author;
    private string $author_email;
    private string $author_website;
    private string $version;
    private string $creation_date;
    private string $last_update;
    private string $compatibility;
    private string $php_version;
    private string $repository;
    private string $admin_main_page;
    private string $admin_menu;
    private string $home_page;
    private string $contribution_interface;
    private array $specific_hooks;
    private array $url_rewrite_rules;
    private string $documentation;
    private array $features;
    private string $item_name;
    private string $items_table_name;
    private string $categories_table_name;
    private string $configuration_name;
    private string $fa_icon;
    private string $hexa_icon;

    public function __construct(mixed $config_ini_file, mixed $desc_ini_file, string $module_id)
    {
        $this->module_id = $module_id;
        $this->load_configuration($config_ini_file);
        $this->load_description($desc_ini_file);
    }

    public function get_addon_type(): string
    {
        return $this->addon_type;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_genre(): string
    {
        return $this->genre;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_author(): string
    {
        return $this->author;
    }

    public function get_author_email(): string
    {
        return $this->author_email;
    }

    public function get_author_website(): string
    {
        return $this->author_website;
    }

    public function get_version(): string
    {
        return $this->version;
    }

    public function get_creation_date(): string
    {
        return $this->creation_date;
    }

    public function get_last_update(): string
    {
        return $this->last_update;
    }

    public function get_fa_icon(): string
    {
        return $this->fa_icon;
    }

    public function get_hexa_icon(): string
    {
        return $this->hexa_icon;
    }

    public function get_compatibility(): string
    {
        return $this->compatibility;
    }

    public function get_php_version(): string
    {
        return $this->php_version;
    }

    public function get_repository(): string
    {
        return $this->repository;
    }

    public function get_admin_main_page(): string
    {
        return $this->admin_main_page;
    }

    public function get_admin_menu(): string
    {
        return $this->admin_menu;
    }

    public function get_home_page(): string
    {
        return $this->home_page;
    }

    public function get_contribution_interface(): string
    {
        return $this->contribution_interface;
    }

    public function get_specific_hooks(): array
    {
        return $this->specific_hooks;
    }

    public function get_url_rewrite_rules(): array
    {
        return $this->url_rewrite_rules;
    }

    public function get_documentation(): string
    {
        return $this->documentation;
    }

    public function get_features(): array
    {
        return $this->features;
    }

    public function feature_is_enabled(string $feature_id): bool
    {
        return in_array($feature_id, array_map('trim', $this->features), true);
    }

    public function get_item_name(): string
    {
        return $this->item_name;
    }

    public function has_items(): bool
    {
        return $this->item_name && ClassLoader::is_class_registered_and_valid($this->item_name) && ($this->item_name == 'Item' || is_subclass_of($this->item_name, 'Item'));
    }

    public function has_rich_items(): bool
    {
        return $this->item_name && ClassLoader::is_class_registered_and_valid($this->item_name) && ($this->item_name == 'RichItem' || is_subclass_of($this->item_name, 'RichItem'));
    }

    public function get_items_table_name(): string
    {
        return $this->items_table_name ? PREFIX . $this->items_table_name : '';
    }

    public function get_categories_table_name(): string
    {
        return PREFIX . $this->categories_table_name;
    }

    public function has_categories(): bool
    {
        $module_categories_cache_class_name = TextHelper::ucfirst($this->module_id) . 'CategoriesCache';
        $categories_cache_class = ClassLoader::is_class_registered_and_valid($module_categories_cache_class_name) && is_subclass_of($module_categories_cache_class_name, 'CategoriesCache') ? $module_categories_cache_class_name : '';
        return ($this->feature_is_enabled('categories') || $this->feature_is_enabled('rich_categories') || $categories_cache_class);
    }

    public function has_contribution(): bool
    {
        return (bool)$this->contribution_interface;
    }

    public function has_duplication(): bool
    {
        return $this->feature_is_enabled('duplication');
    }

    public function get_configuration_name(): string
    {
        if ($this->configuration_name)
        {
            return $this->configuration_name;
        }
        else
        {
            if ($this->has_rich_config_parameters() || $this->has_rich_items())
            {
                return 'DefaultRichModuleConfig';
            }
            if ($this->has_items())
            {
                return 'DefaultModuleConfig';
            }
        }
        return '';
    }

    public function has_rich_config_parameters(): bool
    {
        return $this->configuration_name && ClassLoader::is_class_registered_and_valid($this->configuration_name) && is_subclass_of($this->configuration_name, 'DefaultRichModuleConfig');
    }

    public function get_configuration_parameters(): mixed
    {
        $configuration_name = $this->get_configuration_name();
        if ($configuration_name)
        {
            return $configuration_name::load($this->module_id);
        }
        else
        {
            return DefaultModuleConfig::load($this->module_id);
        }
    }

    private function load_configuration(mixed $config_ini_file): void
    {
        $config = parse_ini_file($config_ini_file);
        $this->check_parse_ini_file($config, $config_ini_file);

        $this->addon_type             = isset($config['addon_type']) ? $config['addon_type'] : '';
        $this->author                 = isset($config['author']) ? $config['author'] : '';
        $this->author_email           = isset($config['author_mail']) ? $config['author_mail'] : '';
        $this->author_website         = isset($config['author_website']) ? $config['author_website'] : '';
        $this->version                = isset($config['version']) ? $config['version'] : '';
        $this->creation_date          = isset($config['creation_date']) ? Date::to_format(strtotime($config['creation_date']), Date::FORMAT_DAY_MONTH_YEAR) : null;
        $this->last_update            = isset($config['last_update']) ? Date::to_format(strtotime($config['last_update']), Date::FORMAT_DAY_MONTH_YEAR) : null;
        $this->compatibility          = isset($config['compatibility']) ? $config['compatibility'] : '';
        $this->fa_icon                = isset($config['fa_icon']) ? $config['fa_icon'] : '';
        $this->hexa_icon              = isset($config['hexa_icon']) ? $config['hexa_icon'] : '';
        $this->php_version            = isset($config['php_version']) && !empty($config['php_version']) ? $config['php_version'] : ServerConfiguration::MIN_PHP_VERSION;
        $this->repository             = isset($config['repository']) && !empty($config['repository']) ? $config['repository'] : Updates::PHPBOOST_OFFICIAL_REPOSITORY;
        $this->features               = isset($config['features']) && !empty($config['features']) ? explode(',', preg_replace('/\s/', '', $config['features'])) : [];
        $this->specific_hooks         = isset($config['specific_hooks']) && !empty($config['specific_hooks']) ? explode(',', preg_replace('/\s/', '', $config['specific_hooks'])) : [];
        $this->contribution_interface = isset($config['contribution_interface']) && !empty($config['contribution_interface']) ? Url::to_rel('/' . $this->module_id . '/' . $config['contribution_interface']) : ($this->feature_is_enabled('contribution') ? ItemsUrlBuilder::add(Category::ROOT_CATEGORY, $this->module_id)->rel() : '');
        $this->url_rewrite_rules      = isset($config['rewrite_rules']) && !empty($config['rewrite_rules']) ? $config['rewrite_rules'] : [];

        if (GeneralConfig::load()->get_phpboost_major_version() >= '6.0' && $this->compatibility >= '6.0' && ((ModulesManager::is_module_installed($this->module_id) && ModulesConfig::load()->get_module($this->module_id)->get_installed_version() == $this->version) || !ModulesManager::is_module_installed($this->module_id)))
        {
            $this->item_name             = isset($config['item_name']) && !empty($config['item_name']) ? $config['item_name'] : $this->get_default_item_class_name();
            $this->items_table_name      = isset($config['items_table_name']) && !empty($config['items_table_name']) ? $config['items_table_name'] : ($this->item_name || $this->has_categories() ? $this->module_id : '');
            $this->categories_table_name = isset($config['categories_table_name']) && !empty($config['categories_table_name']) ? $config['categories_table_name'] : ($this->has_categories() ? $this->module_id . '_cats' : '');
            $this->configuration_name    = isset($config['configuration_name']) && !empty($config['configuration_name']) ? $config['configuration_name'] : $this->get_default_configuration_class_name();
        }

        $this->home_page       = isset($config['home_page']) && !empty($config['home_page']) ? $config['home_page'] : ($this->item_name ? 'index.php' : '');
        $this->admin_main_page = isset($config['admin_main_page']) && !empty($config['admin_main_page']) ? (!preg_match('/' . $this->module_id . '\//', $config['admin_main_page']) ? Url::to_relative('/' . $this->module_id . '/' . $config['admin_main_page']) : $config['admin_main_page']) : ($this->get_configuration_name() ? ModulesUrlBuilder::admin($this->module_id)->relative() : '');
        $this->admin_menu      = isset($config['admin_menu']) && !empty($config['admin_menu']) ? $config['admin_menu'] : 'modules';
    }

    private function get_default_configuration_class_name(): string
    {
        $module_config_class_name = TextHelper::ucfirst($this->module_id) . 'Config';
        return ClassLoader::is_class_registered_and_valid($module_config_class_name) && is_subclass_of($module_config_class_name, 'AbstractConfigData') ? $module_config_class_name : '';
    }

    private function get_default_item_class_name(): string
    {
        $module_item_class_name = TextHelper::ucfirst($this->module_id) . 'Item';
        $item_class = ClassLoader::is_class_registered_and_valid($module_item_class_name) && is_subclass_of($module_item_class_name, 'Item') ? $module_item_class_name : '';
        if (empty($item_class))
        {
            if ($this->feature_is_enabled('items'))
            {
                $item_class = 'Item';
            }
            if ($this->feature_is_enabled('rich_items'))
            {
                $item_class = 'RichItem';
            }
        }
        return $item_class;
    }

    private function load_description(mixed $desc_ini_file): void
    {
        $desc = @parse_ini_file($desc_ini_file);
        $this->check_parse_ini_file($desc, $desc_ini_file);
        $this->name          = isset($desc['name']) ? $desc['name'] : '';
        $this->genre         = isset($desc['genre']) ? $desc['genre'] : '';
        $this->description   = isset($desc['desc']) ? $desc['desc'] : '';
        $this->documentation = isset($desc['doc']) && !empty($desc['doc']) ? $desc['doc'] : '';
    }

    private function check_parse_ini_file(mixed $parse_result, mixed $ini_file): void
    {
        if ($parse_result === false)
        {
            throw new IOException('Parse ini file ' . $ini_file . ' failed');
        }
    }

    public function get_properties(): array
    {
        return [
            'addon_type'             => $this->addon_type,
            'name'                   => $this->name,
            'genre'                  => $this->genre,
            'description'            => $this->description,
            'documentation'          => $this->documentation,
            'author'                 => $this->author,
            'author_email'           => $this->author_email,
            'author_website'         => $this->author_website,
            'version'                => $this->version,
            'compatibility'          => $this->compatibility,
            'fa_icon'                => $this->fa_icon,
            'hexa_icon'              => $this->hexa_icon,
            'creation_date'          => $this->creation_date,
            'last_update'            => $this->last_update,
            'php_version'            => $this->php_version,
            'features'               => implode(', ', $this->features),
            'specific_hooks'         => implode(', ', $this->specific_hooks),
            'contribution_interface' => $this->contribution_interface,
            'home_page'              => $this->home_page,
            'admin_main_page'        => $this->admin_main_page,
            'admin_menu'             => $this->admin_menu
        ];
    }
}
?>
