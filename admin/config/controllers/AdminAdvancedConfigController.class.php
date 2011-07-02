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
		
		$url_rewriting_fieldset = new FormFieldsetHTML('url-rewriting', $this->lang['url-rewriting']);
		$form->add_fieldset($url_rewriting_fieldset);
		
		$url_rewriting_fieldset->set_description($this->lang['url-rewriting.explain']);
		
		$server_configuration = new ServerConfiguration();
		if ($server_configuration->has_url_rewriting())
		{
			$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url-rewriting', $this->lang['url-rewriting'], $this->server_environment_config->is_url_rewriting_enabled(), array('description' => $this->lang['url-rewriting.available'])));
		}
		else
		{
			$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url-rewriting', $this->lang['url-rewriting'], FormFieldCheckbox::UNCHECKED, array('disabled' => true, 'description' => $this->lang['url-rewriting.not-available'])));
		}
		
		$htaccess_manual_content_fieldset = new FormFieldsetHTML('htaccess-manual-content', $this->lang['htaccess-manual-content']);
		$form->add_fieldset($htaccess_manual_content_fieldset);
		
		$htaccess_manual_content_fieldset->add_field(new FormFieldMultiLineTextEditor('htaccess-manual-content', $this->lang['htaccess-manual-content'], $this->server_environment_config->get_htaccess_manual_content(),
			array('rows' => 7, 'description' => $this->lang['htaccess-manual-content.explain'])
		));
		
		$sessions_config_fieldset = new FormFieldsetHTML('sessions-config', $this->lang['sessions-config']);
		$form->add_fieldset($sessions_config_fieldset);
		
		$sessions_config_fieldset->add_field(new FormFieldTextEditor('cookie-name', $this->lang['sessions-config.cookie-name'], $this->sessions_config->get_cookie_name(), array(
			'class' => 'text','maxlength' => 25, 'size' => 25, 'required' => true)
		));
		
		$sessions_config_fieldset->add_field(new FormFieldTextEditor('cookie-duration', $this->lang['sessions-config.cookie-duration'], $this->sessions_config->get_session_duration(), array(
			'class' => 'text','maxlength' => 25, 'description' => $this->lang['sessions-config.cookie-duration.explain'], 'size' => 8, 'required' => true)
		));
		
		$sessions_config_fieldset->add_field(new FormFieldTextEditor('active-session-duration', $this->lang['sessions-config.active-session-duration'], $this->sessions_config->get_active_session_duration(), array(
			'class' => 'text','maxlength' => 25, 'description' => $this->lang['sessions-config.active-session-duration.explain'], 'size' => 8, 'required' => true)
		));
		
		$miscellaneous_fieldset = new FormFieldsetHTML('miscellaneous', $this->lang['miscellaneous']);
		$form->add_fieldset($miscellaneous_fieldset);
		
		$miscellaneous_fieldset->add_field(new FormFieldCheckbox('output-gziping-enabled', $this->lang['miscellaneous.output-gziping-enabled'], $this->server_environment_config->is_output_gziping_enabled(), 
		array('description' => $this->lang['miscellaneous.output-gziping-enabled.explain'])));
		
		//TODO, send mail for unlock administration
		$miscellaneous_fieldset->add_field(new FormFieldFree('unlock-administration', $this->lang['miscellaneous.unlock-administration'], $this->lang['miscellaneous.unlock-administration.request'], 
		array('description' => $this->lang['miscellaneous.unlock-administration.explain'])));
		
		$miscellaneous_fieldset->add_field(new FormFieldCheckbox('debug-mod', $this->lang['miscellaneous.debug-mod'], Debug::is_debug_mode_enabled(), 
		array('description' => $this->lang['miscellaneous.debug-mod.explain'], 'events' => array('change' => '
				if (HTMLForms.getField("debug_mod").getValue()) { 
					HTMLForms.getField("debug_mod_type").enable(); 
				} else { 
					HTMLForms.getField("debug_mod_type").disable(); 
				}'))));
		
		//TODO, change for new function.
		$miscellaneous_fieldset->add_field(new FormFieldSimpleSelectChoice('debug-mod-type', $this->lang['miscellaneous.debug-mod.type'], Debug::is_debug_mode_enabled(),
			array(
				new FormFieldSelectChoiceOption($this->lang['miscellaneous.debug-mod.type.normal'], '2'),
				new FormFieldSelectChoiceOption($this->lang['miscellaneous.debug-mod.type.strict'], '3')
			)
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
		//TODO, save configuration
	}
}
?>