<?php
/*##################################################
 *                       AdminAdvancedConfigController.class.php
 *                            -------------------
 *   begin                : July 1, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
	private $form;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->load_lang();
		$this->load_config();
		
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('debug_mode_type')->set_hidden(!Debug::is_debug_mode_enabled());
			$this->form->get_field_by_id('display_database_query_enabled')->set_hidden(!Debug::is_debug_mode_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
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
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('advanced-config', $this->lang['advanced-config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldUrlEditor('site_url', $this->lang['advanced-config.site_url'], $this->general_config->get_site_url(),
			array('description' => $this->lang['advanced-config.site_url-explain'], 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('site_path', $this->lang['advanced-config.site_path'], $this->general_config->get_site_path(),
			array('description' => $this->lang['advanced-config.site_path-explain'])
		));
		
		$fieldset->add_field(new FormFieldTimezone('site_timezone', $this->lang['advanced-config.site_timezone'], $this->general_config->get_site_timezone(),
			array('description' => $this->lang['advanced-config.site_timezone-explain'])
		));
		
		$url_rewriting_fieldset = new FormFieldsetHTML('url_rewriting', $this->lang['advanced-config.url-rewriting']);
		$form->add_fieldset($url_rewriting_fieldset);
		
		$url_rewriting_fieldset->set_description($this->lang['advanced-config.url-rewriting.explain']);
		
		$server_configuration = new ServerConfiguration();
		try {
			if ($server_configuration->has_url_rewriting())
			{
				$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], $this->server_environment_config->is_url_rewriting_enabled(), array('description' => '<span class="text-strong color-available">' . $this->lang['advanced-config.config.available'] . '</span>')));
			}
			else
			{
				$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], FormFieldCheckbox::UNCHECKED, array('disabled' => true, 'description' => '<span class="text-strong color-notavailable">' . $this->lang['advanced-config.config.not-available'] . '</span>')));
			}
		} catch (UnsupportedOperationException $ex) {
			$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], $this->server_environment_config->is_url_rewriting_enabled(), array('description' => '<span class="text-strong color-unknown">' . $this->lang['advanced-config.config.unknown'] . '</span>')));
 		}
		
		$htaccess_manual_content_fieldset = new FormFieldsetHTML('htaccess_manual_content', $this->lang['advanced-config.htaccess-manual-content']);
		$form->add_fieldset($htaccess_manual_content_fieldset);
		
		$htaccess_manual_content_fieldset->add_field(new FormFieldMultiLineTextEditor('htaccess_manual_content', $this->lang['advanced-config.htaccess-manual-content'], $this->server_environment_config->get_htaccess_manual_content(),
			array('rows' => 7, 'description' => $this->lang['advanced-config.htaccess-manual-content.explain'])
		));
		
		$robots_file = new File(PATH_TO_ROOT . '/robots.txt');
		$robots_content = $robots_file->exists() ? $robots_file->read() : '';
		$robots_content_fieldset = new FormFieldsetHTML('robots_content', $this->lang['advanced-config.robots-content']);
		$form->add_fieldset($robots_content_fieldset);
		
		$robots_content_fieldset->add_field(new FormFieldMultiLineTextEditor('robots_content', $this->lang['advanced-config.robots-content'], $robots_content,
			array('rows' => 7, 'description' => $this->lang['advanced-config.robots-content.explain'])
		));
		
		$sessions_config_fieldset = new FormFieldsetHTML('sessions_config', $this->lang['advanced-config.sessions-config']);
		$form->add_fieldset($sessions_config_fieldset);
		
		$sessions_config_fieldset->add_field(new FormFieldTextEditor('cookie_name', $this->lang['advanced-config.cookie-name'], $this->sessions_config->get_cookie_name(),
			array('required' => true),
			array(new FormFieldConstraintRegex('`^[A-Za-z0-9]+$`i', '', $this->lang['advanced-config.cookie-name.style-wrong']))
		));
		
		$sessions_config_fieldset->add_field(new FormFieldNumberEditor('session_duration', $this->lang['advanced-config.cookie-duration'], $this->sessions_config->get_session_duration(),
			array('description' => $this->lang['advanced-config.cookie-duration.explain'], 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i', '', $this->lang['advanced-config.integer-required']))
		));
		
		$sessions_config_fieldset->add_field(new FormFieldNumberEditor('active_session_duration', $this->lang['advanced-config.active-session-duration'], $this->sessions_config->get_active_session_duration(),
			array('description' => $this->lang['advanced-config.active-session-duration.explain'], 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i', '', $this->lang['advanced-config.integer-required']))
		));
		
		$miscellaneous_fieldset = new FormFieldsetHTML('miscellaneous', $this->lang['advanced-config.miscellaneous']);
		$form->add_fieldset($miscellaneous_fieldset);
		
		if (function_exists('ob_gzhandler') && @extension_loaded('zlib'))
		{
			$miscellaneous_fieldset->add_field(new FormFieldCheckbox('output_gziping_enabled', $this->lang['advanced-config.output-gziping-enabled'], $this->server_environment_config->is_output_gziping_enabled(),
				array('description' => '<span class="text-strong color-available">' . $this->lang['advanced-config.config.available'] . '</span>')
			));
		}
		else
		{
			$miscellaneous_fieldset->add_field(new FormFieldCheckbox('output_gziping_enabled', $this->lang['advanced-config.output-gziping-enabled'], FormFieldCheckbox::UNCHECKED, 
				array('description' => '<span class="text-strong color-notavailable">' . $this->lang['advanced-config.config.not-available'] . '</span>', 'disabled' => true)
			));
		}
		
		$miscellaneous_fieldset->add_field(new FormFieldCheckbox('debug_mode_enabled', $this->lang['advanced-config.debug-mode'], Debug::is_debug_mode_enabled(), 
		array('description' => $this->lang['advanced-config.debug-mode.explain'], 'events' => array('change' => '
				if (HTMLForms.getField("debug_mode_enabled").getValue()) { 
					HTMLForms.getField("debug_mode_type").enable();
					HTMLForms.getField("display_database_query_enabled").enable();
				} else { 
					HTMLForms.getField("debug_mode_type").disable();
					HTMLForms.getField("display_database_query_enabled").disable();
				}'))));
		
		$miscellaneous_fieldset->add_field(new FormFieldSimpleSelectChoice('debug_mode_type', $this->lang['advanced-config.debug-mode.type'], Debug::is_strict_mode_enabled(),
			array(
				new FormFieldSelectChoiceOption($this->lang['advanced-config.debug-mode.type.normal'], '0'),
				new FormFieldSelectChoiceOption($this->lang['advanced-config.debug-mode.type.strict'], '1')
			), 
			array('hidden' => !Debug::is_debug_mode_enabled())
		));
		
		$miscellaneous_fieldset->add_field(new FormFieldCheckbox('display_database_query_enabled', $this->lang['advanced-config.debug-display-database-query-enabled'], Debug::is_display_database_query_enabled(), 
			array('hidden' => !Debug::is_debug_mode_enabled())));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
		$this->general_config->set_site_url($this->form->get_value('site_url'));
		$this->general_config->set_site_path($this->form->get_value('site_path'));
		$this->general_config->set_site_timezone($this->form->get_value('site_timezone')->get_raw_value());
		GeneralConfig::save();
		
		$this->sessions_config->set_cookie_name($this->form->get_value('cookie_name'));
		$this->sessions_config->set_session_duration($this->form->get_value('session_duration'));
		$this->sessions_config->set_active_session_duration($this->form->get_value('active_session_duration'));
		SessionsConfig::save();
		
		if (!$this->form->field_is_disabled('url_rewriting_enabled'))
		{
			$this->server_environment_config->set_url_rewriting_enabled($this->form->get_value('url_rewriting_enabled'));
		}
		
		$this->server_environment_config->set_htaccess_manual_content(TextHelper::html_entity_decode($this->form->get_value('htaccess_manual_content')));
		
		$robots_file = new File(PATH_TO_ROOT . '/robots.txt');
		$robots_file->write($this->form->get_value('robots_content'));
		
		if (!$this->form->field_is_disabled('output_gziping_enabled'))
		{
			$this->server_environment_config->set_output_gziping_enabled($this->form->get_value('output_gziping_enabled'));
		}
		
		ServerEnvironmentConfig::save();
		$this->clear_cache();
		
		if ($this->form->get_value('debug_mode_enabled'))
		{
			$options = array();
			if ($this->form->get_value('debug_mode_type')->get_raw_value() == '1')
			{
				$options[Debug::STRICT_MODE] = true;
			}
			if ($this->form->get_value('display_database_query_enabled'))
			{
				$options[Debug::DISPLAY_DATABASE_QUERY] = true;
			}
			Debug::enabled_debug_mode($options);
		}
		else
		{
			Debug::disable_debug_mode();
		}
		
		HtaccessFileCache::regenerate();
	}
	
	private function clear_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}
}
?>