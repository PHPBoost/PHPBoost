<?php
/*##################################################
 *                       AdminAdvancedConfigController.class.php
 *                            -------------------
 *   begin                : July 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminAdvancedConfigController extends AdminController
{
	private $lang;
	private $general_config;
	private $server_environment_config;
	private $sessions_config;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$this->load_lang();
		$this->load_config();
		
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$tpl->put('MSG', MessageHelper::display($this->lang['advanced-config.success'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminConfigDisplayResponse($tpl, $this->lang['advanced-config']);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
	}

	private function load_config()
	{
		$this->general_config = GeneralConfig::load();
		$this->server_environment_config = ServerEnvironmentConfig::load();
		$this->sessions_config = SessionsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('advanced-config');
		
		$fieldset = new FormFieldsetHTML('advanced-config', $this->lang['advanced-config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('site_url', $this->lang['advanced-config.site_url'], $this->general_config->get_site_url(), array(
			'class' => 'text', 'description' => $this->lang['advanced-config.site_url-explain'], 'maxlength' => 25, 'size' => 25, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('site_path', $this->lang['advanced-config.site_path'], $this->general_config->get_site_path(),
			array('class' => 'text', 'required' => true, 'description' => $this->lang['advanced-config.site_path-explain'])
		));
		
		$fieldset->add_field(new FormFieldTimezone('site_timezone', $this->lang['advanced-config.site_timezone'], $this->general_config->get_site_timezone(),
		array('description' => $this->lang['advanced-config.site_timezone-explain'])));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
	
	}
}
?>