<?php
/*##################################################
 *                      BugtrackerEditController.class.php
 *                            -------------------
 *   begin                : November 13, 2012
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

class BugtrackerEditController extends ModuleController
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
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$this->view->put('FORM', $this->form->display());
		return $this->build_response($this->view);
	}
	
	private function init()
	{
		$request = AppContext::get_request();
		
		$id = $request->get_int('id', 0);
		
		$this->current_user = AppContext::get_current_user();
		$this->config = BugtrackerConfig::load();
		$this->lang = LangLoader::get('common', 'bugtracker');
		
		try {
			$this->bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$error_controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), $this->lang['bugs.error.e_unexist_bug']);
			DispatchManager::redirect($error_controller);
		}
		
		$this->view = new StringTemplate('# INCLUDE FORM #');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!(BugtrackerAuthorizationsService::check_authorizations()->moderation() || (BugtrackerAuthorizationsService::check_authorizations()->write() && $this->bug->get_author_user()->get_id() == $this->current_user->get_id()) || ($this->bug->get_assigned_to_id() && $this->current_user->get_id() == $this->bug->get_assigned_to_id())))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		if ($this->current_user->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}
	
	private function build_form()
	{
		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$versions = $this->config->get_versions();
		$severities = $this->config->get_severities();
		$priorities = $this->config->get_priorities();
		
		$versions = array_reverse($versions, true);
		
		$versions_detected_in = $this->config->get_versions_detected();
		$versions_detected_in = array_reverse($versions_detected_in, true);
		
		$display_types = sizeof($types) > 1 ? true : false;
		$display_categories = sizeof($categories) > 1 ? true : false;
		$display_priorities = sizeof($priorities) > 1 ? true : false;
		$display_severities = sizeof($severities) > 1 ? true : false;
		$display_versions = sizeof($versions) > 1 ? true : false;
		$display_versions_detected_in = sizeof($versions_detected_in) > 1 ? true : false;
		
		$default_type = $this->config->get_default_type();
		$default_category = $this->config->get_default_category();
		$default_priority = $this->config->get_default_priority();
		$default_severity = $this->config->get_default_severity();
		$default_version = $this->config->get_default_version();
		
		$form = new HTMLForm(__CLASS__);
		
		if (BugtrackerAuthorizationsService::check_authorizations()->moderation() || ($this->current_user->get_id() == $this->bug->get_assigned_to_id()))
		{
			$fieldset = new FormFieldsetHTML('bug_treatment', $this->lang['bugs.titles.bugs_treatment']);
			$form->add_fieldset($fieldset);
			
			$fieldset->add_field(new FormFieldCheckbox('fixed', $this->lang['bugs.labels.fixed'], $this->bug->is_fixed() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED));
			
			$user_assigned = '';
			if (UserService::user_exists('WHERE user_aprob = 1 AND user_id=:user_id', array('user_id' => $this->bug->get_assigned_to_id())))
				$user_assigned = UserService::get_user('WHERE user_aprob = 1 AND user_id=:user_id', array('user_id' => $this->bug->get_assigned_to_id()));
			
			$fieldset->add_field(new FormFieldAjaxUserAutoComplete('assigned_to', $this->lang['bugs.labels.fields.assigned_to_id'], !empty($user_assigned) ? $user_assigned->get_pseudo() : '', array(
				'maxlength' => 25, 'size' => 25)
			));
			
			//Fix versions
			if (sizeof($versions))
			{
				$array_versions = array();
				$array_versions[] = new FormFieldSelectChoiceOption('', 0);
				foreach ($versions as $key => $version)
				{
					if ($key >= $this->bug->get_detected_in())
						$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key);
				}
				
				$fieldset->add_field(new FormFieldSimpleSelectChoice('fixed_in', $this->lang['bugs.labels.fields.fixed_in'], $this->bug->get_fixed_in(), $array_versions));
			}
		}
		
		$fieldset = new FormFieldsetHTML('bug_infos', $this->lang['bugs.titles.bugs_infos']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['bugs.labels.fields.title'], $this->bug->get_title(), array(
			'class' => 'text', 'maxlength' => 200, 'size' => 50, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['bugs.labels.fields.contents'], $this->bug->get_contents(), array(
			'description' => $this->lang['bugs.explain.contents'], 'rows' => 15, 'required' => true)
		));
		
		//Types
		if ($display_types)
		{
			$array_types = array();
			if (empty($default_type))
			{
				$array_types[] = new FormFieldSelectChoiceOption('', '');
			}
			foreach ($types as $key => $type)
			{
				$array_types[] = new FormFieldSelectChoiceOption(stripslashes($type), $key);
			}
			
			$fieldset->add_field(new FormFieldSimpleSelectChoice('type', $this->lang['bugs.labels.fields.type'], $this->bug->get_type(), $array_types, array(
				'required' => $this->config->is_type_mandatory())
			));
		}
		
		//Categories
		if ($display_categories)
		{
			$array_categories = array();
			if (empty($default_category))
			{
				$array_categories[] = new FormFieldSelectChoiceOption('', '');
			}
			foreach ($categories as $key => $category)
			{
				$array_categories[] = new FormFieldSelectChoiceOption(stripslashes($category), $key);
			}
			
			$fieldset->add_field(new FormFieldSimpleSelectChoice('category', $this->lang['bugs.labels.fields.category'], $this->bug->get_category(), $array_categories, array(
				'required' => $this->config->is_category_mandatory())
			));
		}
		
		if(BugtrackerAuthorizationsService::check_authorizations()->advanced_write())
		{
			//Severities
			if ($display_severities)
			{
				$array_severities = array();
				if (empty($default_severity))
				{
					$array_severities[] = new FormFieldSelectChoiceOption('', '');
				}
				foreach ($severities as $key => $severity)
				{
					$array_severities[] = new FormFieldSelectChoiceOption(stripslashes($severity['name']), $key);
				}
				
				$fieldset->add_field(new FormFieldSimpleSelectChoice('severity', $this->lang['bugs.labels.fields.severity'], $this->bug->get_severity(), $array_severities, array(
					'required' => $this->config->is_severity_mandatory())
				));
			}
			
			//Priorities
			if ($display_priorities)
			{
				$array_priorities = array();
				if (empty($default_priority))
				{
					$array_priorities[] = new FormFieldSelectChoiceOption('', '');
				}
				foreach ($priorities as $key => $priority)
				{
					$array_priorities[] = new FormFieldSelectChoiceOption(stripslashes($priority), $key);
				}
				
				$fieldset->add_field(new FormFieldSimpleSelectChoice('priority', $this->lang['bugs.labels.fields.priority'], $this->bug->get_priority(), $array_priorities, array(
					'required' => $this->config->is_priority_mandatory())
				));
			}
		}
		
		//Detected versions
		if ($display_versions_detected_in)
		{
			$array_versions = array();
			if (empty($default_version))
			{
				$array_versions[] = new FormFieldSelectChoiceOption('', '');
			}
			foreach ($versions_detected_in as $key => $version)
			{
				$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key);
			}
			
			$fieldset->add_field(new FormFieldSimpleSelectChoice('detected_in', $this->lang['bugs.labels.fields.detected_in'], $this->bug->get_detected_in(), $array_versions, array(
				'required' => $this->config->is_detected_in_version_mandatory())
			));
		}
		
		$fieldset->add_field(new FormFieldCheckbox('reproductible', $this->lang['bugs.labels.fields.reproductible'], $this->bug->is_reproductible() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED, 
			array('events' => array('click' => '
			if (HTMLForms.getField("reproductible").getValue()) {
				HTMLForms.getField("reproduction_method").enable();
			} else {
				HTMLForms.getField("reproduction_method").disable();
			}')
		)));
		
		$fieldset->add_field(new FormFieldRichTextEditor('reproduction_method', $this->lang['bugs.labels.fields.reproduction_method'], FormatingHelper::unparse($this->bug->get_reproduction_method()), array(
			'rows' => 15, 'hidden' => !$this->bug->is_reproductible() ? true : false)
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		
		$error_lang = LangLoader::get('errors');
		
		$error = $request->get_value('error', '');
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$body_view = BugtrackerViews::build_body_view($view, 'edit', $this->bug->get_id());
		
		//error messages
		switch ($error)
		{
			case 'no_fixed_version':
				$errstr = $this->lang['bugs.error.e_no_fixed_version'];
				$errtyp = E_USER_ERROR;
				break;
			case 'unexist_user':
				$errstr = $error_lang['e_unexist_user'];
				$errtyp = E_USER_ERROR;
				break;
			case 'incomplete':
				$errstr = $error_lang['e_incomplete'];
				$errtyp = E_USER_NOTICE;
				break;
			default:
				$errstr = $errtyp = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, $errtyp));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.edit_bug'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::edit(!empty($back_page) ? $this->bug->get_id() . '/' . $back_page . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '') : $this->bug->get_id()));
		$response->set_page_title($this->lang['bugs.titles.edit_bug'] . ' #' . $this->bug->get_id());
		
		return $response->display($body_view);
	}
	
	private function save()
	{
		$request = AppContext::get_request();
		
		$main_lang = LangLoader::get('main');
		
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$bug = $this->bug;
		$old_values = new Bug();
		$old_values->set_properties($bug->get_properties());
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$status_list = $this->config->get_status_list();
		
		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$severities = $this->config->get_severities();
		$priorities = $this->config->get_priorities();
		$versions = $this->config->get_versions();
		$display_versions = sizeof($versions) > 1 ? true : false;
		
		$bug->set_title($this->form->get_value('title', $old_values->get_title()));
		$bug->set_contents($this->form->get_value('contents', $old_values->get_contents()));
		$bug->set_type($this->form->get_value('type') ? $this->form->get_value('type')->get_raw_value() : $old_values->get_type());
		$bug->set_category($this->form->get_value('category') ? $this->form->get_value('category')->get_raw_value() : $old_values->get_category());
		$bug->set_severity($this->form->get_value('severity') ? $this->form->get_value('severity')->get_raw_value() : $old_values->get_severity());
		$bug->set_priority($this->form->get_value('priority') ? $this->form->get_value('priority')->get_raw_value() : $old_values->get_priority());
		$bug->set_detected_in($this->form->get_value('detected_in') ? $this->form->get_value('detected_in')->get_raw_value() : $old_values->get_detected_in());
		
		$something_is_missing = ($this->config->get_types() && $this->config->is_type_mandatory() && !$bug->get_type() || $this->config->get_categories() && $this->config->is_category_mandatory() && !$bug->get_category() || $this->config->get_severities() && $this->config->is_severity_mandatory() && !$bug->get_severity() || $this->config->get_priorities() && $this->config->is_priority_mandatory() && !$bug->get_priority() || $this->config->get_versions() && $this->config->is_detected_in_version_mandatory() && !$bug->get_detected_in());
		
		if ($bug->get_title() && $bug->get_contents() && !$something_is_missing)
		{
			$bug->set_reproductible($this->form->get_value('reproductible') ? true : 0);
			
			if ($bug->is_reproductible())
			{
				$bug->set_reproduction_method($this->form->get_value('reproduction_method', $old_values->get_reproduction_method()));
			}
			
			$pm_comment = '';
			$modification = false;
			
			if (BugtrackerAuthorizationsService::check_authorizations()->moderation() || ($this->current_user->get_id() == $old_values->get_assigned_to_id()))
			{
				$versions = $this->config->get_versions();
				$display_versions = sizeof($versions) > 1 ? true : false;
				
				$assigned_to = $this->form->get_value('assigned_to');
				
				//Error if the selected user does not exist
				if (!empty($assigned_to) && !UserService::user_exists("WHERE user_aprob = 1 AND login=:login", array('login' => $assigned_to)))
					AppContext::get_response()->redirect(BugtrackerUrlBuilder::edit_error(!empty($back_page) ? 'unexist_user/' . $this->bug->get_id() . '/' . $back_page . '/' . $page : 'unexist_user/' . $this->bug->get_id()));
				
				$user_assigned = !empty($assigned_to) ? UserService::get_user("WHERE user_aprob = 1 AND login=:login", array('login' => $assigned_to)) : 0;
				
				$bug->set_assigned_to_id($user_assigned ? $user_assigned->get_id() : 0);
				
				if (sizeof($versions))
				{
					if (!$this->form->field_is_disabled('fixed_in'))
					{
						$bug->set_fixed_in($this->form->get_value('fixed_in')->get_raw_value() ? $this->form->get_value('fixed_in')->get_raw_value() : 0);
					}
				}
				
				$status = $bug->get_status();
				if (!$old_values->is_fixed() && $this->form->get_value('fixed'))
					$status = Bug::FIXED;
				if ($old_values->is_fixed() && !$this->form->get_value('fixed'))
					$status = Bug::REOPEN;
				if (!$bug->get_assigned_to_id() && $old_values->get_assigned_to_id())
					$status = Bug::NEW_BUG;
				if ($bug->get_assigned_to_id() && ($status != Bug::FIXED) && ($status != Bug::REJECTED))
					$status = Bug::ASSIGNED;
				if ($bug->get_fixed_in() && ($status != Bug::FIXED) && !$bug->get_assigned_to_id())
					$status = Bug::IN_PROGRESS;
					
				$bug->set_status($status);
				
				//Error if the status is fixed and no version is selected if the roadmap is enabled
				if ($display_versions && $this->config->is_roadmap_enabled() && $bug->is_fixed() && !$bug->get_fixed_in())
					AppContext::get_response()->redirect(BugtrackerUrlBuilder::edit_error(!empty($back_page) ? 'no_fixed_version/' . $this->bug->get_id() . '/' . $back_page . '/' . $page : 'no_fixed_version/' . $this->bug->get_id()));
				
				$fields = array('title', 'contents', 'type', 'category', 'severity', 'priority', 'detected_in', 'reproductible', 'reproduction_method', 'status', 'fixed_in', 'assigned_to_id');
			}
			else
			{
				$fields = array('title', 'contents', 'type', 'category', 'severity', 'priority', 'detected_in', 'reproductible', 'reproduction_method');
			}
			
			$n_values = $bug->get_properties();
			$o_values = $old_values->get_properties();
			foreach ($fields as $field)
			{
				if ($o_values[$field] != $n_values[$field])
				{
					$modification = true;
					$comment = '';
					switch ($field)
					{
						case 'title': 
							$new_value = stripslashes($n_values[$field]);
							$o_values[$field] = addslashes($o_values[$field]);
							$comment = '';
							break;
							
						case 'contents' :
								$o_values[$field] = '';
								$n_values[$field] = '';
								$comment = $this->lang['bugs.notice.contents_update'];
								break;
						
						case 'reproduction_method' :
								$o_values[$field] = '';
								$n_values[$field] = '';
								$comment = $this->lang['bugs.notice.reproduction_method_update'];
								break;
						
						case 'type': 
							$new_value = !empty($n_values[$field]) ? stripslashes($types[$n_values[$field]]) : $this->lang['bugs.notice.none'];
							break;
						
						case 'category': 
							$new_value = !empty($n_values[$field]) ? stripslashes($categories[$n_values[$field]]) : $this->lang['bugs.notice.none_e'];
							break;
						
						case 'priority': 
							$new_value = !empty($n_values[$field]) ? stripslashes($priorities[$n_values[$field]]) : $this->lang['bugs.notice.none_e'];
							break;
						
						case 'severity': 
							$new_value = !empty($n_values[$field]) ? stripslashes($severities[$n_values[$field]]['name']) : $this->lang['bugs.notice.none'];
							break;
						
						case 'detected_in': 
						case 'fixed_in': 
							$new_value = !empty($n_values[$field]) ? stripslashes($versions[$n_values[$field]]['name']) : $this->lang['bugs.notice.none_e'];
							break;
						
						case 'status': 
							$new_value = $this->lang['bugs.status.' . $n_values[$field]];
							break;
						
						case 'reproductible': 
							$new_value = $new_values[$field] ? $main_lang['yes'] : $main_lang['no'];
							break;
						
						case 'assigned_to_id': 
							$new_value = !empty($user_assigned) ? '<a href="' . UserUrlBuilder::profile($user_assigned->get_id())->absolute() . '" class="' . UserService::get_level_class($user_assigned->get_level()) . '">' . $user_assigned->get_pseudo() . '</a>' : $this->lang['bugs.notice.no_one'];
							break;
						
						default:
							$new_value = $n_values[$field];
							$comment = '';
					}
					$pm_comment .= ($field != 'contents' && $field != 'reproduction_method' && $field != 'assigned_to_id') ? $this->lang['bugs.labels.fields.' . $field] . ' : ' . stripslashes($new_value) . '
' : '';
					//Bug history update
					BugtrackerService::add_history(array(
						'bug_id'		=> $bug->get_id(),
						'updater_id'	=> $this->current_user->get_id(),
						'update_date'	=> $now->get_timestamp(),
						'updated_field'	=> $field,
						'old_value'		=> $o_values[$field],
						'new_value'		=> $n_values[$field],
						'change_comment'=> $comment
					));
				}
			}
			
			if ($modification)
			{
				if (BugtrackerAuthorizationsService::check_authorizations()->moderation() || ($this->current_user->get_id() == $bug->get_assigned_to_id()))
				{
					//Bug update
					$bug->set_progress($bug->is_fixed() ? $status_list[Bug::FIXED] : ($bug->is_assigned() || $bug->is_in_progress() ? $status_list[Bug::IN_PROGRESS] : ($bug->is_new() ? $status_list[Bug::NEW_BUG] : $bug->get_progress())));
					$bug->set_fix_date($bug->is_fixed() ? $now->get_timestamp() : 0);
					
					//Send PM to the assigned user if the option is enabled
					if ($this->config->are_pm_enabled() && $this->config->are_pm_assign_enabled() && $bug->get_assigned_to_id() && ($old_values->get_assigned_to_id() != $bug->get_assigned_to_id()) && ($this->current_user->get_id() != $bug->get_assigned_to_id()))
						BugtrackerPMService::send_PM('assigned', $bug->get_assigned_to_id(), $bug->get_id(), $pm_comment);
				}
				//Bug update
				BugtrackerService::update($bug);
				
				//Send PM to updaters if the option is enabled
				if ($this->config->are_pm_enabled() && $this->config->are_pm_edit_enabled() && !empty($pm_comment))
					BugtrackerPMService::send_PM_to_updaters('edit', $bug->get_id(), $pm_comment);
				
				if ($this->config->are_admin_alerts_enabled() && in_array($bug->get_severity(), $this->config->get_admin_alerts_levels()))
				{
					$alerts = AdministratorAlertService::find_by_criteria($bug->get_id(), 'bugtracker');
					if (!empty($alerts))
					{
						$alert = $alerts[0];
						if ($this->config->is_admin_alerts_fix_action_fix())
						{
							$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED);
							AdministratorAlertService::save_alert($alert);
						}
						else
							AdministratorAlertService::delete_alert($alert);
					}
				}
				
				BugtrackerStatsCache::invalidate();
				
				switch ($back_page)
				{
					case 'detail' :
						$redirect = BugtrackerUrlBuilder::detail_success('edit/' . $bug->get_id())->absolute();
						break;
					case 'solved' :
						$redirect = BugtrackerUrlBuilder::solved_success('edit/' . $bug->get_id() . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
						break;
					case 'unsolved' :
						$redirect = BugtrackerUrlBuilder::unsolved_success('edit/' . $bug->get_id() . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
						break;
					default :
						$redirect = BugtrackerUrlBuilder::edit_success('edit/' . $bug->get_id())->absolute();
						break;
				}
			}
			else
			{
				switch ($back_page)
				{
					case 'detail' :
						$redirect = BugtrackerUrlBuilder::detail($bug->get_id())->absolute();
						break;
					case 'solved' :
						$redirect = BugtrackerUrlBuilder::solved($page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
						break;
					case 'unsolved' :
						$redirect = BugtrackerUrlBuilder::unsolved($page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
						break;
					default :
						$redirect = BugtrackerUrlBuilder::edit($bug->get_id())->absolute();
						break;
				}
			}
			
			AppContext::get_response()->redirect($redirect);
		}
		else
			AppContext::get_response()->redirect(BugtrackerUrlBuilder::edit_error(!empty($back_page) ? 'incomplete/' . $back_page . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '') : 'incomplete'));
	}
}
?>
