<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 10 08
 * @since       PHPBoost 3.0 - 2011 09 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminCustomizeUrlBuilder
{
    private static $dispatcher = '/customization';

	/**
	 * @return Url
	 */
    public static function customize_interface($theme = '')
	{
		return DispatchManager::get_url(self::$dispatcher, '/interface/'. $theme);
	}

	/**
	 * @return Url
	 */
    public static function customize_favicon()
	{
		return DispatchManager::get_url(self::$dispatcher, '/favicon');
	}

	/**
	 * @return Url
	 */
    public static function editor_css_file($theme = '', $file = '')
	{
		$url = !empty($file) ? $theme . '/' . $file : $theme;
		return DispatchManager::get_url(self::$dispatcher, '/editor/css/'. $url);
	}

	/**
	 * @return Url
	 */
    public static function editor_tpl_file($theme = '', $file = '')
	{
		$url = !empty($file) ? $theme . '/' . $file : $theme;
		return DispatchManager::get_url(self::$dispatcher, '/editor/tpl/'. $url);
	}
}
?>
