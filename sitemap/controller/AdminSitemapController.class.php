<?php
/*##################################################
 *                        AdminSitemapController.class.php
 *                            -------------------
 *   begin                : December 09 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class AdminSitemapController extends AdminModuleController
{
	private $lang = array();
	/**
	 * @var HTMLForm
	 */
	private $form = null;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;
	
	public function __construct()
	{
		$this->lang = LangLoader::get('common', 'sitemap');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->handle_form();
			$this->form->get_field_by_id('file_life_time')->set_hidden(!SitemapXMLFileService::is_xml_file_generation_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->build_response($tpl);
	}

	private function build_form()
	{
		$this->form = new HTMLForm('sitemap_global_config', SitemapUrlBuilder::get_general_config()->rel());
		$fieldset = new FormFieldsetHTML('general_config', $this->lang['general_config']);
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('enable_sitemap_xml', $this->lang['auto_generate_xml_file'], SitemapXMLFileService::is_xml_file_generation_enabled() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED,
			array('events' => array('click' => 'if ($FF("enable_sitemap_xml").getValue()) { $FF("file_life_time").enable(); } else { $FF("file_life_time").disable(); }'))));

		$fieldset->add_field(new FormFieldNumberEditor('file_life_time', $this->lang['xml_file_life_time'], SitemapXMLFileService::get_life_time(),
			array('required' => true, 'min' => 0, 'description' => $this->lang['xml_file_life_time_explain'], 'hidden' => !SitemapXMLFileService::is_xml_file_generation_enabled()),
			array(new FormFieldConstraintIntegerRange(1, 50))));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_button(new FormButtonReset());
	}

	private function handle_form()
	{
		$config = SitemapConfig::load();
		if ($this->form->get_value('enable_sitemap_xml'))
		{
			$config->enable_sitemap_xml_generation();
			$config->set_sitemap_xml_life_time((int)$this->form->get_value('file_life_time'));
		}
		else
		{
			$config->disable_sitemap_xml_generation();
		}

		SitemapConfig::save($config);
	}

	private function build_response(Template $view)
	{
		$response = new AdminSitemapResponse($view);
		$response->get_graphical_environment()->set_page_title($this->lang['general_config']);
		return $response;
	}
}
?>