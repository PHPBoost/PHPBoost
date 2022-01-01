<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 10 08
*/

class BugtrackerCommentsTopicEvents extends CommentsTopicEvents
{
	public function execute_add_comment_event()
	{
		//Load module lang
		$lang = LangLoader::get_all_langs('bugtracker');

		//Load module configuration
		$config = BugtrackerConfig::load();

		//Get the content of the comment
		$comment = stripslashes(FormatingHelper::strparse(AppContext::get_request()->get_poststring('comments_message', '')));

		//Retrieve the id of the bug
		$bug_id = $this->comments_topic->get_id_in_module();

		$now = new Date();

		//New line in the bug history
		BugtrackerService::add_history(array(
			'bug_id' => $bug_id,
			'updater_id' => AppContext::get_current_user()->get_id(),
			'update_date' => $now->get_timestamp(),
			'change_comment' => $lang['notice.new_comment'],
		));

		//Send a PM to the list of members who updated the bug if the send of PM is enabled
		if ($config->are_pm_enabled() && $config->are_pm_comment_enabled())
			BugtrackerPMService::send_PM_to_updaters('comment', $bug_id, $comment);

		return true;
	}
}
?>
