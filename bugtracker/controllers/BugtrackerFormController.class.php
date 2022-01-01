<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 4.0 - 2014 01 29
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerFormController extends DefaultModuleController
{
	private $bug;
	private $current_user;
	private $is_new_bug;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->generate_response($this->view);
	}

	private function init()
	{
		$this->current_user = AppContext::get_current_user();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$bug = $this->get_bug();

		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$severities = $this->config->get_severities();
		$priorities = $this->config->get_priorities();
		$versions_detected_in = array_reverse($this->config->get_versions_detected(), true);

		$display_types = count($types) > 1;
		$display_categories = count($categories) > 1;
		$display_severities = count($severities) > 1;
		$display_priorities = count($priorities) > 1;
		$display_versions_detected_in = count($versions_detected_in) > 1;

		$default_type = $this->config->get_default_type();
		$default_category = $this->config->get_default_category();
		$default_severity = $this->config->get_default_severity();
		$default_priority = $this->config->get_default_priority();
		$default_version = $this->config->get_default_version();

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('bug_infos', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['form.title'], $bug->get_title(), array('required' => true)));

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->lang['form.description'], $bug->get_content(), array(
			'description' => $this->lang['explain.content'], 'rows' => 15, 'required' => true)
		));

		//Types
		if ($display_types)
		{
			$array_types = array();
			if (empty($default_type))
			{
				$array_types[] = new FormFieldSelectChoiceOption(' ', '');
			}
			foreach ($types as $key => $type)
			{
				$array_types[] = new FormFieldSelectChoiceOption(stripslashes($type), $key);
			}

			$fieldset->add_field(new FormFieldSimpleSelectChoice('type', $this->lang['labels.fields.type'], $bug->get_type(), $array_types, array(
				'required' => $this->config->is_type_mandatory())
			));
		}

		//Categories
		if ($display_categories)
		{
			$array_categories = array();
			if (empty($default_category))
			{
				$array_categories[] = new FormFieldSelectChoiceOption(' ', '');
			}
			foreach ($categories as $key => $category)
			{
				$array_categories[] = new FormFieldSelectChoiceOption(stripslashes($category), $key);
			}

			$fieldset->add_field(new FormFieldSimpleSelectChoice('category', $this->lang['labels.fields.category'], $bug->get_category(), $array_categories, array(
				'required' => $this->config->is_category_mandatory())
			));
		}

		if (BugtrackerAuthorizationsService::check_authorizations()->advanced_write())
		{
			//Severities
			if ($display_severities)
			{
				$array_severities = array();
				if (empty($default_severity))
				{
					$array_severities[] = new FormFieldSelectChoiceOption(' ', '');
				}
				foreach ($severities as $key => $severity)
				{
					$array_severities[] = new FormFieldSelectChoiceOption(stripslashes($severity['name']), $key);
				}

				$fieldset->add_field(new FormFieldSimpleSelectChoice('severity', $this->lang['labels.fields.severity'], $bug->get_severity(), $array_severities, array(
					'required' => $this->config->is_severity_mandatory())
				));
			}

			//Priorities
			if ($display_priorities)
			{
				$array_priorities = array();
				if (empty($default_priority))
				{
					$array_priorities[] = new FormFieldSelectChoiceOption(' ', '');
				}
				foreach ($priorities as $key => $priority)
				{
					$array_priorities[] = new FormFieldSelectChoiceOption(stripslashes($priority), $key);
				}

				$fieldset->add_field(new FormFieldSimpleSelectChoice('priority', $this->lang['labels.fields.priority'], $bug->get_priority(), $array_priorities, array(
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
				$array_versions[] = new FormFieldSelectChoiceOption(' ', '');
			}
			foreach ($versions_detected_in as $key => $version)
			{
				$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key);
			}

			$fieldset->add_field(new FormFieldSimpleSelectChoice('detected_in', $this->lang['labels.fields.detected_in'], $bug->get_detected_in(), $array_versions, array(
				'required' => $this->config->is_detected_in_version_mandatory())
			));
		}

		$fieldset->add_field(new FormFieldCheckbox('reproductible', $this->lang['labels.fields.reproductible'], $bug->is_reproductible() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED,
			array('events' => array('click' => '
			if (HTMLForms.getField("reproductible").getValue()) {
				HTMLForms.getField("reproduction_method").enable();
			} else {
				HTMLForms.getField("reproduction_method").disable();
			}')
		)));

		$fieldset->add_field(new FormFieldRichTextEditor('reproduction_method', $this->lang['labels.fields.reproduction_method'], FormatingHelper::unparse($bug->get_reproduction_method()), array(
			'rows' => 15, 'hidden' => !$bug->is_reproductible())
		));

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_bug()
	{
		if ($this->bug === null)
		{
			$request = AppContext::get_request();
			$id = $request->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_bug = true;
				$this->bug = new BugtrackerItem();
				$this->bug->init_default_properties();
			}
		}
		return $this->bug;
	}

	private function check_authorizations()
	{
		$bug = $this->get_bug();

		if ($bug->get_id() === null)
		{
			if (!$bug->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!($bug->is_authorized_to_edit() || ($bug->get_assigned_to_id() && $this->current_user->get_id() == $bug->get_assigned_to_id())))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}

	private function save()
	{
		$bug = $this->get_bug();

		if ($bug->get_id() === null)
		{
			$title = $this->form->get_value('title');

			$bug->set_title($title);
			$bug->set_rewrited_title(Url::encode_rewrite($title));
			$bug->set_content($this->form->get_value('content'));
			$bug->set_type($this->form->get_value('type') ? $this->form->get_value('type')->get_raw_value() : $this->config->get_default_type());
			$bug->set_category($this->form->get_value('category') ? $this->form->get_value('category')->get_raw_value() : $this->config->get_default_category());
			$bug->set_severity($this->form->get_value('severity') ? $this->form->get_value('severity')->get_raw_value() : $this->config->get_default_severity());
			$bug->set_priority($this->form->get_value('priority') ? $this->form->get_value('priority')->get_raw_value() : $this->config->get_default_priority());
			$bug->set_detected_in($this->form->get_value('detected_in') ? $this->form->get_value('detected_in')->get_raw_value() : $this->config->get_default_version());
			$bug->set_reproductible($this->form->get_value('reproductible') ? true : 0);

			if ($bug->is_reproductible())
				$bug->set_reproduction_method($this->form->get_value('reproduction_method'));

			//Bug creation
			$bug->set_id(BugtrackerService::add($bug));

			HooksService::execute_hook_action('add', self::$module_id, array_merge($bug->get_properties(), array('item_url' => $bug->get_item_url())));

			if ($this->config->are_admin_alerts_enabled() && in_array($bug->get_severity(), $this->config->get_admin_alerts_levels()))
			{
				$alert = new AdministratorAlert();
				$alert->set_entitled('[' . $this->lang['bugtracker.module.title'] . '] ' . $bug->get_title());
				$alert->set_fixing_url(BugtrackerUrlBuilder::detail($bug->get_id() . '-' . $bug->get_rewrited_title())->relative());

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
				$alert->set_id_in_module($bug->get_id());
				$alert->set_type('bugtracker');
				AdministratorAlertService::save_alert($alert);
			}
		}
		else
		{
			$old_values = clone $bug;

			$now = new Date();

			$types = $this->config->get_types();
			$categories = $this->config->get_categories();
			$severities = $this->config->get_severities();
			$priorities = $this->config->get_priorities();
			$versions = $this->config->get_versions();
			$display_versions = count($versions) > 1;
			$status_list = $this->config->get_status_list();

			$title = $this->form->get_value('title', $old_values->get_title());

			$bug->set_title($title);
			$bug->set_rewrited_title(Url::encode_rewrite($title));
			$bug->set_content($this->form->get_value('content', $old_values->get_content()));
			$bug->set_type($this->form->get_value('type') ? $this->form->get_value('type')->get_raw_value() : $old_values->get_type());
			$bug->set_category($this->form->get_value('category') ? $this->form->get_value('category')->get_raw_value() : $old_values->get_category());
			$bug->set_severity($this->form->get_value('severity') ? $this->form->get_value('severity')->get_raw_value() : $old_values->get_severity());
			$bug->set_priority($this->form->get_value('priority') ? $this->form->get_value('priority')->get_raw_value() : $old_values->get_priority());
			$bug->set_detected_in($this->form->get_value('detected_in') ? $this->form->get_value('detected_in')->get_raw_value() : $old_values->get_detected_in());

			$bug->set_reproductible($this->form->get_value('reproductible') ? true : 0);

			if ($bug->is_reproductible())
			{
				$bug->set_reproduction_method($this->form->get_value('reproduction_method', $old_values->get_reproduction_method()));
			}

			$pm_comment = '';
			$modification = false;

			$fields = array('title', 'content', 'type', 'category', 'severity', 'priority', 'detected_in', 'reproductible', 'reproduction_method');

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

						case 'content' :
								$o_values[$field] = '';
								$n_values[$field] = '';
								$comment = $this->lang['notice.content_update'];
								break;

						case 'reproduction_method' :
								$o_values[$field] = '';
								$n_values[$field] = '';
								$comment = $this->lang['notice.reproduction_method_update'];
								break;

						case 'type':
							$new_value = !empty($n_values[$field]) ? stripslashes($types[$n_values[$field]]) : $this->lang['notice.none'];
							break;

						case 'category':
							$new_value = !empty($n_values[$field]) ? stripslashes($categories[$n_values[$field]]) : $this->lang['notice.none_e'];
							break;

						case 'priority':
							$new_value = !empty($n_values[$field]) ? stripslashes($priorities[$n_values[$field]]) : $this->lang['notice.none_e'];
							break;

						case 'severity':
							$new_value = !empty($n_values[$field]) ? stripslashes($severities[$n_values[$field]]['name']) : $this->lang['notice.none'];
							break;

						case 'detected_in':
							$new_value = !empty($n_values[$field]) ? stripslashes($versions[$n_values[$field]]['name']) : $this->lang['notice.none_e'];
							break;

						case 'status':
							$new_value = $this->lang['status.' . $n_values[$field]];
							break;

						case 'reproductible':
							$new_value = $n_values[$field] ? $this->lang['common.yes'] : $this->lang['common.no'];
							break;

						default:
							$new_value = $n_values[$field];
							$comment = '';
					}
					$pm_comment .= ($field != 'content' && $field != 'reproduction_method') ? $this->lang['labels.fields.' . $field] . ' : ' . stripslashes($new_value) . '
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
				//Bug update
				BugtrackerService::update($bug);

				HooksService::execute_hook_action('edit', self::$module_id, array_merge($bug->get_properties(), array('item_url' => $bug->get_item_url())));

				//Send PM to updaters if the option is enabled
				if ($this->config->are_pm_enabled() && $this->config->are_pm_edit_enabled() && !empty($pm_comment))
					BugtrackerPMService::send_PM_to_updaters('edit', $bug->get_id(), $pm_comment);
			}
		}

		Feed::clear_cache('bugtracker');
		BugtrackerStatsCache::invalidate();

		if ($this->is_new_bug)
		{
			DispatchManager::redirect(new BugtrackerBugSubmitSuccessController($bug->get_id()));
		}
		else
		{
			AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : BugtrackerUrlBuilder::unsolved()), StringVars::replace_vars($this->lang['success.edit'], array('id' => $bug->get_id())));
		}
	}

	private function generate_response(View $tpl)
	{
		$bug = $this->get_bug();

		if ($bug->get_id() === null)
		{
			$body_view = BugtrackerViews::build_body_view($tpl, 'add');

			$response = new SiteDisplayResponse($body_view);
			$graphical_environment = $response->get_graphical_environment();
			$graphical_environment->set_page_title($this->lang['titles.add'], $this->lang['bugtracker.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::add());

			$breadcrumb = $graphical_environment->get_breadcrumb();
			$breadcrumb->add($this->lang['bugtracker.module.title'], BugtrackerUrlBuilder::home());
			$breadcrumb->add($this->lang['titles.add'], BugtrackerUrlBuilder::add());
		}
		else
		{
			$body_view = BugtrackerViews::build_body_view($tpl, 'edit', $bug->get_id(), $this->bug->get_type());

			$location_id = 'bugtracker-edit-'. $bug->get_id();

			$response = new SiteDisplayResponse($body_view, $location_id);
			$graphical_environment = $response->get_graphical_environment();

			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['titles.edit'] . ' #' . $bug->get_id(), $this->lang['bugtracker.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::edit($bug->get_id()));

			$breadcrumb = $graphical_environment->get_breadcrumb();
			$breadcrumb->add($this->lang['bugtracker.module.title'], BugtrackerUrlBuilder::home());
			$breadcrumb->add($this->lang['titles.edit'] . ' #' . $bug->get_id(), BugtrackerUrlBuilder::edit($bug->get_id()));
		}

		return $response;
	}
}
?>
