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

class AdminSitemapController extends AdminController
{
	private $lang = array();
	/**
	 * @var Form
	 */
	private $form = null;
	/**
	 * @return View
	 */
	private $view;

	public function __construct()
	{
		$this->lang = LangLoader::get('common', 'sitemap');
	}

	public function execute(HTTPRequest $request)
	{
		$this->build_form();

		if ($request->is_post_method() && $this->form->validate())
		{
			$this->handle_form();
		}

		return $this->build_response($this->form->export());
	}

	private function build_form()
	{
		$this->form = new HTMLForm('sitemap_global_config', SitemapUrlBuilder::get_general_config()->absolute());
		$fieldset = new FormFieldset($this->lang['general_config']);
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('enable_sitemap_xml', $this->lang['auto_generate_xml_file'], SitemapXMLFileService::is_xml_file_generation_enabled() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED));

		$fieldset->add_field(new FormFieldTextEditor('file_life_time', $this->lang['xml_file_life_time'], SitemapXMLFileService::get_life_time(),
		array('required' => true, 'size' => 2, 'maxlength' => 2, 'description' => $this->lang['xml_file_life_time_explain']),
		array(new IntegerIntervalFormFieldConstraint(1, 50, 'message qui devrait être généré automatiquement'))));
	}

	private function handle_form()
	{
		$config = SitemapConfig::load();
		if ($this->form->get_value('enable_sitemap_xml'))
		{
			$config->enable_sitemap_xml_generation();
		}
		else
		{
			$config->disable_sitemap_xml_generation();
		}

		$config->set_sitemap_xml_life_time((int)$this->form->get_value('file_life_time'));
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