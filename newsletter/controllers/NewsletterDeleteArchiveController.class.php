<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 01 27
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

			NewsletterService::delete_archive($id);

			AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : NewsletterUrlBuilder::archives($id_stream)), LangLoader::get_message('newsletter.message.success.delete', 'common', 'newsletter'));
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error-archive-not-existed']);
			DispatchManager::redirect($controller);
		}
	}

	private static function archive_exist($id)
	{
		return PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, "WHERE id = '" . $id . "'") > 0;
	}
}
?>
