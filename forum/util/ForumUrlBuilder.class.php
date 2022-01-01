<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 20
 * @since       PHPBoost 4.1 - 2015 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ForumUrlBuilder
{
	private static $dispatcher = '/forum';

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
	public static function display_category($id, $rewrited_name)
	{
		return new Url('/forum/' . url('index.php?id=' . $id, 'cat-' . $id . '+' . $rewrited_name . '.php'));
	}

	/**
	 * @return Url
	 */
	public static function display_forum($id, $rewrited_name)
	{
		return new Url('/forum/' . url('forum.php?id=' . $id, 'forum-' . $id . '+' . $rewrited_name . '.php'));
	}

	/**
	 * @return Url
	 */
	public static function manage_ranks()
	{
		return new Url('/forum/admin_ranks.php');
	}

	/**
	 * @return Url
	 */
	public static function add_rank()
	{
		return new Url('/forum/admin_ranks_add.php');
	}

	/**
	 * @return Url
	 */
	public static function moderation_panel()
	{
		return new Url('/forum/moderation_forum.php');
	}

	/**
	 * @return Url
	 */
	public static function display_member_items()
	{
		return new Url('/forum/membermsg.php');
	}

	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}

	/**
	 * @return Url
	 */
	public static function show_no_answer()
	{
		return new Url('/forum/noanswer.php');
	}

}
?>
