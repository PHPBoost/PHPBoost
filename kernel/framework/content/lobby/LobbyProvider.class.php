<?php
/**
 * @package     Content
 * @subpackage  Lobby
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

interface LobbyProvider extends ExtensionPoint
{
	const EXTENSION_POINT = 'lobby';

	/**
	 * Returns the lobby entry identifier.
	 * For modules without a category variant, equals get_phpboost_module_id().
	 * For a category sub-view, use a distinct key e.g. 'download_category'.
	 */
	public function get_module_id(): string;

    /**
	 * Returns the lobby entry name.
	 */
	public function get_module_name(): string;

	/**
	 * Returns the actual installed module folder name.
	 * Used for authorisation checks, config loading and module manager calls.
	 */
	public function get_phpboost_module_id(): string;

	/**
	 * Returns true when the module uses CategoriesService.
	 * Determines whether a HomeLandingModuleCategory instance is used.
	 */
	public function has_categories(): bool;

	/**
	 * Returns whether this provider represents a single-category sub-view.
	 * When true, LobbySetup and AdminLobbyAddModulesController will create a
	 * HomeLandingModuleCategory entry paired with the base module entry.
	 */
	public function is_category_view(): bool;

	/**
	 * Returns the FormField objects to inject into the lobby admin config form
	 * for this module entry.
	 *
	 * @param  LobbyModule $module  The current lobby module or module-category instance
	 * @return AbstractFormField[]
	 */
	public function get_config_fields(LobbyModule $module): array;

	/**
	 * Returns an associative array of field_id => hidden (bool) to apply
	 * after the config form is saved, to refresh field visibility.
	 *
	 * @param  LobbyModule $module
	 * @return array<string, bool>
	 */
	public function get_fields_visibility(LobbyModule $module): array;

	/**
	 * Persists submitted form values into the lobby module instance.
	 *
	 * @param HTMLForm    $form
	 * @param LobbyModule $module
	 */
	public function save(HTMLForm $form, LobbyModule $module): void;

	/**
	 * Builds and returns the FileTemplate to inject into the lobby home page.
	 *
	 * @return FileTemplate
	 */
	public function get_view(): FileTemplate;
}
?>
