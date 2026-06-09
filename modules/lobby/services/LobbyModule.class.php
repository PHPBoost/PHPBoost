<?php
/**
 * @package     Lobby
 * @subpackage  Services
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class LobbyModule
{
	const ELEMENTS_NUMBER_DISPLAYED   = 5;
	const CHARACTERS_NUMBER_DISPLAYED = 128;

	private bool   $displayed                    = false;
	private string $module_id                    = '';
	private string $module_name                  = '';
	private string $phpboost_module_id           = '';
	private int    $elements_number_displayed    = self::ELEMENTS_NUMBER_DISPLAYED;
	private int    $characters_number_displayed  = self::CHARACTERS_NUMBER_DISPLAYED;
	private bool   $has_category                 = false;
	private        $id_category                  = 0;
	private bool   $subcategories_content_displayed = false;

	// -------------------------------------------------------------------------
	// Display
	// -------------------------------------------------------------------------

	public function display(): void
	{
		$this->displayed = true;
	}

	public function hide(): void
	{
		$this->displayed = false;
	}

	public function is_displayed(): bool
	{
		if (!empty($this->phpboost_module_id))
		{
			return ModulesManager::is_module_installed($this->phpboost_module_id)
				&& ModulesManager::is_module_activated($this->phpboost_module_id)
				&& $this->displayed;
		}
		return $this->displayed;
	}

	public function is_active(): bool
	{
		if (empty($this->phpboost_module_id))
		{
			return true;
		}
		return ModulesManager::is_module_installed($this->phpboost_module_id)
			&& ModulesManager::is_module_activated($this->phpboost_module_id);
	}

	// -------------------------------------------------------------------------
	// Name
	// -------------------------------------------------------------------------

	public function set_module_name(string $module_name): void
	{
		$this->module_name = $module_name;
	}

	public function get_module_name(): string
	{
		return $this->module_name;
	}

	// -------------------------------------------------------------------------
	// Module ids
	// -------------------------------------------------------------------------

	public function set_module_id(string $module_id): void
	{
		$this->module_id = $module_id;
	}

	public function get_module_id(): string
	{
		return $this->module_id;
	}

	public function set_phpboost_module_id(string $phpboost_module_id): void
	{
		$this->phpboost_module_id = $phpboost_module_id;
	}

	public function get_phpboost_module_id(): string
	{
		return $this->phpboost_module_id;
	}

	// -------------------------------------------------------------------------
	// Items display settings
	// -------------------------------------------------------------------------

	public function set_elements_number_displayed(int $number): void
	{
		$this->elements_number_displayed = $number;
	}

	public function get_elements_number_displayed(): int
	{
		return $this->elements_number_displayed;
	}

	public function set_characters_number_displayed(int $number): void
	{
		$this->characters_number_displayed = $number;
	}

	public function get_characters_number_displayed(): int
	{
		return $this->characters_number_displayed;
	}

	// -------------------------------------------------------------------------
	// Category settings (only relevant when has_category = true)
	// -------------------------------------------------------------------------

	public function set_has_category(bool $has_category): void
	{
		$this->has_category = $has_category;
	}

	public function has_category(): bool
	{
		return $this->has_category;
	}

	public function set_id_category($id_category): void
	{
		$this->id_category = (int) $id_category;
	}

	public function get_id_category(): int
	{
		return (int) $this->id_category;
	}

	public function display_subcategories_content(): void
	{
		$this->subcategories_content_displayed = true;
	}

	public function hide_subcategories_content(): void
	{
		$this->subcategories_content_displayed = false;
	}

	public function is_subcategories_content_displayed(): bool
	{
		return $this->subcategories_content_displayed;
	}

	// -------------------------------------------------------------------------
	// Serialisation
	// -------------------------------------------------------------------------

	public function get_properties(): array
	{
		$props = [
			'displayed'                   => (int) $this->is_displayed(),
			'module_id'                   => $this->module_id,
			'module_name'                 => $this->module_name,
			'phpboost_module_id'          => $this->phpboost_module_id,
			'elements_number_displayed'   => $this->elements_number_displayed,
			'characters_number_displayed' => $this->characters_number_displayed,
			'has_category'                => (int) $this->has_category,
		];

		if ($this->has_category)
		{
			$props['id_category']                    = $this->id_category;
			$props['subcategories_content_displayed'] = (int) $this->subcategories_content_displayed;
		}

		return $props;
	}

	public function set_properties(array $properties): void
	{
		$this->displayed                   = (bool) $properties['displayed'];
		$this->module_id                   = $properties['module_id'];
		$this->module_name                 = $properties['module_name'];
		$this->phpboost_module_id          = $properties['phpboost_module_id'] ?? '';
		$this->elements_number_displayed   = (int) ($properties['elements_number_displayed'] ?? self::ELEMENTS_NUMBER_DISPLAYED);
		$this->characters_number_displayed = (int) ($properties['characters_number_displayed'] ?? self::CHARACTERS_NUMBER_DISPLAYED);
		$this->has_category                = (bool) ($properties['has_category'] ?? false);

		if ($this->has_category)
		{
			$this->id_category                    = (int) ($properties['id_category'] ?? Category::ROOT_CATEGORY);
			$this->subcategories_content_displayed = (bool) ($properties['subcategories_content_displayed'] ?? false);
		}
	}
}
?>
