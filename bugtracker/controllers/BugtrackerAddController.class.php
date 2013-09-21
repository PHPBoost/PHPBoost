<?php
/*##################################################
 *                      BugtrackerAddController.class.php
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

class BugtrackerAddController extends ModuleController
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
	private $bug;
	private $submit_button;
	private $config;
	
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
		$this->config = BugtrackerConfig::load();
		
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->view = new StringTemplate('# INCLUDE FORM #');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->write() && !BugtrackerAuthorizationsService::check_authorizations()->moderation())
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
		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$severities = $this->config->get_severities();
		$priorities = $this->config->get_priorities();
		$versions_detected_in = $this->config->get_versions_detected();
		
		$versions_detected_in = array_reverse($versions_detected_in, true);
		
		$display_types = sizeof($types) > 1 ? true : false;
		$display_categories = sizeof($categories) > 1 ? true : false;
		$display_priorities = sizeof($priorities) > 1 ? true : false;
		$display_severities = sizeof($severities) > 1 ? true : false;
		$display_versions_detected_in = sizeof($versions_detected_in) > 1 ? true : false;
		
		$default_type = $this->config->get_default_type();
		$default_category = $this->config->get_default_category();
		$default_severity = $this->config->get_default_severity();
		$default_priority = $this->config->get_default_priority();
		$default_version = $this->config->get_default_version();

		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('bug_infos', $this->lang['bugs.titles.bugs_infos']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['bugs.labels.fields.title'], '', array(
			'class' => 'text', 'maxlength' => 200, 'size' => 50, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['bugs.labels.fields.contents'], FormatingHelper::unparse($this->config->get_contents_value()), array(
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
			
			$fieldset->add_field(new FormFieldSimpleSelectChoice('type', $this->lang['bugs.labels.fields.type'], $default_type, $array_types, array(
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
			
			$fieldset->add_field(new FormFieldSimpleSelectChoice('category', $this->lang['bugs.labels.fields.category'], $default_category, $array_categories, array(
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
				
				$fieldset->add_field(new FormFieldSimpleSelectChoice('severity', $this->lang['bugs.labels.fields.severity'], $default_severity, $array_severities, array(
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
				
				$fieldset->add_field(new FormFieldSimpleSelectChoice('priority', $this->lang['bugs.labels.fields.priority'], $default_priority, $array_priorities, array(
					'required' => $this->config->is_priority_mandatory())
				));
			}
		}
		
		//Versions
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
			
			$fieldset->add_field(new FormFieldSimpleSelectChoice('detected_in', $this->lang['bugs.labels.fields.detected_in'], $default_version, $array_versions, array(
				'required' => $this->config->is_detected_in_version_mandatory())
			));
		}
		
		$fieldset->add_field(new FormFieldCheckbox('reproductible', $this->lang['bugs.labels.fields.reproductible'], FormFieldCheckbox::CHECKED, 
			array('events' => array('click' => '
			if (HTMLForms.getField("reproductible").getValue()) {
				HTMLForms.getField("reproduction_method").enable();
			} else {
				HTMLForms.getField("reproduction_method").disable();
			}')
		)));
		
		$fieldset->add_field(new FormFieldRichTextEditor('reproduction_method', $this->lang['bugs.labels.fields.reproduction_method'], '', array(
			'rows' => 15)
		));
		
		$fieldset->add_field(new FormFieldCaptcha('captcha'));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		
		$error = $request->get_value('error', '');
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$body_view = BugtrackerViews::build_body_view($view, 'add');
		
		//Error messages
		switch ($error)
		{
			case 'incomplete':
				$errstr = LangLoader::get_message('e_incomplete', 'errors');
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_NOTICE));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.add_bug'], BugtrackerUrlBuilder::add(!empty($back_page) ? $back_page . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '') : ''));
		$response->set_page_title($this->lang['bugs.titles.add_bug']);
		
		return $response->display($body_view);
	}
	
	private function get_bug()
	{
		$this->bug = new Bug();
		$this->bug->init_default_properties();
		
		return $this->bug;
	}
	
	private function save()
	{
		$request = AppContext::get_request();
		
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$bug = $this->get_bug();
		
		$bug->set_title($this->form->get_value('title'));
		$bug->set_contents($this->form->get_value('contents'));
		$bug->set_type($this->form->get_value('type') ? $this->form->get_value('type')->get_raw_value() : $this->config->get_default_type());
		$bug->set_category($this->form->get_value('category') ? $this->form->get_value('category')->get_raw_value() : $this->config->get_default_category());
		$bug->set_severity($this->form->get_value('severity') ? $this->form->get_value('severity')->get_raw_value() : $this->config->get_default_severity());
		$bug->set_priority($this->form->get_value('priority') ? $this->form->get_value('priority')->get_raw_value() : $this->config->get_default_priority());
		$bug->set_detected_in($this->form->get_value('detected_in') ? $this->form->get_value('detected_in')->get_raw_value() : $this->config->get_default_version());
		$bug->set_reproductible($this->form->get_value('reproductible') ? true : 0);
		
		if ($bug->is_reproductible())
			$bug->set_reproduction_method($this->form->get_value('reproduction_method'));
		
		$something_is_missing = ($this->config->get_types() && $this->config->is_type_mandatory() && !$bug->get_type() || $this->config->get_categories() && $this->config->is_category_mandatory() && !$bug->get_category() || $this->config->get_severities() && $this->config->is_severity_mandatory() && !$bug->get_severity() || $this->config->get_priorities() && $this->config->is_priority_mandatory() && !$bug->get_priority() || $this->config->get_versions() && $this->config->is_detected_in_version_mandatory() && !$bug->get_detected_in());
		
		if ($bug->get_title() && $bug->get_contents() && !$something_is_missing)
		{
			//Bug creation
			$id = BugtrackerService::add($bug);
			
			if ($this->config->are_admin_alerts_enabled() && in_array($bug->get_severity(), $this->config->get_admin_alerts_levels()))
			{
				$alert = new AdministratorAlert();
				$alert->set_entitled('[' . $this->lang['bugs.module_title'] . '] ' . $bug->get_title());
				$alert->set_fixing_url(BugtrackerUrlBuilder::detail($id)->relative());
				
				switch ($bug->get_priority())
				{
					case 1 :
						switch ($bug->get_severity())
						{
							case 1 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY;
								break;
							
							case 2 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY;
								break;
							
							default :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
						}
						break;

					case 2 :
						switch ($bug->get_severity())
						{
							case 1 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY;
								break;
							
							default :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
						}
						break;
					
					case 3 :
						switch ($bug->get_severity())
						{
							case 1 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY;
								break;
							
							case 2 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
							
							case 3 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY;
								break;
							
							default :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
						}
						break;
					
					case 4 :
						switch ($bug->get_severity())
						{
							case 2 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
							
							case 3 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY;
								break;
							
							default :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY;
								break;
						}
						break;
					
					case 5 :
						switch ($bug->get_severity())
						{
							case 2 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY;
								break;
							
							case 3 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY;
								break;
							
							default :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
						}
						break;
					
					default :
						switch ($bug->get_severity())
						{
							case 1 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY;
								break;
							
							case 2 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
							
							case 3 :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY;
								break;
							
							default :
								$alert_priority = AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY;
								break;
						}
						break;
				}
				
				$alert->set_priority($alert_priority);
				$alert->set_id_in_module($id);
				$alert->set_type('bugtracker');
				AdministratorAlertService::save_alert($alert);
			}
			
			BugtrackerStatsCache::invalidate();
			
			switch ($back_page)
			{
				case 'roadmap' :
					$redirect = BugtrackerUrlBuilder::roadmap_success('add/'. $id . '/' . $page)->absolute();
					break;
				case 'stats' :
					$redirect = BugtrackerUrlBuilder::stats_success('add/'. $id)->absolute();
					break;
				case 'solved' :
					$redirect = BugtrackerUrlBuilder::solved_success('add/'. $id . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
					break;
				default :
					$redirect = BugtrackerUrlBuilder::unsolved_success('add/'. $id . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
					break;
			}
			
			AppContext::get_response()->redirect($redirect);
		}
		else
			AppContext::get_response()->redirect(BugtrackerUrlBuilder::add_error(!empty($back_page) ? 'incomplete/' . $back_page . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '') : 'incomplete'));
	}
}
?>
