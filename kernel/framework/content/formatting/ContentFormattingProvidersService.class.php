<?php
/**
 * @package     Content
 * @subpackage  Formatting
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 09 23
*/

class ContentFormattingProvidersService
{
	public static function create_factory($language)
	{
		return self::get_editor($language);
	}

	public static function get_editor($id)
	{
		$editors = self::get_editors();
		if (array_key_exists($id, $editors))
		{
			return $editors[$id];
		}
	}

	public static function get_editors()
	{
		$editors = array();
		foreach (self::get_extensions_point() as $id => $provider)
		{
			$editors[$id] = $provider;
		}
		return $editors;
	}

	public static function get_extensions_point()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(ContentFormattingExtensionPoint::EXTENSION_POINT);
	}
}
?>
