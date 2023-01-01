<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 16
*/

class ForumScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		//Suppression des marqueurs de vue du forum trop anciens.
		PersistenceContext::get_querier()->delete(PREFIX . 'forum_view',
			'WHERE timestamp < :limit', array('limit' => time() - (ForumConfig::load()->get_read_messages_storage_duration() * 3600 * 24)));
	}
}
?>
