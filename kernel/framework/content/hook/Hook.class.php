<?php
/**
 * @package     Content
 * @subpackage  Hook
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 6.0 - 2021 09 14
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class Hook implements ExtensionPoint
{
	const EXTENSION_POINT = 'hook';

	/**
	 * Get the name of the Hook class.
	 */
	public function get_hook_name(): string
	{
		return static::class;
	}

	/**
	 * Execute action after item add if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after item edition if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after item removal if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after category add if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the category (id, name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_category_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after category edition if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the category (id, name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_category_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after category removal if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the category (id, name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_category_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after item contribution if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_contribution_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after contribution edition if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the contribution (id, title, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_contribution_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after contribution removal if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the contribution (id, title, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_contribution_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after contribution process if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the contribution (id, title, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_process_contribution_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user warning if needed.
	 * @param int $user_id Id of the user that receives the warning
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_warning_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user punishment if needed.
	 * @param int $user_id Id of the user that receives the punishment
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_punishment_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user ban if needed.
	 * @param int $user_id Id of the user that receives the ban
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_ban_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user level changed by an admin if needed.
	 * @param int $user_id Id of the user whom level is changed
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_change_level_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user email change if needed.
	 * @param int $user_id Id of the user whom email is changed
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_change_email_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user display name change if needed.
	 * @param int $user_id Id of the user whom display name is changed
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_change_display_name_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after a new user registration if needed.
	 * @param int $user_id Id of the user who just registered
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_registration_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user account add if needed.
	 * @param int $user_id Id of the user who just registered
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_user_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after user account removal if needed.
	 * @param int $user_id Id of the user who just registered
	 * @param array $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_user_action(int $user_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after comment add if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the comment (id, message, url, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_comment_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after comment edition if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the comment (id, message, url, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_comment_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after comment removal if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the comment (id, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_comment_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after notation on a item if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the notation (user_id, note, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_notation_action(string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after configuration page edition if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties (optional) Properties of the configuration page (title, url, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_config_action(string $module_id, array $properties = [], string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after module/theme/lang installation if needed.
	 * @param string $installation_type Type of installed element (module, theme or lang)
	 * @param string $element_id Name of the current installed element
	 * @param array $properties (optional) Properties of the installed element (name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_install_action(string $installation_type, string $element_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after module/theme/lang uninstallation if needed.
	 * @param string $uninstallation_type Type of uninstalled element (module, theme or lang)
	 * @param string $element_id Name of the current uninstalled element
	 * @param array $properties (optional) Properties of the uninstalled element (name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_uninstall_action(string $uninstallation_type, string $element_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Execute action after module/theme/lang update if needed.
	 * @param string $upgrade_type Type of updated element (module, theme or lang)
	 * @param string $element_id Name of the current updated element
	 * @param array $properties (optional) Properties of the updated element (name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_update_action(string $upgrade_type, string $element_id, array $properties, string $description = ''): bool
	{
		return true;
	}

	/**
	 * Modify content before display if needed.
	 * @param string $module_id Name of the current module
	 * @param string $content Content displayed on the current page
	 * @param array $properties (optional) Properties of the item (title, content, ...)
	 */
	public function on_display_action(string $module_id, string $content, array $properties = []): string
	{
		return $content;
	}

	/**
	 * Modify content before display if needed.
	 * @param string $module_id Name of the current module
	 * @param array $properties (optional) Properties of the user (display_name, ...)
	 */
	public function on_display_user_additional_informations_action(string $module_id, array $properties = []): string
	{
		return '';
	}

	/**
	 * Define a global function to treat all the modules specific hooks the same way. You can also define a specific function in your Hook class if you want to treat one or several of these module specific hook differently. For instance a specific function for on_forum_add_topic_action.
	 * @param string $action Name of the module specific hook
	 * @param string $module_id Name of the current module
	 * @param array $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function execute_module_specific_hook_action(string $action, string $module_id, array $properties, string $description = ''): bool
	{
		return true;
	}
}
?>
