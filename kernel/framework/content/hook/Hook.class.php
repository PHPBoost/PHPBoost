<?php
/**
 * @package     Content
 * @subpackage  Hook
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 28
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
	 */
	public function on_add_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after item edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the item (title, content, ...)
	 */
	public function on_edit_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after item removal if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the item (title, content, ...)
	 */
	public function on_delete_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after category add if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the category (id, name, ...)
	 */
	public function on_add_category_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after category edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the category (id, name, ...)
	 */
	public function on_edit_category_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after category removal if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the category (id, name, ...)
	 */
	public function on_delete_category_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after comment add if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the comment (id, message, url, ...)
	 */
	public function on_add_comment_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after comment edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the comment (id, message, url, ...)
	 */
	public function on_edit_comment_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after comment removal if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the comment (id, ...)
	 */
	public function on_delete_comment_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after notation on a item if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties Properties of the notation (user_id, note, ...)
	 */
	public function on_notation_action($module_id, array $properties)
	{
		return true;
	}

	/**
	 * @desc Execute action after config page edition if needed.
	 * @param string $module_id Name of the current module
	 * @param string[] $properties (optional) Properties of the item (title, content, ...)
	 */
	public function on_edit_config_action($module_id, array $properties = array())
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
}
?>
