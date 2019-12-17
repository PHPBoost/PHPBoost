<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 03 21
*/

class NewsletterArchiveController extends ModuleController
{
	private $lang;
	private $view;
	private $contents;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		return $this->build_response($this->view);
	}

	private function build_form($request)
	{
		$id = $request->get_int('id', 0);

		$archive_exist = PersistenceContext::get_querier()->count(NewsletterSetup::$newsletter_table_archives, "WHERE id = '" . $id . "'") > 0;
		if (!$archive_exist)
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error-archive-not-existed']);
			DispatchManager::redirect($controller);
		}

		$id_stream = PersistenceContext::get_querier()->get_column_value(NewsletterSetup::$newsletter_table_archives, 'stream_id', "WHERE id = '". $id ."'");
		if (!NewsletterAuthorizationsService::id_stream($id_stream)->read_archives())
		{
			NewsletterAuthorizationsService::get_errors()->read_archives();
		}

		$this->contents = NewsletterService::display_newsletter($id);
	}

	private function init($request)
	{
		$this->lang = LangLoader::get('common', 'newsletter');
		$this->build_form($request);
		$this->view = new StringTemplate($this->contents);
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		return new SiteNodisplayResponse($view);
	}
}
?>
