<?php
/**
 * @package     Content
 * @subpackage  Hook
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 20
 * @since       PHPBoost 6.0 - 2021 09 14
*/

abstract class Hook implements ExtensionPoint
{
	const EXTENSION_POINT = 'hook';
	
	/** Get the name of the Hook class **/
	public function get_hook_name()
	{
		return static::class;
	}

	/** Execute action after item add if needed **/
	public function on_add_action($module_id, $id_in_module, $title, $content = '', $author = '', $other_parameters = array())
	{
		return true;
	}

	/** Execute action after item edition if needed **/
	public function on_edit_action($module_id, $id_in_module, $title, $content = '', $author = '', $other_parameters = array())
	{
		return true;
	}

	/** Execute action after item removal if needed **/
	public function on_delete_action($module_id, $id_in_module, $title, $content = '', $author = '', $other_parameters = array())
	{
		return true;
	}

	/** Modify content before display if needed **/
	public function on_display_action($module_id, $id_in_module, $content = '', $other_parameters = array())
	{
		return $content;
	}
}
?>
