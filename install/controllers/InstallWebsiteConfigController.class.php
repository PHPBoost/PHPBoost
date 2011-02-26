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
		if ($this->submit_button->has_been_submitted() && $this->form->validate())
		{
			$this->handle_form();
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('websiteForm');

		$fieldset = new FormFieldsetHTML('yourSite', $this->lang['website.yours']);
		$this->form->add_fieldset($fieldset);

		$host = new FormFieldTextEditor('host', $this->lang['website.host'], $this->current_server_host(),
		array('description' => $this->lang['website.host.explanation'], 'required' => $this->lang['website.host.required']));
		$host->add_event('change', $this->warning_if_not_equals($host, $this->lang['website.host.warning']));
		$host->add_constraint(new FormFieldConstraintUrl());
		$fieldset->add_field($host);
		$path = new FormFieldTextEditor('path', $this->lang['website.path'], $this->current_server_path(),
		array('description' => $this->lang['website.path.explanation'], 'required' => true));
		$path->add_event('change', $this->warning_if_not_equals($path, $this->lang['website.path.warning']));
		$fieldset->add_field($path);
		$name = new FormFieldTextEditor('name', $this->lang['website.name'], '', array('required' => $this->lang['website.name.required']));
		$fieldset->add_field($name);
		$description = new FormFieldMultiLineTextEditor('description', $this->lang['website.description'], '',
		array('description' => $this->lang['website.description.explanation']));
		$fieldset->add_field($description);
		$meta_keywords = new FormFieldMultiLineTextEditor('metaKeywords', $this->lang['website.metaKeywords'], '',
		array('description' => $this->lang['website.metaKeywords.explanation']));
		$fieldset->add_field($meta_keywords);
		$timezone = new FormFieldTimezone('timezone', $this->lang['website.timezone'], 'UTC+0',
		array('description' => $this->lang['website.timezone.explanation']));
		$fieldset->add_field($timezone);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLink($this->lang['step.previous'], InstallUrlBuilder::database(), 'templates/images/left.png');
		$action_fieldset->add_element($back);
		$this->submit_button = new FormButtonSubmitImg($this->lang['step.next'], 'templates/images/right.png', 'website');
		$action_fieldset->add_element($this->submit_button);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_form()
	{
		$installation_services = new InstallationServices();
		$installation_services->configure_website(
		$this->form->get_value('host'), $this->form->get_value('path'),
		$this->form->get_value('name'), $this->form->get_value('description'),
		$this->form->get_value('metaKeywords'), $this->form->get_value('timezone')->get_raw_value());
		AppContext::get_response()->redirect(InstallUrlBuilder::admin());
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

	private function warning_if_not_equals(FormField $field, $message)
	{
		$tpl = new StringTemplate('var field = $FF(${escapejs(ID)});
var value = ${escapejs(VALUE)};
if(field.getValue()!=value && !confirm(${escapejs(MESSAGE)})){field.setValue(value);}');
		$tpl->put('ID', $field->get_id());
		$tpl->put('VALUE', $field->get_value());
		$tpl->put('MESSAGE', $message);
		return $tpl->render();
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