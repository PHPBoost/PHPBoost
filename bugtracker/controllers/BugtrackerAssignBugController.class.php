<?php
/*##################################################
 *                      BugtrackerAssignBugController.class.php
 *                            -------------------
 *   begin                : January 18, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

class BugtrackerAssignBugController extends ModuleController
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
	
	private $assign = false;
	
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
					$redirect = ($this->assign ? BugtrackerUrlBuilder::detail_success('assign/' . $this->bug->get_id()) : BugtrackerUrlBuilder::detail($this->bug->get_id()));
					break;
				default :
					$redirect = ($this->assign ? BugtrackerUrlBuilder::unsolved_success('assign/' . $this->bug->get_id() . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '')) : BugtrackerUrlBuilder::unsolved($page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '')));
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
			$error_controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), $this->lang['bugs.error.e_unexist_bug']);
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
		
		$fieldset = new FormFieldsetHTML('assign_bug');
		$form->add_fieldset($fieldset);
		
		$user_assigned = '';
		if (UserService::user_exists('WHERE user_aprob = 1 AND user_id=:user_id', array('user_id' => $this->bug->get_assigned_to_id())))
			$user_assigned = UserService::get_user('WHERE user_aprob = 1 AND user_id=:user_id', array('user_id' => $this->bug->get_assigned_to_id()));
		
		$fieldset->add_field(new FormFieldAjaxUserAutoComplete('assigned_to', $this->lang['bugs.labels.fields.assigned_to_id'], !empty($user_assigned) ? $user_assigned->get_pseudo() : '', array(
			'maxlength' => 25, 'class' => 'field-large'), array(
			new FormFieldConstraintLengthRange(3, 25), 
			new FormFieldConstraintUserExist())
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('comments_message', LangLoader::get_message('comment', 'comments-common'), '', array(
			'description' => $this->lang['bugs.explain.assign_comment'], 'hidden' => !$this->config->are_pm_enabled() || !$this->config->are_pm_assign_enabled()
		)));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonLink(LangLoader::get_message('back', 'main'), 'javascript:history.back(1);'));
		
		$this->form = $form;
	}
	
	private function save()
	{
		$now = new Date();
		$status_list = $this->config->get_status_list();
		
		$assigned_to = $this->form->get_value('assigned_to');
		
		$old_assigned_to_id = $this->bug->get_assigned_to_id();
		$old_user_assigned = $old_assigned_to_id ? UserService::get_user("WHERE user_aprob = 1 AND user_id=:id", array('id' => $old_assigned_to_id)) : 0;
		$new_user_assigned = !empty($assigned_to) ? UserService::get_user("WHERE user_aprob = 1 AND login=:login", array('login' => $assigned_to)) : 0;
		
		if ($new_user_assigned != $old_user_assigned)
		{
			$this->assign = !empty($assigned_to);
			
			//Bug history update
			BugtrackerService::add_history(array(
				'bug_id'		=> $this->bug->get_id(),
				'updater_id'	=> $this->current_user->get_id(),
				'update_date'	=> $now->get_timestamp(),
				'updated_field'	=> 'assigned_to_id',
				'old_value'		=> !empty($old_user_assigned) ? '<a href="' . UserUrlBuilder::profile($old_user_assigned->get_id())->rel() . '" class="' . UserService::get_level_class($old_user_assigned->get_level()) . '">' . $old_user_assigned->get_pseudo() . '</a>' : $this->lang['bugs.notice.no_one'],
				'new_value'		=> !empty($new_user_assigned) ? '<a href="' . UserUrlBuilder::profile($new_user_assigned->get_id())->rel() . '" class="' . UserService::get_level_class($new_user_assigned->get_level()) . '">' . $new_user_assigned->get_pseudo() . '</a>' : $this->lang['bugs.notice.no_one']
			));
			
			//Bug update
			$this->bug->set_assigned_to_id($new_user_assigned ? $new_user_assigned->get_id() : 0);
			
			if ($new_user_assigned && ($this->bug->get_status() != Bug::FIXED) && ($this->bug->get_status() != Bug::REJECTED))
			{
				$this->bug->set_status(Bug::ASSIGNED);
				$this->bug->set_progress($status_list[Bug::ASSIGNED]);
			}
			else if ($old_user_assigned && !$new_user_assigned)
			{
				$this->bug->set_status(Bug::IN_PROGRESS);
				$this->bug->set_progress($status_list[Bug::IN_PROGRESS]);
			}
			
			BugtrackerService::update($this->bug);
			
			Feed::clear_cache('bugtracker');
			
			if ($this->config->are_pm_enabled() && $this->config->are_pm_assign_enabled() && $this->bug->get_assigned_to_id() && ($old_assigned_to_id != $this->bug->get_assigned_to_id()) && ($this->current_user->get_id() != $this->bug->get_assigned_to_id()))
			{
				//Add comment if needed
				$comment = !$this->form->field_is_disabled('comments_message') ? $this->form->get_value('comments_message', '') : '';
				if (!empty($comment))
				{
					//Send PM with comment to updaters if the option is enabled
					BugtrackerPMService::send_PM_to_updaters('assigned_with_comment', $this->bug->get_id(), stripslashes(FormatingHelper::strparse($comment)));
				}
				else
				{
					//Send PM to updaters if the option is enabled
					BugtrackerPMService::send_PM_to_updaters('assigned', $this->bug->get_id());
				}
			}
		}
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$body_view = BugtrackerViews::build_body_view($view, 'assign', $this->bug->get_id());
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.assign'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::assign($this->bug->get_id(), $back_page, $page, $back_filter, $filter_id));
		$response->set_page_title($this->lang['bugs.titles.assign'] . ' #' . $this->bug->get_id());
		
		return $response->display($body_view);
	}
}
?>
