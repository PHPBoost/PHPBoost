<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 16
 * @since       PHPBoost 3.0 - 2012 11 11
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class BugtrackerDeleteBugController extends ModuleController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	private $view;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;
	private $bug;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$this->init($request);

		$this->check_authorizations();

		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : BugtrackerUrlBuilder::unsolved()), StringVars::replace_vars(LangLoader::get_message('success.delete', 'common', 'bugtracker'), array('id' => $this->bug->get_id())));
		}

		$this->view->put('FORM', $this->form->display());
		return $this->build_response($this->view);
	}

	private function init(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$this->lang = LangLoader::get('common', 'bugtracker');

		try {
			$this->bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$error_controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_bug']);
			DispatchManager::redirect($error_controller);
		}

		$this->view = new StringTemplate('# INCLUDE FORM #');
		$this->view->add_lang($this->lang);
		$this->config = BugtrackerConfig::load();
	}

	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('delete_bug');
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRichTextEditor('comments_message', LangLoader::get_message('comment', 'comments-common'), '', array(
			'description' => $this->lang['explain.delete_comment'], 'hidden' => !$this->config->are_pm_enabled() || !$this->config->are_pm_delete_enabled()
		)));

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonLink(LangLoader::get_message('back', 'main'), 'javascript:history.back(1);'));

		$this->form = $form;
	}

	private function save()
	{
		$now = new Date();
		$current_user = AppContext::get_current_user();

		if ($this->config->are_pm_enabled() && $this->config->are_pm_delete_enabled())
		{
			//Add comment if needed
			$comment = !$this->form->field_is_disabled('comments_message') ? $this->form->get_value('comments_message', '') : '';

			//Send PM with comment to updaters if the option is enabled
			BugtrackerPMService::send_PM_to_updaters('delete', $this->bug->get_id(), $comment);
		}

		//Delete bug
		BugtrackerService::delete("WHERE id=:id", array('id' => $this->bug->get_id()));

		//Delete bug history
		BugtrackerService::delete_history("WHERE bug_id=:id", array('id' => $this->bug->get_id()));

		//Delete comments
		CommentsService::delete_comments_topic_module('bugtracker', $this->bug->get_id());

		//Delete admin alert
		if ($this->config->are_admin_alerts_enabled())
		{
			$alerts = AdministratorAlertService::find_by_criteria($this->bug->get_id(), 'bugtracker');
			if (!empty($alerts))
				AdministratorAlertService::delete_alert($alerts[0]);
		}

		BugtrackerStatsCache::invalidate();

		Feed::clear_cache('bugtracker');
	}

	private function build_response(View $view)
	{
		$body_view = BugtrackerViews::build_body_view($view, 'delete', $this->bug->get_id(), $this->bug->get_type());

		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.delete'] . ' #' . $this->bug->get_id(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::delete($this->bug->get_id()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.delete'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::delete($this->bug->get_id()));

		return $response;
	}
}
?>
