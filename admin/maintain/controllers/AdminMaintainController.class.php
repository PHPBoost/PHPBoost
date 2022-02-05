<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 05
 * @since       PHPBoost 4.1 - 2014 09 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMaintainController extends DefaultAdminController
{
	private $maintain_delay_list;
	private $maintain_type;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('maintain_during')->set_hidden($this->get_maintain_type() != 'during');
			$this->form->get_field_by_id('maintain_until')->set_hidden($this->get_maintain_type() != 'until');
			$this->form->get_field_by_id('display_duration_for_admin')->set_hidden(!$this->maintenance_config->get_display_duration());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminMaintainDisplayResponse($this->view, $this->lang['admin.maintenance']);
	}

	private function init()
	{
		$this->maintain_delay_list = array(
			60    => '1 ' . $this->lang['date.minute'],
			300   => '5 ' . $this->lang['date.minutes'],
			600   => '10 ' . $this->lang['date.minutes'],
			900   => '15 ' . $this->lang['date.minutes'],
			1800  => '30 ' . $this->lang['date.minutes'],
			3600  => '1 ' . $this->lang['date.hour'],
			7200  => '2 ' . $this->lang['date.hours'],
			10800 => '3 ' . $this->lang['date.hours'],
			14400 => '4 ' . $this->lang['date.hours'],
			18000 => '5 ' . $this->lang['date.hours'],
			21600 => '6 ' . $this->lang['date.hours'],
			25200 => '7 ' . $this->lang['date.hours'],
			28800 => '8 ' . $this->lang['date.hours'],
			57600 => '16 ' . $this->lang['date.hours']
		);

		$this->maintenance_config = MaintenanceConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('maintain', $this->lang['admin.maintenance']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('maintain_type', $this->lang['admin.maintenance.type'], $this->get_maintain_type(), $this->build_maintain_select_options(),
			array(
				'events' => array('change' => '
					if (HTMLForms.getField("maintain_type").getValue() == "during") {
						HTMLForms.getField("maintain_during").enable();
						HTMLForms.getField("maintain_until").disable();
					} else if (HTMLForms.getField("maintain_type").getValue() == "until") {
						HTMLForms.getField("maintain_during").disable();
						HTMLForms.getField("maintain_until").enable();
					} else {
						HTMLForms.getField("maintain_during").disable();
						HTMLForms.getField("maintain_until").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('maintain_during', $this->lang['admin.maintenance.type.during'], $this->get_maintain_during_select_option(), $this->build_maintain_during_select_options(),
			array('required' => true, 'hidden' => $this->get_maintain_type() != 'during')
		));

		$fieldset->add_field(new FormFieldDate('maintain_until', $this->lang['admin.maintenance.type.until'], $this->get_maintain_until_date(),
			array('required' => true, 'hidden' => $this->get_maintain_type() != 'until')
		));

		$fieldset->add_field(new FormFieldCheckbox('display_duration', $this->lang['admin.maintenance.display.duration'], $this->maintenance_config->get_display_duration(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("display_duration").getValue()) {
						HTMLForms.getField("display_duration_for_admin").enable();
					} else {
						HTMLForms.getField("display_duration_for_admin").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display_duration_for_admin', $this->lang['admin.maintenance.admin.display.duration'], $this->maintenance_config->get_display_duration_for_admin(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->maintenance_config->get_display_duration()
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('message', $this->lang['admin.maintenance.text'], $this->maintenance_config->get_message(),
			array('rows' => 14, 'cols' => 47)
		));

		$auth_settings = new AuthorizationsSettings(array(
			new VisitorDisabledActionAuthorization($this->lang['admin.maintenance.authorization'], MaintenanceConfig::ACCESS_WHEN_MAINTAIN_ENABLED_AUTHORIZATIONS),
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->maintenance_config->get_auth());
		$fieldset->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_maintain_type()
	{
		if ($this->maintain_type === null)
		{
			$maintenance_terminates_after_tomorrow = $this->maintenance_config->get_end_date()->is_posterior_to(new Date(time() + 86400, Timezone::SERVER_TIMEZONE));

			if ($this->maintenance_config->is_maintenance_enabled() && $this->maintenance_config->is_unlimited_maintenance())
				$this->maintain_type = 'unlimited';
			else if ($this->maintenance_config->is_maintenance_enabled() && ($this->maintenance_config->is_unlimited_maintenance() || ($this->maintenance_config->is_end_date_not_reached() && !$maintenance_terminates_after_tomorrow)))
				$this->maintain_type = 'during';
			else if ($this->maintenance_config->is_maintenance_enabled() && !$this->maintenance_config->is_unlimited_maintenance() && $maintenance_terminates_after_tomorrow)
				$this->maintain_type = 'until';
			else
				$this->maintain_type = 'disabled';
		}

		return $this->maintain_type;
	}

	private function build_maintain_select_options()
	{
		$options = array(
			new FormFieldSelectChoiceOption($this->lang['common.no'], 'disabled'),
			new FormFieldSelectChoiceOption($this->lang['admin.maintenance.type.during'], 'during'),
			new FormFieldSelectChoiceOption($this->lang['admin.maintenance.type.until'], 'until'),
			new FormFieldSelectChoiceOption($this->lang['admin.maintenance.type.unlimited'], 'unlimited')
		);

		return $options;
	}

	private function get_maintain_during_select_option()
	{
		$option = 0;

		if (!$this->maintenance_config->is_unlimited_maintenance())
		{
			$time_until_maintain_end = $this->maintenance_config->get_end_date() !== null ? ($this->maintenance_config->get_end_date()->get_timestamp(Timezone::SERVER_TIMEZONE) - time()) : 0;

			foreach ($this->maintain_delay_list as $value => $label)
			{
				if ($time_until_maintain_end > 57600)
				{
					$option = '';
				}
				else if ($time_until_maintain_end - $value > 0)
				{
					$option = $value;
				}
			}
		}

		return $option;
	}

	private function build_maintain_during_select_options()
	{
		$options = array(new FormFieldSelectChoiceOption('', ''));

		foreach ($this->maintain_delay_list as $key => $value)
		{
			$options[] = new FormFieldSelectChoiceOption($value, $key);
		}

		return $options;
	}

	private function get_maintain_until_date()
	{
		$maintain_until_date = null;

		if ($this->get_maintain_type() == 'until')
		{
			$maintain_until_date = new Date($this->maintenance_config->get_end_date()->get_timestamp(Timezone::USER_TIMEZONE), Timezone::SERVER_TIMEZONE);
		}

		return $maintain_until_date;
	}

	private function save()
	{
		$this->maintain_type = $this->form->get_value('maintain_type')->get_raw_value();
		switch ($this->maintain_type)
		{
			case 'during':
				$maintain_during = $this->form->get_value('maintain_during')->get_raw_value();
				$this->maintenance_config->enable_maintenance();
				$this->maintenance_config->set_unlimited_maintenance(false);
				$this->maintenance_config->set_end_date(new Date(time() + 5 + $maintain_during, Timezone::SERVER_TIMEZONE));
			break;
			case 'until':
				$this->maintenance_config->enable_maintenance();
				$this->maintenance_config->set_unlimited_maintenance(false);
				$this->maintenance_config->set_end_date($this->form->get_value('maintain_until'));
			break;
			case 'unlimited':
				$this->maintenance_config->enable_maintenance();
				$this->maintenance_config->set_unlimited_maintenance(true);
			break;
			default:
				$this->maintenance_config->disable_maintenance();
				$this->maintenance_config->set_end_date(new Date());
		}

		if ($this->form->get_value('display_duration'))
		{
			$this->maintenance_config->set_display_duration(true);
			$this->maintenance_config->set_display_duration_for_admin($this->form->get_value('display_duration_for_admin'));
		}
		else
			$this->maintenance_config->set_display_duration(false);

		$this->maintenance_config->set_message($this->form->get_value('message'));
		$this->maintenance_config->set_auth($this->form->get_value('authorizations')->build_auth_array());

		MaintenanceConfig::save();

		HooksService::execute_hook_action('edit_config', 'kernel', array('title' => $this->lang['admin.maintenance'], 'url' => AdminMaintainUrlBuilder::maintain()->rel()));
	}
}
?>
