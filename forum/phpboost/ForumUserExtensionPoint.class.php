<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 12
 * @since       PHPBoost 3.0 - 2010 10 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumUserExtensionPoint implements UserExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_view($user_id)
	{
		return Url::to_rel('/forum/membermsg.php?id=' . $user_id);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_name()
	{
		return LangLoader::get_message('forum.module.title', 'common', 'forum');
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_id()
	{
		return 'forum';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_module_icon()
	{
		return 'fa fa-globe';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_publications_number($user_id)
	{
		return PersistenceContext::get_querier()->count(PREFIX . 'forum_msg', 'WHERE user_id = :user_id', array('user_id' => $user_id));
	}
}
?>
