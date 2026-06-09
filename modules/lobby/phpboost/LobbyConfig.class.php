<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyConfig extends AbstractConfigData
{
	// -------------------------------------------------------------------------
	// Config keys
	// -------------------------------------------------------------------------

	const MODULE_TITLE   = 'module_title';

    const MODULES_LIST   = 'modules_list';

    const LEFT_COLUMNS   = 'left_columns';
	const RIGHT_COLUMNS  = 'right_columns';
	const TOP_CENTRAL    = 'top_central';
	const BOTTOM_CENTRAL = 'bottom_central';
	const TOP_FOOTER     = 'top_footer';


	// Built-in (non-provider) module ids
	const MODULE_ANCHORS_MENU = 'anchors_menu';
	const ANCHORS_MENU        = 'anchors_menu';

    const MODULE_EDITO = 'edito';
	const EDITO        = 'edito';

	const MODULE_CAROUSEL = 'carousel';
	const CAROUSEL        = 'carousel';
	const CAROUSEL_SPEED  = 'carousel_speed';
	const CAROUSEL_TIME   = 'carousel_time';
	const CAROUSEL_NUMBER = 'carousel_number';
	const CAROUSEL_AUTO   = 'carousel_auto';
	const CAROUSEL_HOVER  = 'carousel_hover';
	const CAROUSEL_TRUE   = 'true';
	const CAROUSEL_FALSE  = 'false';

	const MODULE_LASTCOMS     = 'lastcoms';

	// -------------------------------------------------------------------------
	// Module title
	// -------------------------------------------------------------------------

	public function get_module_title(): string
	{
		return $this->get_property(self::MODULE_TITLE);
	}

	public function set_module_title(string $v): void
	{
		$this->set_property(self::MODULE_TITLE, $v);
	}

	// -------------------------------------------------------------------------
	// Layout columns
	// -------------------------------------------------------------------------

	public function get_left_columns(): bool   { return $this->get_property(self::LEFT_COLUMNS); }
	public function set_left_columns(bool $v): void { $this->set_property(self::LEFT_COLUMNS, $v); }

	public function get_right_columns(): bool  { return $this->get_property(self::RIGHT_COLUMNS); }
	public function set_right_columns(bool $v): void { $this->set_property(self::RIGHT_COLUMNS, $v); }

	public function get_top_central(): bool    { return $this->get_property(self::TOP_CENTRAL); }
	public function set_top_central(bool $v): void { $this->set_property(self::TOP_CENTRAL, $v); }

	public function get_bottom_central(): bool { return $this->get_property(self::BOTTOM_CENTRAL); }
	public function set_bottom_central(bool $v): void { $this->set_property(self::BOTTOM_CENTRAL, $v); }

	public function get_top_footer(): bool     { return $this->get_property(self::TOP_FOOTER); }
	public function set_top_footer(bool $v): void { $this->set_property(self::TOP_FOOTER, $v); }

	// -------------------------------------------------------------------------
	// Modules list
	// -------------------------------------------------------------------------

	public function get_modules(): array
	{
		return $this->get_property(self::MODULES_LIST);
	}

	public function set_modules(array $array): void
	{
		$this->set_property(self::MODULES_LIST, $array);
	}

	public function get_module_position_by_id(string $module_id): ?int
	{
		foreach ($this->get_modules() as $key => $module)
		{
			if ($module['module_id'] == $module_id)
			{
				return $key;
			}
		}
		return null;
	}

	// -------------------------------------------------------------------------
	// Anchors menu
	// -------------------------------------------------------------------------

	public function get_anchors_menu(): bool { return $this->get_property(self::ANCHORS_MENU); }
	public function set_anchors_menu(bool $v): void { $this->set_property(self::ANCHORS_MENU, $v); }

	// -------------------------------------------------------------------------
	// Carousel
	// -------------------------------------------------------------------------

	public function get_carousel(): array   { return $this->get_property(self::CAROUSEL); }
	public function set_carousel(array $v): void { $this->set_property(self::CAROUSEL, $v); }

	public function get_carousel_speed(): int  { return $this->get_property(self::CAROUSEL_SPEED); }
	public function set_carousel_speed(int $v): void { $this->set_property(self::CAROUSEL_SPEED, $v); }

	public function get_carousel_time(): int   { return $this->get_property(self::CAROUSEL_TIME); }
	public function set_carousel_time(int $v): void { $this->set_property(self::CAROUSEL_TIME, $v); }

	public function get_carousel_number(): int { return $this->get_property(self::CAROUSEL_NUMBER); }
	public function set_carousel_number(int $v): void { $this->set_property(self::CAROUSEL_NUMBER, $v); }

	public function get_carousel_auto(): string  { return $this->get_property(self::CAROUSEL_AUTO); }
	public function set_carousel_auto(string $v): void { $this->set_property(self::CAROUSEL_AUTO, $v); }

	public function get_carousel_hover(): string { return $this->get_property(self::CAROUSEL_HOVER); }
	public function set_carousel_hover(string $v): void { $this->set_property(self::CAROUSEL_HOVER, $v); }

	// -------------------------------------------------------------------------
	// Edito
	// -------------------------------------------------------------------------

	public function get_edito(): string { return $this->get_property(self::EDITO); }
	public function set_edito(string $v): void { $this->set_property(self::EDITO, $v); }

	// -------------------------------------------------------------------------
	// Default values — only built-in entries; dynamic providers are added by LobbySetup
	// -------------------------------------------------------------------------

	private static function init_modules_array(): array
	{
		$i       = 1;
		$modules = [];

		$module = new LobbyModule();
		$module->set_module_id(self::MODULE_ANCHORS_MENU);
		$module->set_module_name(LangLoader::get_message('lobby.module.anchors.menu', 'common', 'lobby'));
		$modules[$i++] = $module->get_properties();

		$module = new LobbyModule();
		$module->set_module_id(self::MODULE_CAROUSEL);
		$module->set_module_name(LangLoader::get_message('lobby.module.carousel', 'common', 'lobby'));
		$modules[$i++] = $module->get_properties();

		$module = new LobbyModule();
		$module->set_module_id(self::MODULE_EDITO);
		$module->set_module_name(LangLoader::get_message('lobby.module.edito', 'common', 'lobby'));
		$module->display();
		$modules[$i++] = $module->get_properties();

		$module = new LobbyModule();
		$module->set_module_id(self::MODULE_LASTCOMS);
		$module->set_module_name(LangLoader::get_message('lobby.module.lastcoms', 'common', 'lobby'));
		$module->set_characters_number_displayed(30);
		$modules[$i] = $module->get_properties();

		return $modules;
	}

	public function get_default_values(): array
	{
		return [
			self::MODULE_TITLE => LangLoader::get_message('lobby.title', 'common', 'lobby'),

			self::LEFT_COLUMNS   => false,
			self::RIGHT_COLUMNS  => true,
			self::TOP_CENTRAL    => true,
			self::BOTTOM_CENTRAL => true,
			self::TOP_FOOTER     => true,

			self::MODULES_LIST => self::init_modules_array(),

			self::ANCHORS_MENU => false,

			self::CAROUSEL        => [],
			self::CAROUSEL_SPEED  => 200,
			self::CAROUSEL_TIME   => 5000,
			self::CAROUSEL_NUMBER => 4,
			self::CAROUSEL_AUTO   => self::CAROUSEL_TRUE,
			self::CAROUSEL_HOVER  => self::CAROUSEL_TRUE,

			self::EDITO => LangLoader::get_message('lobby.edito.description', 'install', 'lobby'),
		];
	}

	public static function load(): self
	{
		return ConfigManager::load(self::class, 'lobby', 'config');
	}

	public static function save(): void
	{
		ConfigManager::save('lobby', self::load(), 'config');
	}
}
?>
