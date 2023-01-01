<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 03 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterArchiveController extends DefaultModuleController
{
	private $content;

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form($request);
		$this->view = new StringTemplate($this->content);
		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$id = $request->get_int('id', 0);

		$archive_exist = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, "WHERE id = '" . $id . "'") > 0;
		if (!$archive_exist)
		{
			$controller = new UserErrorController($this->lang['warning.error'], $this->lang['newsletter.archive.not.exists']);
			DispatchManager::redirect($controller);
		}

		$id_stream = PersistenceContext::get_querier()->get_column_value(NewsletterSetup::$newsletter_table_archives, 'stream_id', "WHERE id = '". $id ."'");
		if (!NewsletterAuthorizationsService::id_stream($id_stream)->read_archives())
		{
			NewsletterAuthorizationsService::get_errors()->read_archives();
		}

		$this->content = NewsletterService::display_newsletter($id);
	}

	private function build_response(View $view)
	{
		return new SiteNodisplayResponse($view);
	}
}
?>
