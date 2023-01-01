<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 30
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class MenusProvidersService
{
	public static function get_menus($module_id)
	{
		if (self::module_containing_extension_point($module_id))
		{
			$provider = self::get_provider($module_id);
			return ($provider !== null ? $provider->get_menus($module_id) : array());
		}
	}

	public static function module_containing_extension_point($module_id)
	{
		return in_array($module_id, self::get_extension_point_ids());
	}

	public static function get_extension_point_ids()
	{
		return array_keys(self::get_extension_point());
	}

	public static function get_extension_point()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(MenusExtensionPoint::EXTENSION_POINT);
	}

	public static function get_provider($module_id)
	{
		if (self::module_containing_extension_point($module_id))
		{
			$extension_point = self::get_extension_point();
			return $extension_point[$module_id];
		}
	}
}
?>
