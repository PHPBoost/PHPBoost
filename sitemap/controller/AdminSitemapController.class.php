<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSitemapController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->handle_form();

			$this->form->get_field_by_id('file_life_time')->set_hidden(!SitemapXMLFileService::is_xml_file_generation_enabled());

			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->build_response($this->view);
	}

	private function build_form()
	{
		$this->form = new HTMLForm('sitemap_global_config', SitemapUrlBuilder::get_general_config()->rel());
		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('enable_sitemap_xml', $this->lang['sitemap.auto.generate.xml'], SitemapXMLFileService::is_xml_file_generation_enabled() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED,
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => 'if ($FF("enable_sitemap_xml").getValue()) { $FF("file_life_time").enable(); } else { $FF("file_life_time").disable(); }'
			)
		)));

		$fieldset->add_field(new FormFieldNumberEditor('file_life_time', $this->lang['sitemap.xml.life.time'], SitemapXMLFileService::get_life_time(),
			array(
				'required' => true, 'min' => 0,
				'description' => $this->lang['sitemap.xml.life.time.clue'],
				'hidden' => !SitemapXMLFileService::is_xml_file_generation_enabled()
			),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_button(new FormButtonReset());
	}

	private function handle_form()
	{
		if ($this->form->get_value('enable_sitemap_xml'))
		{
			$this->config->enable_sitemap_xml_generation();
			$this->config->set_sitemap_xml_life_time((int)$this->form->get_value('file_life_time'));
		}
		else
		{
			$this->config->disable_sitemap_xml_generation();
		}

		SitemapConfig::save();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}

	private function build_response(Template $view)
	{
		$response = new AdminSitemapResponse($view);
		$response->get_graphical_environment()->set_page_title(StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		return $response;
	}
}
?>
