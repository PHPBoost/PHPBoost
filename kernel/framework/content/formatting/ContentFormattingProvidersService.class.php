<?php
/**
 * @package     Content
 * @subpackage  Formatting
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 3.0 - 2011 09 23
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContentFormattingProvidersService
{
	public static function create_factory(string $language)
	{
		return self::get_editor($language);
	}

	public static function get_editor(string $id): mixed
	{
		$editors = self::get_editors();
		if (array_key_exists($id, $editors))
		{
			return $editors[$id];
		}
        return null;
	}

	public static function get_editors(): array
	{
		$editors = [];
		foreach (self::get_extensions_point() as $id => $provider)
		{
			$editors[$id] = $provider;
		}
		return $editors;
	}

	public static function get_extensions_point(): mixed
	{
		return AppContext::get_extension_provider_service()->get_extension_point(ContentFormattingExtensionPoint::EXTENSION_POINT);
	}
}
?>
