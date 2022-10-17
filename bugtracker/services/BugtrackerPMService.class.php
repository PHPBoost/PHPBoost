<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 10 12
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class BugtrackerPMService
{
	/**
	 * @desc Send a PM to a member.
	 * @param string $pm_type Type of PM ('assigned', 'comment', 'pending', 'in_progress', 'delete', 'edit', 'fixed', 'rejected', 'reopen')
	 * @param int $recipient_id ID of the PM's recipient
	 * @param int $bug_id ID of the bug which is concerned
	 * @param string $message (optional) Message to include in the PM
	 */
	public static function send_PM($pm_type, $recipient_id, $bug_id, $message = '')
	{
		//Load module lang
		$lang = LangLoader::get_all_langs('bugtracker');

		//Send the PM if the recipient is not a guest
		if ($recipient_id > 0)
		{
			//Get current user
			$current_user = AppContext::get_current_user();

			$author = $current_user->get_id() != User::VISITOR_LEVEL ? $current_user->get_display_name() : $lang['user.guest'];

			$pm_content = StringVars::replace_vars($lang['pm.' . $pm_type . '.content'], array('author' => $author, 'id' => $bug_id)) . (!empty($message) ? ($pm_type != 'edit' ? StringVars::replace_vars($lang['pm.with_comment'], array('comment' => $message)) : StringVars::replace_vars($lang['pm.edit_fields'], array('fields' => $message))) : '') . ($pm_type != 'delete' ? StringVars::replace_vars($lang['pm.bug_link'], array('link' => BugtrackerUrlBuilder::detail($bug_id)->relative())) : '');

			//Send the PM
			PrivateMsg::start_conversation(
				$recipient_id,
				StringVars::replace_vars($lang['pm.' . $pm_type . '.title'], array('id' => $bug_id)),
				$pm_content,
				-1,
				PrivateMsg::SYSTEM_PM
			);
		}
	}

	/**
	 * @desc Send a PM to a list of members.
	 * @param string $pm_type Type of PM ('assigned', 'pending', 'in_progress', 'comment', 'delete', 'edit', 'fixed', 'rejected', 'reopen')
	 * @param int $bug_id ID of the bug which is concerned
	 * @param string $message (optional) Message to include in the PM
	 * @param string[] $recipients_list (optional) Recipients to whom send the PM
	 */
	public static function send_PM_to_updaters($pm_type, $bug_id, $message = '', $recipients_list = array())
	{
		//Load configuration
		$config = BugtrackerConfig::load();
		$pm_enabled = $config->are_pm_enabled();

		//Check is the sending of PM is enabled for the selected type
		$pm_type_enabled = '';
		switch ($pm_type)
		{
			case 'assigned':
				$pm_type_enabled = $config->are_pm_assign_enabled();
				break;
			case 'pending':
				$pm_type_enabled = $config->are_pm_pending_enabled();
				break;
			case 'in_progress':
				$pm_type_enabled = $config->are_pm_in_progress_enabled();
				break;
			case 'comment':
				$pm_type_enabled = $config->are_pm_comment_enabled();
				break;
			case 'delete':
				$pm_type_enabled = $config->are_pm_delete_enabled();
				break;
			case 'edit':
				$pm_type_enabled = $config->are_pm_edit_enabled();
				break;
			case 'fixed':
				$pm_type_enabled = $config->are_pm_fix_enabled();
				break;
			case 'rejected':
				$pm_type_enabled = $config->are_pm_reject_enabled();
				break;
			case 'reopen':
				$pm_type_enabled = $config->are_pm_reopen_enabled();
				break;
		}

		//Retrieve the list of members which updated the bug
		if (empty($recipients_list))
			$recipients_list = BugtrackerService::get_updaters_list($bug_id);

		//Send the PM to each recipient
		foreach ($recipients_list as $recipient)
		{
			if ($pm_enabled && $pm_type_enabled)
			{
				self::send_PM($pm_type, $recipient, $bug_id, $message);
			}
		}
	}
}
?>
