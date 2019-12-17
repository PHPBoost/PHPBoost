<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 25
 * @since       PHPBoost 4.1 - 2014 02 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class BugtrackerChangeBugStatusController extends ModuleController
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

			$this->form->get_field_by_id('assigned_to')->set_hidden(!$this->bug->is_assigned());
			if ($this->config->get_versions_fix())
				$this->form->get_field_by_id('fixed_in')->set_hidden(!$this->bug->is_fixed() && !$this->bug->is_in_progress());
			$this->form->get_field_by_id('comments_message')->set_value('');

			$this->view->put('MSG', MessageHelper::display($this->lang['success.' . $this->bug->get_status()], MessageHelper::SUCCESS, 5));
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

		$this->view = new FileTemplate('bugtracker/BugtrackerChangeBugStatusController.tpl');
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
		$versions = array_reverse($this->config->get_versions_fix(), true);
		$versions_number = count($versions);

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('fix_bug');
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('status', $this->lang['labels.fields.status'], $this->bug->get_status(), $this->generate_status_list_select(),
			array('events' => array('change' => ($versions_number ? '
			if (HTMLForms.getField("status").getValue() == "' . Bug::FIXED . '" || HTMLForms.getField("status").getValue() == "' . Bug::IN_PROGRESS . '") {
				HTMLForms.getField("fixed_in").enable();
				HTMLForms.getField("assigned_to").disable();
			} else ' : '') . 'if (HTMLForms.getField("status").getValue() == "' . Bug::ASSIGNED . '") {
				HTMLForms.getField("assigned_to").enable();
				' . ($versions_number ? 'HTMLForms.getField("fixed_in").disable();
				' : '') . '} else {
				' . ($versions_number ? 'HTMLForms.getField("fixed_in").disable();
				' : '') . 'HTMLForms.getField("assigned_to").disable();
			}')),
			!$this->bug->is_fixed() && !$this->bug->is_assigned() && !$this->bug->is_in_progress() ? array(new BugtrackerConstraintStatusChanged($this->bug->get_id(), $this->bug->get_status())) : array()
		));

		$user_assigned = UserService::user_exists('WHERE user_id=:user_id', array('user_id' => $this->bug->get_assigned_to_id())) ? UserService::get_user($this->bug->get_assigned_to_id()) : '';

		$fieldset->add_field(new FormFieldAjaxUserAutoComplete('assigned_to', $this->lang['labels.fields.assigned_to_id'], !empty($user_assigned) ? $user_assigned->get_display_name() : '',
			array('required' => true, 'hidden' => !$this->bug->is_assigned()),
			array(new FormFieldConstraintUserExist())
		));

		//Fix versions
		if ($versions_number)
		{
			$array_versions = array();
			$array_versions[] = new FormFieldSelectChoiceOption(' ', 0);
			foreach ($versions as $key => $version)
			{
				if ($key > $this->bug->get_detected_in())
					$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key);
			}

			$fieldset->add_field(new FormFieldSimpleSelectChoice('fixed_in', $this->lang['labels.fields.fixed_in'], $this->bug->get_fixed_in(), $array_versions,
				array('description' => $this->lang['explain.change_status_select_fix_version'], 'hidden' => !$this->bug->is_fixed() && !$this->bug->is_in_progress()
			)));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('comments_message', LangLoader::get_message('comment', 'comments-common'), '', array(
			'description' => $this->lang['explain.change_status_comments_message']
		)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonLink(LangLoader::get_message('back', 'main'), 'javascript:history.back(1);'));

		$this->form = $form;
	}

	private function generate_status_list_select()
	{
		$options = array();
		$options[] = new FormFieldSelectChoiceOption($this->lang['status.new'], Bug::NEW_BUG);
		$options[] = new FormFieldSelectChoiceOption($this->lang['status.pending'], Bug::PENDING);
		$options[] = new FormFieldSelectChoiceOption($this->lang['status.assigned'], Bug::ASSIGNED);
		$options[] = new FormFieldSelectChoiceOption($this->lang['status.in_progress'], Bug::IN_PROGRESS);
		$options[] = new FormFieldSelectChoiceOption($this->lang['status.rejected'], Bug::REJECTED);
		$options[] = new FormFieldSelectChoiceOption($this->lang['status.reopen'], Bug::REOPEN);
		$options[] = new FormFieldSelectChoiceOption($this->lang['status.fixed'], Bug::FIXED);

		return $options;
	}

	private function save()
	{
		$now = new Date();
		$pm_recipients_list = array();
		$send_pm = true;

		$versions = array_reverse($this->config->get_versions_fix(), true);
		$status = $this->form->get_value('status')->get_raw_value();

		if (count($versions))
		{
			if (!$this->form->field_is_disabled('fixed_in'))
			{
				$fixed_in = $this->form->get_value('fixed_in')->get_raw_value() ? $this->form->get_value('fixed_in')->get_raw_value() : 0;

				if ($fixed_in != $this->bug->get_fixed_in())
				{
					//Bug history update
					BugtrackerService::add_history(array(
						'bug_id'		=> $this->bug->get_id(),
						'updater_id'	=> $this->current_user->get_id(),
						'update_date'	=> $now->get_timestamp(),
						'updated_field'	=> 'fixed_in',
						'old_value'		=> $this->bug->get_fixed_in(),
						'new_value'		=> $fixed_in
					));

					$this->bug->set_fixed_in($fixed_in);
				}
			}
			else if (in_array($status, array(Bug::NEW_BUG, Bug::REJECTED)))
				$this->bug->set_fixed_in(0);
		}

		if (!$this->form->field_is_disabled('assigned_to'))
		{
			$assigned_to = $this->form->get_value('assigned_to');
			$assigned_to_id = UserService::user_exists("WHERE display_name = :display_name", array('display_name' => $assigned_to));

			if ($this->bug->get_assigned_to_id())
			{
				$old_user_assigned_id = UserService::user_exists("WHERE user_id = :user_id", array('user_id' => $this->bug->get_assigned_to_id()));
				$old_user_assigned = !empty($old_user_assigned_id) ? UserService::get_user($old_user_assigned_id) : 0;
			}
			else
				$old_user_assigned = 0;

			$new_user_assigned = !empty($assigned_to) && !empty($assigned_to_id) ? UserService::get_user($assigned_to_id) : 0;
			$new_assigned_to_id = !empty($new_user_assigned) ? $new_user_assigned->get_id() : 0;

			if ($new_assigned_to_id != $this->bug->get_assigned_to_id())
			{
				//Bug history update
				BugtrackerService::add_history(array(
					'bug_id'		=> $this->bug->get_id(),
					'updater_id'	=> $this->current_user->get_id(),
					'update_date'	=> $now->get_timestamp(),
					'updated_field'	=> 'assigned_to_id',
					'old_value'		=> $old_user_assigned ? $old_user_assigned->get_display_name() : $this->lang['notice.no_one'],
					'new_value'		=> $new_user_assigned ? $new_user_assigned->get_display_name() : $this->lang['notice.no_one']
				));

				//Bug update
				$this->bug->set_assigned_to_id($new_assigned_to_id);

				//The PM will only be sent to the assigned user
				if ($new_assigned_to_id != $this->current_user->get_id())
					$pm_recipients_list[] = $new_assigned_to_id;
				else
					$send_pm = false;
			}
		}

		if ($status != $this->bug->get_status())
		{
			//Bug history update
			BugtrackerService::add_history(array(
				'bug_id'		=> $this->bug->get_id(),
				'updater_id'	=> $this->current_user->get_id(),
				'update_date'	=> $now->get_timestamp(),
				'updated_field'	=> 'status',
				'old_value'		=> $this->bug->get_status(),
				'new_value'		=> $status
			));

			//Bug update
			$this->bug->set_status($status);
			if ($this->bug->is_fixed() || $this->bug->is_rejected())
				$this->bug->set_fix_date($now);
			else
				$this->bug->set_fix_date(0);
		}

		BugtrackerService::update($this->bug);

		Feed::clear_cache('bugtracker');

		switch ($status)
		{
			case Bug::IN_PROGRESS:
				$is_pm_enabled = $this->config->are_pm_in_progress_enabled();
				break;
			case Bug::PENDING:
				$is_pm_enabled = $this->config->are_pm_pending_enabled();
				break;
			case Bug::ASSIGNED:
				$is_pm_enabled = $this->config->are_pm_assign_enabled();
				break;
			case Bug::FIXED:
				$is_pm_enabled = $this->config->are_pm_fix_enabled();
				break;
			case Bug::REOPEN:
				$is_pm_enabled = $this->config->are_pm_reopen_enabled();
				break;
			case Bug::REJECTED:
				$is_pm_enabled = $this->config->are_pm_reject_enabled();
				break;
			default :
				$is_pm_enabled = false;
				break;
		}

		//Add comment if needed
		$comment = $this->form->get_value('comments_message', '');
		if (!empty($comment))
		{
			$comments_topic = new BugtrackerCommentsTopic();
			$comments_topic->set_id_in_module($this->bug->get_id());
			$comments_topic->set_url(BugtrackerUrlBuilder::detail($this->bug->get_id() . '-' . $this->bug->get_rewrited_title()));
			CommentsManager::add_comment($comments_topic->get_module_id(), $comments_topic->get_id_in_module(), $comments_topic->get_topic_identifier(), $comments_topic->get_path(), $comment);

			//New line in the bug history
			BugtrackerService::add_history(array(
				'bug_id' => $this->bug->get_id(),
				'updater_id' => $this->current_user->get_id(),
				'update_date' => $now->get_timestamp(),
				'change_comment' => $this->lang['notice.new_comment'],
			));
		}

		//Send PM with comment to updaters if the option is enabled
		if (!$this->bug->is_new() && $this->config->are_pm_enabled() && $is_pm_enabled && $send_pm)
			BugtrackerPMService::send_PM_to_updaters($status, $this->bug->get_id(), $comment, $pm_recipients_list);

		if (in_array($status, array(Bug::NEW_BUG, Bug::REOPEN, Bug::REJECTED, Bug::FIXED)) && $this->config->are_admin_alerts_enabled() && in_array($this->bug->get_severity(), $this->config->get_admin_alerts_levels()))
		{
			$alerts = AdministratorAlertService::find_by_criteria($this->bug->get_id(), 'bugtracker');
			if (!empty($alerts))
			{
				$alert = $alerts[0];
				if ($this->bug->is_new() || $this->bug->is_reopen())
				{
					$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD);
					AdministratorAlertService::save_alert($alert);
				}
				else
				{
					if ($this->config->is_admin_alerts_fix_action_fix())
					{
						$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED);
						AdministratorAlertService::save_alert($alert);
					}
					else
						AdministratorAlertService::delete_alert($alert);
				}
			}
		}

		BugtrackerStatsCache::invalidate();
	}

	private function build_response(View $view)
	{
		$request = AppContext::get_request();

		$body_view = BugtrackerViews::build_body_view($view, 'change_status', $this->bug->get_id(), $this->bug->get_type());

		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.change_status'] . ' #' . $this->bug->get_id(), $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::change_status($this->bug->get_id()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.detail'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::detail($this->bug->get_id() . '-' . $this->bug->get_rewrited_title()));
		$breadcrumb->add($this->lang['titles.change_status'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::change_status($this->bug->get_id()));

		return $response;
	}
}
?>
