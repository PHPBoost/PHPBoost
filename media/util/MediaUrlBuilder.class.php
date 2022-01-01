<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 07
 * @since       PHPBoost 4.0 - 2015 02 02
*/

class MediaUrlBuilder
{
	private static $dispatcher = '/media';

	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}

	/**
	 * @return Url
	 */
	public static function manage()
	{
		return new Url(PATH_TO_ROOT. '/media/moderation_media.php');
	}

	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $page = 1, $subcategories_page = 1)
	{
		$page = $page !== 1 || $subcategories_page !== 1 ? '&p=' . $page : '';
		return new Url(PATH_TO_ROOT. '/media/' . url('media.php?cat=' . $id . $page, 'media-0-' . $id . ($page > 1 ? '-' . $page : '') . '+' . $rewrited_name . '.php'));
	}

	/**
	 * @return Url
	 */
	public static function add($id_category = null)
	{
		return new Url(PATH_TO_ROOT. '/media/media_action.php');
	}

	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
