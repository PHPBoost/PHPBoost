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
		$this->init();

		if ($request->is_post_method() && $this->form->validate())
		{
			$this->handle_form();
		}

		$this->view->add_subtemplate('FORM', $this->form->export());

		return $this->build_response();
	}

	private function init()
	{
		$this->build_form();
		$this->view = new View('sitemap/' . __CLASS__ . '.tpl');
		
		$this->view->assign_vars(array(
			'U_GENERATE' => SitemapUrlBuilder::get_xml_file_generation()->absolute()
		));
		$this->view->add_lang(LangLoader::get_class(__FILE__, 'sitemap'));
	}

	/**
	 * @return Form
	 */
	private function build_form()
	{
		$form = new Form('sitemap_global_config', SitemapUrlBuilder::get_general_config()->absolute());
		$fieldset = new FormFieldset($this->lang['general_config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormCheckbox('enable_sitemap_xml', 'Enable sitemap.xml', FormCheckbox::CHECKED));

		$fieldset->add_field(new FormTextEdit('file_life_time', SitemapXMLFileService::get_life_time(), array('title' => 'Life time'), array(
		new IntegerIntervalFormFieldConstraint(1, 50, 'message qui devrait être généré automatiquement'))));

		$this->form = $form;
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

	private function build_response()
	{
		$response = new AdminSitemapResponse($this->view);
		$response->get_graphical_environment()->set_page_title($this->lang['general_config']);
		return $response;
	}
}
?>