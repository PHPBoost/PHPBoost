<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2011 04 10
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ThemeConfiguration
{
	private string $addon_type;
	private string $name;
	private string $author_name;
	private string $author_mail;
	private string $author_link;
	private string $version;
	private string $description = '';
	private string $creation_date;
	private string $last_update;
	private string $compatibility;
	private bool $require_copyright = false;
	private string $html_version = '5.0';
	private string $css_version = '4.0';
	private ?ColumnsDisabled $columns_disabled = null;
	private string $main_color = '';
	private string $variable_width;
	private string $width;
	private array $pictures;
	private string $parent_theme;
	private string $repository;

	public function __construct(mixed $config_ini_file, mixed $desc_ini_file)
	{
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

	public function get_author_name(): string
	{
		return $this->author_name;
	}

	public function get_author_mail(): string
	{
		return $this->author_mail;
	}

	public function get_author_link(): string
	{
		return $this->author_link;
	}

	public function get_version(): string
	{
		return $this->version;
	}

	public function get_description(): string
	{
		return $this->description;
	}

	public function get_creation_date(): string
	{
		return $this->creation_date;
	}

	public function get_last_update(): string
	{
		return $this->last_update;
	}

	public function get_compatibility(): string
	{
		return $this->compatibility;
	}

	public function get_require_copyright(): string
	{
		return $this->require_copyright;
	}

	public function get_html_version(): string
	{
		return $this->html_version;
	}

	public function get_css_version(): string
	{
		return $this->css_version;
	}

	public function get_columns_disabled(): ColumnsDisabled
	{
		return $this->columns_disabled;
	}

	public function get_main_color(): string
	{
		return $this->main_color;
	}

	public function get_variable_width(): string
	{
		return $this->variable_width;
	}

	public function get_width(): string
	{
		return $this->width;
	}

	public function get_pictures(): array
	{
		return $this->pictures;
	}

	public function get_first_picture(): string
	{
		if (isset($this->pictures[0]))
		{
			return $this->pictures[0];
		}
		return '';
	}

	public function get_parent_theme(): string
	{
		return $this->parent_theme;
	}

	public function get_repository(): string
	{
		return $this->repository;
	}

	private function load_configuration(mixed $config_ini_file): void
	{
		$config = @parse_ini_file($config_ini_file);

		// config.ini may not exist if the theme has not been downloaded yet
		// (e.g. during installation before the theme is fetched from the server).
		// Return safe empty defaults instead of crashing.
		if ($config === false)
		{
			$this->addon_type        = '';
			$this->author_name       = '';
			$this->author_mail       = '';
			$this->author_link       = '';
			$this->creation_date     = '';
			$this->last_update       = '';
			$this->compatibility     = '';
			$this->version           = '';
			$this->html_version      = '';
			$this->css_version       = '';
			$this->main_color        = '';
			$this->width             = '';
			$this->parent_theme      = '__default__';
			$this->pictures          = [];
			$this->columns_disabled  = new ColumnsDisabled();
			return;
		}

		$this->check_parse_ini_file($config, $config_ini_file);

		$this->addon_type        = isset($config['addon_type']) ? $config['addon_type'] : '';
		$this->author_name       = isset($config['author']) ? $config['author'] : '';
		$this->author_mail       = isset($config['author_mail']) ? $config['author_mail'] : '';
		$this->author_link       = isset($config['author_link']) ? $config['author_link'] : '';
		$this->version           = isset($config['version']) ? $config['version'] : '';
		$this->creation_date     = isset($config['creation_date']) ? Date::to_format(strtotime($config['creation_date']),Date::FORMAT_DAY_MONTH_YEAR) : '';
		$this->last_update       = isset($config['last_update']) ? Date::to_format(strtotime($config['last_update']),Date::FORMAT_DAY_MONTH_YEAR) : '';
		$this->compatibility     = isset($config['compatibility']) ? $config['compatibility'] : '';
		$this->require_copyright = isset($config['require_copyright']) ? (bool)$config['require_copyright'] : '';
		$this->html_version      = isset($config['html_version']) ? $config['html_version'] : '';
		$this->css_version       = isset($config['css_version']) ? $config['css_version'] : '';
		$this->columns_disabled  = isset($config['columns_disabled']) ? $this->parse_columns_disabled_array($config['columns_disabled']) : new ColumnsDisabled();
		$this->variable_width    = isset($config['variable_width']) ? (bool)$config['variable_width'] : '';
		$this->width             = isset($config['width']) ? $config['width'] : '';
		$this->pictures          = isset($config['pictures']) ? $this->parse_pictures_array($config['pictures']) : [];
		$this->parent_theme      = isset($config['parent_theme']) && !empty($config['parent_theme']) ? $config['parent_theme'] : '__default__';
		$this->repository        = isset($config['repository']) && !empty($config['repository']) ? $config['repository'] : Updates::PHPBOOST_OFFICIAL_REPOSITORY;

	}

	private function load_description(mixed $desc_ini_file): void
	{
		if (empty($desc_ini_file))
		{
			$this->name = '';
			$this->description = '';
			$this->main_color = '';
			return;
		}
		$desc = @parse_ini_file($desc_ini_file);
		$this->check_parse_ini_file($desc, $desc_ini_file);
		$this->name = isset($desc['name']) ? $desc['name'] : '';
		$this->description = isset($desc['desc']) ? $desc['desc'] : '';
		$this->main_color = isset($desc['main_color']) ? $desc['main_color'] : '';
	}

	private function parse_pictures_array(string $pictures): array
	{
		return explode(',', $pictures);
	}

	private function parse_columns_disabled_array(string $columns_disabled): ColumnsDisabled
	{
		$columns_disabled_array = explode(',', $columns_disabled);
		$columns_disabled_class = new ColumnsDisabled();
		$columns_disabled_class->set_columns_disabled($columns_disabled_array);

		return $columns_disabled_class;
	}

	private function check_parse_ini_file(mixed $parse_result, mixed $ini_file): void
	{
		if ($parse_result === false)
		{
			throw new Exception('Parse ini file "' . $ini_file . '" failed');
		}
	}

	public function get_properties(): array
	{
		return [
			'addon_type'        => $this->addon_type,
			'name'              => $this->name,
			'description'       => $this->description,
			'main_color'        => $this->main_color,
			'author_name'       => $this->author_name,
			'author_mail'       => $this->author_mail,
			'author_link'       => $this->author_link,
			'version'           => $this->version,
			'compatibility'     => $this->compatibility,
			'creation_date'     => $this->creation_date,
			'last_update'       => $this->last_update,
			'require_copyright' => $this->require_copyright,
			'html_version'      => $this->html_version,
			'css_version'       => $this->css_version,
			'columns_disabled'  => $this->columns_disabled,
			'variable_width'    => $this->variable_width,
			'width'             => $this->width,
			'pictures'          => $this->pictures,
			'parent_theme'      => $this->parent_theme
		];
	}
}
?>
