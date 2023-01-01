<?php
/**
 * @package     Content
 * @subpackage  Hook
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 24
 * @since       PHPBoost 6.0 - 2021 09 14
*/

abstract class Hook implements ExtensionPoint
{
	const EXTENSION_POINT = 'hook';
	
	/**
	 * @desc Get the name of the Hook class.
	 */
	public function get_hook_name()
	{
		return static::class;
	}

	/**
	 * @desc Execute action after item add if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after item edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after item removal if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after category add if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the category (id, name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_category_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after category edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the category (id, name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_category_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after category removal if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the category (id, name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_category_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after item contribution if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_contribution_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after contribution edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the contribution (id, title, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_contribution_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after contribution removal if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the contribution (id, title, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_contribution_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after contribution process if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the contribution (id, title, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_process_contribution_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user warning if needed.
	 * @param string $user_id Id of the user that receives the warning
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_warning_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user punishment if needed.
	 * @param string $user_id Id of the user that receives the punishment
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_punishment_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user ban if needed.
	 * @param string $user_id Id of the user that receives the ban
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_ban_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user level changed by an admin if needed.
	 * @param string $user_id Id of the user whom level is changed
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_change_level_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user email change if needed.
	 * @param string $user_id Id of the user whom email is changed
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_change_email_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user display name change if needed.
	 * @param string $user_id Id of the user whom display name is changed
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_change_display_name_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after a new user registration if needed.
	 * @param string $user_id Id of the user who just registered
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_user_registration_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user account add if needed.
	 * @param string $user_id Id of the user who just registered
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_user_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after user account removal if needed.
	 * @param string $user_id Id of the user who just registered
	 * @param string[] $properties Properties of the user (id, display_name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_user_action($user_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after comment add if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the comment (id, message, url, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_add_comment_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after comment edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the comment (id, message, url, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_comment_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after comment removal if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the comment (id, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_delete_comment_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after notation on a item if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the notation (user_id, note, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_notation_action($module_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after configuration page edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties (optional) Properties of the configuration page (title, url, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_edit_config_action($module_id, array $properties = array(), $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after module/theme/lang installation if needed.
	 * @param string $installation_type Type of installed element (module, theme or lang)
	 * @param string $element_id Name of the current installed element
	 * @param string[] $properties (optional) Properties of the installed element (name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_install_action($installation_type, $element_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after module/theme/lang uninstallation if needed.
	 * @param string $uninstallation_type Type of uninstalled element (module, theme or lang)
	 * @param string $element_id Name of the current uninstalled element
	 * @param string[] $properties (optional) Properties of the uninstalled element (name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_uninstall_action($uninstallation_type, $element_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Execute action after module/theme/lang update if needed.
	 * @param string $upgrade_type Type of updated element (module, theme or lang)
	 * @param string $element_id Name of the current updated element
	 * @param string[] $properties (optional) Properties of the updated element (name, ...)
	 * @param string $description Optional description of the action
	 */
	public function on_update_action($upgrade_type, $element_id, array $properties, $description = '')
	{
		return true;
	}

	/**
	 * @desc Modify content before display if needed.
	 * @param string $module_id Name of the current module
	 * @param string $content Content displayed on the current page
	 * @param string[] $properties (optional) Properties of the item (title, content, ...)
	 */
	public function on_display_action($module_id, $content, array $properties = array())
	{
		return $content;
	}

	/**
	 * @desc Modify content before display if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties (optional) Properties of the user (display_name, ...)
	 */
	public function on_display_user_additional_informations_action($module_id, array $properties = array())
	{
		return '';
	}

	/**
	 * @desc Define a global function to treat all the modules specific hooks the same way. You can also define a specific function in your Hook class if you want to treat one or several of these module specific hook differently. For instance a specific function for on_forum_add_topic_action.
	 * @param string $action Name of the module specific hook
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the item (title, content, ...)
	 * @param string $description Optional description of the action
	 */
	public function execute_module_specific_hook_action($action, $module_id, array $properties, $description = '')
	{
		return true;
	}
}
?>
