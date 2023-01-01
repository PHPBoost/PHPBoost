<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 26
 * @since       PHPBoost 4.1 - 2015 01 27
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterDeleteArchiveController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		$id_stream = $request->get_int('id_stream', 0);

		if ($this->archive_exist($id) || $id_stream !== 0 && $id !== 0)
		{
			if (!NewsletterAuthorizationsService::id_stream($id_stream)->moderation_subscribers())
			{
				NewsletterAuthorizationsService::get_errors()->moderation_archives();
			}
			
			$row = PersistenceContext::get_querier()->select_single_row(NewsletterSetup::$newsletter_table_archives, array('*'), "WHERE id = '". $id ."'");
			
			NewsletterService::delete_archive($id);
			
			HooksService::execute_hook_action('delete', self::$module_id, array('id' => $id, 'title' => $row['subject']));

			AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : NewsletterUrlBuilder::archives($id_stream)), LangLoader::get_message('newsletter.archive.success.delete', 'common', 'newsletter'));
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $this->lang['newsletter.archive.not.exists']);
			DispatchManager::redirect($controller);
		}
	}

	private static function archive_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, "WHERE id = '" . $id . "'") > 0;
	}
}
?>
