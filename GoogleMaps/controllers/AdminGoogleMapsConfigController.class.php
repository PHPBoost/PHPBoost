<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 15
 * @since       PHPBoost 5.0 - 2017 03 26
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminGoogleMapsConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			if ($this->config->get_api_key())
			{
				$this->form->get_field_by_id('default_position')->set_hidden(false);
				$this->form->get_field_by_id('default_position')->enable();
			}
			else
				$this->form->get_field_by_id('default_position')->set_hidden(true);

			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->build_response($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('api_key', $this->lang['config.api.key'], $this->config->get_api_key(),
			array('class' => 'half-field top-field', 'description' => $this->lang['config.api.key.desc'])
		));

		$fieldset->add_field(new GoogleMapsFormFieldMapAddress('default_position', $this->lang['config.default_marker.position'], new GoogleMapsMarker($this->config->get_default_marker_address(), $this->config->get_default_marker_latitude(), $this->config->get_default_marker_longitude(), '', $this->config->get_default_zoom()),
			array('class' => 'half-field', 'description' => $this->lang['config.default_marker.position.description'], 'always_display_marker' => true, 'hidden' => !$this->config->get_api_key())
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_api_key($this->form->get_value('api_key'));

		if ($this->form->get_value('api_key') && !$this->form->get_field_by_id('default_position')->is_hidden())
		{
			$default_position = TextHelper::unserialize($this->form->get_value('default_position'));
			if ($default_position)
			{
				$default_marker = new GoogleMapsMarker();
				$default_marker->set_properties($default_position);

				$this->config->set_default_marker_address($default_marker->get_address());
				$this->config->set_default_marker_latitude($default_marker->get_latitude());
				$this->config->set_default_marker_longitude($default_marker->get_longitude());
				$this->config->set_default_zoom($default_marker->get_zoom());
			}
		}

		GoogleMapsConfig::save();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}

	private function build_response(View $view)
	{
		$title = $this->lang['form.configuration'];

		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($title);
		$response->add_link($title, GoogleMapsUrlBuilder::configuration());
		$response->add_link($this->lang['form.documentation'], ModulesManager::get_module('GoogleMaps')->get_configuration()->get_documentation());
		$response->get_graphical_environment()->set_page_title(StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));

		return $response;
	}
}
?>
