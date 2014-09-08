<?php
/*##################################################
 *                      BugtrackerDeleteBugController.class.php
 *                            -------------------
 *   begin                : November 11, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
		
		$this->build_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			
			$back_page = $request->get_value('back_page', '');
			$page = $request->get_int('page', 1);
			$back_filter = $request->get_value('back_filter', '');
			$filter_id = $request->get_int('filter_id', 0);
			
			switch ($back_page)
			{
				case 'detail' :
					$redirect = BugtrackerUrlBuilder::detail_success('delete', $this->bug->get_id());
					break;
				case 'solved' :
					$redirect = BugtrackerUrlBuilder::solved_success('delete', $this->bug->get_id(), $page, $back_filter, $filter_id);
					break;
				default :
					$redirect = BugtrackerUrlBuilder::unsolved_success('delete', $this->bug->get_id(), $page, $back_filter, $filter_id);
					break;
			}
			
			AppContext::get_response()->redirect($redirect);
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
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('delete_bug');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRichTextEditor('comments_message', LangLoader::get_message('comment', 'comments-common'), '', array(
			'description' => $this->lang['explain.delete_comment'], 'hidden' => !$this->config->are_pm_enabled() || !$this->config->are_pm_delete_enabled()
		)));
		
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
		$request = AppContext::get_request();
		
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$body_view = BugtrackerViews::build_body_view($view, 'delete', $this->bug->get_id());
		
		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.delete'] . ' #' . $this->bug->get_id());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::delete($this->bug->get_id()));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.delete'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::delete($this->bug->get_id(), $back_page, $page, $back_filter, $filter_id));
		
		return $response;
	}
}
?>
