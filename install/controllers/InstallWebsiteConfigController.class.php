<?php
/*##################################################
 *                         InstallWebsiteConfigController.class.php
 *                            -------------------
 *   begin                : October 03 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class InstallWebsiteConfigController extends InstallController
{
	/**
	 * @var Template
	 */
	private $view;

	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var HTMLForm
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
            $installation_services = new InstallationServices(LangLoader::get_locale());
			$installation_services->configure_website(
			$this->form->get_value('host'), $this->form->get_value('path'),
			$this->form->get_value('name'), $this->form->get_value('description'),
			$this->form->get_value('metaKeywords'), $this->form->get_value('timezone'));
			AppContext::get_response()->redirect(InstallUrlBuilder::admin());
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('websiteForm');

		$fieldset = new FormFieldsetHTML('yourSite', $this->lang['website.yours']);
		$this->form->add_fieldset($fieldset);

		$host = new FormFieldTextEditor('host', $this->lang['website.host'], $this->current_server_host(),
		array('description' => $this->lang['website.host.explanation'], 'required' => true));
		$host->add_constraint(new FormFieldConstraintUrl());
		$fieldset->add_field($host);
		$path = new FormFieldTextEditor('path', $this->lang['website.path'], $this->current_server_path(),
		array('description' => $this->lang['website.path.explanation'], 'required' => true));
		$fieldset->add_field($path);
		$name = new FormFieldTextEditor('name', $this->lang['website.name'], '', array('required' => true));
		$fieldset->add_field($name);
		$description = new FormFieldMultiLineTextEditor('description', $this->lang['website.description'], '',
		array('description' => $this->lang['website.description.explanation']));
		$fieldset->add_field($description);
		$meta_keywords = new FormFieldMultiLineTextEditor('metaKeywords', $this->lang['website.metaKeywords'], '',
		array('description' => $this->lang['website.metaKeywords.explanation']));
		$fieldset->add_field($meta_keywords);
		$options = array();
		for ($i = -12; $i <= 14; $i++)
		{
			$options[] = new FormFieldSelectChoiceOption('UTC' . ($i >= 0 ? '+' : '') . $i, $i);
		}
		$timezone = new FormFieldSelectChoice('timezone', $this->lang['website.timezone'], 'UTC+0', $options,
		array('description' => $this->lang['website.timezone.explanation']));
		$fieldset->add_field($timezone);

		$this->submit_button = new FormButtonSubmitImg($this->lang['step.next'], 'templates/images/right.png', 'submit');
		$this->form->add_button($this->submit_button);
	}

	private function current_server_host()
	{
		return 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));
	}

	private function current_server_path()
	{
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		if (!$server_path)
		{
			$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		}
		$server_path = trim(preg_replace('`/install$`', '', dirname($server_path)));
		return $server_path = ($server_path == '/') ? '' : $server_path;
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('install/website.tpl');
		$this->view->put('WEBSITE_FORM', $this->form->display());
		$step_title = $this->lang['step.websiteConfig.title'];
		$response = new InstallDisplayResponse(4, $step_title, $this->view);
		return $response;
	}
}
?>