<?php
/*##################################################
 *                      BugtrackerReopenBugController.class.php
 *                            -------------------
 *   begin                : November 10, 2012
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

class BugtrackerReopenBugController extends ModuleController
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
	private $current_user;
	
	public function execute(HTTPRequestCustom $request)
	{
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
					$redirect = BugtrackerUrlBuilder::detail_success('reopen/'. $this->bug->get_id());
					break;
				default :
					$redirect = BugtrackerUrlBuilder::solved_success('reopen/'. $this->bug->get_id() . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''));
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
		$this->current_user = AppContext::get_current_user();
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->moderation() && ($this->current_user->get_id() != $this->bug->get_assigned_to_id()))
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
		
		$fieldset = new FormFieldsetHTML('reject_bug');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRichTextEditor('comments_message', LangLoader::get_message('comment', 'comments-common'), '', array(
			'description' => $this->lang['explain.reopen_comment']
		)));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonLink(LangLoader::get_message('back', 'main'), 'javascript:history.back(1);'));
		
		$this->form = $form;
	}
	
	private function save()
	{
		$now = new Date();
		
		if (!$this->bug->is_reopen())
		{
			//Bug history update
			BugtrackerService::add_history(array(
				'bug_id'		=> $this->bug->get_id(),
				'updater_id'	=> $this->current_user->get_id(),
				'update_date'	=> $now->get_timestamp(),
				'updated_field'	=> 'status',
				'old_value'		=> $this->bug->get_status(),
				'new_value'		=> Bug::REOPEN
			));
			
			//Bug update
			$status = $this->bug->get_status();
			if ($this->bug->get_fixed_in())
				$status = Bug::IN_PROGRESS;
			else if ($this->bug->get_assigned_to_id())
				$status = Bug::ASSIGNED;
			else
				$status = Bug::REOPEN;
			
			$this->bug->set_status($status);
			$this->bug->set_fix_date(0);
			
			BugtrackerService::update($this->bug);
			
			Feed::clear_cache('bugtracker');
			
			//Add comment if needed
			$comment = $this->form->get_value('comments_message', '');
			if (!empty($comment))
			{
				$comments_topic = new BugtrackerCommentsTopic();
				$comments_topic->set_id_in_module($this->bug->get_id());
				$comments_topic->set_url(BugtrackerUrlBuilder::detail($this->bug->get_id()));
				CommentsManager::add_comment($comments_topic->get_module_id(), $comments_topic->get_id_in_module(), $comments_topic->get_topic_identifier(), $comments_topic->get_path(), $comment);
				
				//New line in the bug history
				BugtrackerService::add_history(array(
					'bug_id' => $this->bug->get_id(),
					'updater_id' => $this->current_user->get_id(),
					'update_date' => $now->get_timestamp(),
					'change_comment' => $this->lang['notice.new_comment'],
				));
				
				//Send PM with comment to updaters if the option is enabled
				if ($this->config->are_pm_enabled() && $this->config->are_pm_reopen_enabled())
					BugtrackerPMService::send_PM_to_updaters('reopen_with_comment', $this->bug->get_id(), stripslashes(FormatingHelper::strparse($comment)));
			}
			else
			{
				//Send PM to updaters if the option is enabled
				if ($this->config->are_pm_enabled() && $this->config->are_pm_reopen_enabled())
					BugtrackerPMService::send_PM_to_updaters('reopen', $this->bug->get_id());
			}
			
			//Change admin alert status
			if ($this->config->are_admin_alerts_enabled() && in_array($this->bug->get_severity(), $this->config->get_admin_alerts_levels()))
			{
				$alerts = AdministratorAlertService::find_by_criteria($this->bug->get_id(), 'bugtracker');
				if (!empty($alerts))
				{
					$alert = $alerts[0];
					$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD);
					AdministratorAlertService::save_alert($alert);
				}
			}
			
			BugtrackerStatsCache::invalidate();
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.e_already_reopen_bug', 'common', 'bugtracker'));
			DispatchManager::redirect($controller);
		}
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$body_view = BugtrackerViews::build_body_view($view, 'reopen', $this->bug->get_id());
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['titles.reopen'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::reopen($this->bug->get_id(), $back_page, $page, $back_filter, $filter_id));
		$response->set_page_title($this->lang['titles.reopen'] . ' #' . $this->bug->get_id());
		
		return $response->display($body_view);
	}
}
?>
