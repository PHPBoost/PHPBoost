<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 26
 * @since       PHPBoost 3.0 - 2011 07 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminAdvancedConfigController extends AdminController
{
	private $lang;
	private $general_config;
	private $server_environment_config;
	private $sessions_config;
	private $cookiebar_config;
	private $form;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->load_lang();
		$this->load_config();

		$this->build_form($request);

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');

		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->form->get_field_by_id('redirection_www_mode')->set_hidden(!$this->server_environment_config->is_redirection_www_enabled());
			$this->form->get_field_by_id('hsts_security_enabled')->set_hidden(!$this->server_environment_config->is_redirection_https_enabled());
			$this->form->get_field_by_id('hsts_security_duration')->set_hidden(!$this->server_environment_config->is_hsts_security_enabled());
			$this->form->get_field_by_id('hsts_security_subdomain')->set_hidden(!$this->server_environment_config->is_hsts_security_enabled());
			$this->form->get_field_by_id('cookiebar_duration')->set_hidden(!$this->cookiebar_config->is_cookiebar_enabled());
			$this->form->get_field_by_id('cookiebar_tracking_mode')->set_hidden(!$this->cookiebar_config->is_cookiebar_enabled());
			$this->form->get_field_by_id('cookiebar_content')->set_hidden(!$this->cookiebar_config->is_cookiebar_enabled());
			$this->form->get_field_by_id('cookiebar_aboutcookie_title')->set_hidden(!$this->cookiebar_config->is_cookiebar_enabled());
			$this->form->get_field_by_id('cookiebar_aboutcookie_content')->set_hidden(!$this->cookiebar_config->is_cookiebar_enabled());
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
		$this->cookiebar_config = CookieBarConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('advanced-config', $this->lang['advanced-config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldUrlEditor('site_url', $this->lang['advanced-config.site_url'], $this->general_config->get_site_url(),
			array('class' => 'third-field', 'description' => $this->lang['advanced-config.site_url-explain'], 'required' => true)
		));

		$fieldset->add_field(new FormFieldTextEditor('site_path', $this->lang['advanced-config.site_path'], $this->general_config->get_site_path(),
			array('class' => 'third-field', 'description' => $this->lang['advanced-config.site_path-explain'])
		));

		$fieldset->add_field(new FormFieldTimezone('site_timezone', $this->lang['advanced-config.site_timezone'], $this->general_config->get_site_timezone(),
			array('class' => 'third-field', 'description' => $this->lang['advanced-config.site_timezone-explain'])
		));

		if ($request->get_is_localhost() || $request->get_is_subdomain())
		{
			$redirection_www_disabled = true;
			$this->server_environment_config->disable_redirection_www(); /*Disabling is forced*/
			$redirection_www_enabled_explain = '<span class="text-strong color-notavailable">' . ($request->get_is_localhost() ? $this->lang['advanced-config.redirection_www_enabled.local'] : $this->lang['advanced-config.redirection_www_enabled.subdomain']) . '</span>';
		}
		else
		{
			$redirection_www_disabled = false;
			$redirection_www_enabled_explain = '';
		}

		$fieldset->add_field( new FormFieldCheckbox('redirection_www_enabled', $this->lang['advanced-config.redirection_www_enabled'], $this->server_environment_config->is_redirection_www_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $redirection_www_enabled_explain,
				'disabled' => $redirection_www_disabled,
				'events' => array('click' => '
					if (HTMLForms.getField("redirection_www_enabled").getValue()) {
						HTMLForms.getField("redirection_www_mode").enable();
					} else {
						HTMLForms.getField("redirection_www_mode").disable();
					}'
				)
			)
		));

		$fieldset->add_field( new FormFieldSimpleSelectChoice('redirection_www_mode', $this->lang['advanced-config.redirection_www_mode'], $this->server_environment_config->get_redirection_www_mode(),
			array(
				new FormFieldSelectChoiceOption($this->lang['advanced-config.redirection_www.with_www'], ServerEnvironmentConfig::REDIRECTION_WWW_WITH_WWW),
				new FormFieldSelectChoiceOption($this->lang['advanced-config.redirection_www.without_www'], ServerEnvironmentConfig::REDIRECTION_WWW_WITHOUT_WWW)
			),
			array('hidden' => !$this->server_environment_config->is_redirection_www_enabled())
		));

		if ($request->get_is_https())
		{
			$redirection_https_disabled = false; /* Checkbox while be activated*/
			$redirection_https_enabled_explain = $this->lang['advanced-config.redirection_https_enabled.explain'];
		}
		else
		{
			$redirection_https_disabled = true; /* Checkbox is forced to deactivate*/
			$this->server_environment_config->disable_redirection_https(); /* HTTPS is forced to deactivate */
			$this->server_environment_config->disable_hsts_security(); /* HSTS is forced to deactivate */
			$redirection_https_enabled_explain = '<span class="text-strong color-notavailable">' . $this->lang['advanced-config.redirection_https_enabled.explain-disable']. '</span>';
		}

		$fieldset->add_field( new FormFieldCheckbox('redirection_https_enabled', $this->lang['advanced-config.redirection_https_enabled'], $this->server_environment_config->is_redirection_https_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $redirection_https_enabled_explain,
				'disabled' => $redirection_https_disabled,
				'events' => array('click' => '
					if (HTMLForms.getField("redirection_https_enabled").getValue()) {
						HTMLForms.getField("hsts_security_enabled").enable();
					} else {
						HTMLForms.getField("hsts_security_enabled").disable();
						HTMLForms.getField("hsts_security_enabled").setValue(false);
						HTMLForms.getField("hsts_security_duration").disable();
						HTMLForms.getField("hsts_security_subdomain").disable();
					}'
				)
			)
		));

		$fieldset->add_field( new FormFieldCheckbox('hsts_security_enabled', $this->lang['advanced-config.hsts_security_enabled'], $this->server_environment_config->is_hsts_security_enabled(), array(
			'description' => $this->lang['advanced-config.hsts_security.explain'],
			'hidden' => !$this->server_environment_config->is_redirection_https_enabled(),
			'events' => array('click' => '
				if (HTMLForms.getField("hsts_security_enabled").getValue()) {
					HTMLForms.getField("hsts_security_duration").enable();
					HTMLForms.getField("hsts_security_subdomain").enable();
				} else {
					HTMLForms.getField("hsts_security_duration").disable();
					HTMLForms.getField("hsts_security_subdomain").disable();
				}')
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('hsts_security_duration', $this->lang['advanced-config.hsts_security_duration'], $this->server_environment_config->get_config_hsts_security_duration(), array(
			'min' => 1,
			'max' => 365,
			'required' => true,
			'description' => $this->lang['advanced-config.hsts_security_duration.explain'],
			'required' => true,
			'hidden' => !$this->server_environment_config->is_hsts_security_enabled()),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu', '', $this->lang['advanced-config.integer-required']))
		));

		$fieldset->add_field(new FormFieldCheckbox('hsts_security_subdomain', $this->lang['advanced-config.hsts_security_subdomain'], $this->server_environment_config->is_hsts_security_subdomain_enabled(), array(
			'description' => $this->lang['advanced-config.hsts_security_subdomain.explain'],
			'hidden' => !$this->server_environment_config->is_hsts_security_enabled())
		));

		$url_rewriting_fieldset = new FormFieldsetHTML('url_rewriting', $this->lang['advanced-config.url-rewriting']);
		$form->add_fieldset($url_rewriting_fieldset);

		$url_rewriting_fieldset->set_description($this->lang['advanced-config.url-rewriting.explain']);

		$server_configuration = new ServerConfiguration();
		try {
			if ($server_configuration->has_url_rewriting())
			{
				$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], $this->server_environment_config->is_url_rewriting_enabled(),
					array('class' => 'half-field custom-checkbox', 'description' => '<span class="text-strong color-available">' . $this->lang['advanced-config.config.available'] . '</span>')
				));
			}
			else
			{
				$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], FormFieldCheckbox::UNCHECKED,
					array('class' => 'half-field custom-checkbox', 'disabled' => true, 'description' => '<span class="text-strong color-notavailable">' . $this->lang['advanced-config.config.not-available'] . '</span>')
				));
			}
		} catch (UnsupportedOperationException $ex) {
			$url_rewriting_fieldset->add_field(new FormFieldCheckbox('url_rewriting_enabled', $this->lang['advanced-config.url-rewriting'], $this->server_environment_config->is_url_rewriting_enabled(),
				array('class' => 'half-field custom-checkbox', 'description' => '<span class="text-strong color-unknown">' . $this->lang['advanced-config.config.unknown'] . '</span>')
			));
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
			array('required' => true, 'class' => 'third-field'),
			array(new FormFieldConstraintRegex('`^[A-Za-z0-9]+$`iu', '', $this->lang['advanced-config.cookie-name.style-wrong']))
		));

		$sessions_config_fieldset->add_field(new FormFieldNumberEditor('session_duration', $this->lang['advanced-config.cookie-duration'], $this->sessions_config->get_session_duration(),
			array('description' => $this->lang['advanced-config.cookie-duration.explain'], 'required' => true, 'class' => 'third-field'),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu', '', $this->lang['advanced-config.integer-required']))
		));

		$sessions_config_fieldset->add_field(new FormFieldNumberEditor('active_session_duration', $this->lang['advanced-config.active-session-duration'], $this->sessions_config->get_active_session_duration(),
			array('description' => $this->lang['advanced-config.active-session-duration.explain'], 'required' => true, 'class' => 'third-field'),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu', '', $this->lang['advanced-config.integer-required']))
		));

		$cookiebar_config_fieldset = new FormFieldsetHTML('cookiebar_config', $this->lang['advanced-config.cookiebar-config']);
		$form->add_fieldset($cookiebar_config_fieldset);

		$cookiebar_config_fieldset->set_description($this->lang['advanced-config.cookiebar-more']);
		$cookiebar_config_fieldset->add_field(new FormFieldCheckbox('cookiebar_enabled', $this->lang['advanced-config.cookiebar-activation'], $this->cookiebar_config->is_cookiebar_enabled(),
			array(
				'class' => 'third-field custom-checkbox',
				'description' => $this->lang['advanced-config.cookiebar-activation.desc'],
				'events' => array('click' => '
					if (HTMLForms.getField("cookiebar_enabled").getValue()) {
						HTMLForms.getField("cookiebar_duration").enable();
						HTMLForms.getField("cookiebar_tracking_mode").enable();
						HTMLForms.getField("cookiebar_content").enable();
						HTMLForms.getField("cookiebar_aboutcookie_title").enable();
						HTMLForms.getField("cookiebar_aboutcookie_content").enable();
					} else {
						HTMLForms.getField("cookiebar_duration").disable();
						HTMLForms.getField("cookiebar_tracking_mode").disable();
						HTMLForms.getField("cookiebar_content").disable();
						HTMLForms.getField("cookiebar_aboutcookie_title").disable();
						HTMLForms.getField("cookiebar_aboutcookie_content").disable();
					}'
				)
			)
		));

		$cookiebar_config_fieldset->add_field(new FormFieldNumberEditor('cookiebar_duration', $this->lang['advanced-config.cookiebar-duration'], $this->cookiebar_config->get_cookiebar_duration(),
			array('class' => 'third-field', 'min' => 1, 'max' => 13, 'required' => true, 'description' => $this->lang['advanced-config.cookiebar-duration.desc'], 'hidden' => !$this->cookiebar_config->is_cookiebar_enabled()),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'), new FormFieldConstraintIntegerRange(1, 13))
		));

		$cookiebar_config_fieldset->add_field(new FormFieldSimpleSelectChoice('cookiebar_tracking_mode', $this->lang['advanced-config.cookiebar-tracking.choice'], $this->cookiebar_config->get_cookiebar_tracking_mode(),
			array(
				new FormFieldSelectChoiceOption($this->lang['advanced-config.cookiebar-tracking.notrack'], CookieBarConfig::NOTRACKING_COOKIE),
				new FormFieldSelectChoiceOption($this->lang['advanced-config.cookiebar-tracking.track'], CookieBarConfig::TRACKING_COOKIE)
			),
			array('class' => 'third-field', 'hidden' => !$this->cookiebar_config->is_cookiebar_enabled(), 'events' => array('click' =>
				'if (HTMLForms.getField("cookiebar_tracking_mode").getValue() == \'' . CookieBarConfig::NOTRACKING_COOKIE . '\') {
					HTMLForms.getField("cookiebar_content").setValue("' . LangLoader::get_message('cookiebar-message.notracking', 'user-common') . '");
				} else {
					HTMLForms.getField("cookiebar_content").setValue("' . LangLoader::get_message('cookiebar-message.tracking', 'user-common') . '");
				}'
			))
		));

		$cookiebar_config_fieldset->add_field(new FormFieldMultiLineTextEditor('cookiebar_content', $this->lang['advanced-config.cookiebar-content'], $this->cookiebar_config->get_cookiebar_content(),
			array('rows' => 7, 'description' => $this->lang['advanced-config.cookiebar-content.explain'], 'required' => true, 'hidden' => !$this->cookiebar_config->is_cookiebar_enabled())
		));

		$cookiebar_config_fieldset->add_field(new FormFieldTextEditor('cookiebar_aboutcookie_title', $this->lang['advanced-config.cookiebar-aboutcookie-title'], $this->cookiebar_config->get_cookiebar_aboutcookie_title(),
			array('class' => 'half-field', 'required' => true, 'hidden' => !$this->cookiebar_config->is_cookiebar_enabled())
		));

		$cookiebar_config_fieldset->add_field(new FormFieldRichTextEditor('cookiebar_aboutcookie_content', $this->lang['advanced-config.cookiebar-aboutcookie'], $this->cookiebar_config->get_cookiebar_aboutcookie_content(),
			array('rows' => 7, 'description' => $this->lang['advanced-config.cookiebar-aboutcookie.explain'], 'required' => true, 'hidden' => !$this->cookiebar_config->is_cookiebar_enabled(), 'reset_value' => LangLoader::get_message('cookiebar-message.aboutcookie', 'user-common'))
		));

		$miscellaneous_fieldset = new FormFieldsetHTML('miscellaneous', $this->lang['advanced-config.miscellaneous']);
		$form->add_fieldset($miscellaneous_fieldset);

		if (function_exists('ob_gzhandler') && @extension_loaded('zlib'))
		{
			$miscellaneous_fieldset->add_field(new FormFieldCheckbox('output_gziping_enabled', $this->lang['advanced-config.output-gziping-enabled'], $this->server_environment_config->is_output_gziping_enabled(),
				array(
					'class' => 'custom-checkbox',
					'description' => '<span class="text-strong color-available">' . $this->lang['advanced-config.config.available'] . '</span>'
				)
			));
		}
		else
		{
			$miscellaneous_fieldset->add_field(new FormFieldCheckbox('output_gziping_enabled', $this->lang['advanced-config.output-gziping-enabled'], FormFieldCheckbox::UNCHECKED,
				array(
					'class' => 'custom-checkbox',
					'description' => '<span class="text-strong color-notavailable">' . $this->lang['advanced-config.config.not-available'] . '</span>',
					'disabled' => true
				)
			));
		}

		$miscellaneous_fieldset->add_field(new FormFieldCheckbox('debug_mode_enabled', $this->lang['advanced-config.debug-mode'], Debug::is_debug_mode_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['advanced-config.debug-mode.explain'],
				'events' => array('change' => '
					if (HTMLForms.getField("debug_mode_enabled").getValue()) {
						HTMLForms.getField("debug_mode_type").enable();
						HTMLForms.getField("display_database_query_enabled").enable();
					} else {
						HTMLForms.getField("debug_mode_type").disable();
						HTMLForms.getField("display_database_query_enabled").disable();
					}'
				)
			)
		));

		$miscellaneous_fieldset->add_field(new FormFieldSimpleSelectChoice('debug_mode_type', $this->lang['advanced-config.debug-mode.type'], Debug::is_strict_mode_enabled(),
			array(
				new FormFieldSelectChoiceOption($this->lang['advanced-config.debug-mode.type.normal'], '0'),
				new FormFieldSelectChoiceOption($this->lang['advanced-config.debug-mode.type.strict'], '1')
			),
			array('hidden' => !Debug::is_debug_mode_enabled())
		));

		$miscellaneous_fieldset->add_field(new FormFieldCheckbox('display_database_query_enabled', $this->lang['advanced-config.debug-display-database-query-enabled'], Debug::is_display_database_query_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !Debug::is_debug_mode_enabled()
			)
		));

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

		if ($this->form->get_value('redirection_www_enabled'))
		{
			$this->server_environment_config->enable_redirection_www();
			$this->server_environment_config->set_redirection_www_mode($this->form->get_value('redirection_www_mode')->get_raw_value());
		}
		else
			$this->server_environment_config->disable_redirection_www();

		if ($this->form->get_value('redirection_https_enabled'))
		{
			$this->server_environment_config->enable_redirection_https();
			if ($this->form->get_value('hsts_security_enabled'))
			{
				$this->server_environment_config->enable_hsts_security();
				$this->server_environment_config->set_hsts_security_duration($this->form->get_value('hsts_security_duration'));
				if ($this->form->get_value('hsts_security_subdomain'))
				{
					$this->server_environment_config->enable_hsts_subdomain_security();
				}
				else
					$this->server_environment_config->disable_hsts_subdomain_security();
			}
			else
				$this->server_environment_config->disable_hsts_security();
		}
		else
		{
			$this->server_environment_config->disable_redirection_https();
			$this->server_environment_config->disable_hsts_security();
		}

		$this->server_environment_config->set_htaccess_manual_content(TextHelper::html_entity_decode($this->form->get_value('htaccess_manual_content')));

		$robots_file = new File(PATH_TO_ROOT . '/robots.txt');
		$robots_file->write($this->form->get_value('robots_content'));

		if (!$this->form->field_is_disabled('output_gziping_enabled'))
		{
			$this->server_environment_config->set_output_gziping_enabled($this->form->get_value('output_gziping_enabled'));
		}

		ServerEnvironmentConfig::save();

		if ($this->form->get_value('cookiebar_enabled'))
		{
			$this->cookiebar_config->enable_cookiebar();
			$this->cookiebar_config->set_cookiebar_duration($this->form->get_value('cookiebar_duration'));
			$this->cookiebar_config->set_cookiebar_tracking_mode($this->form->get_value('cookiebar_tracking_mode')->get_raw_value());
			$this->cookiebar_config->set_cookiebar_content($this->form->get_value('cookiebar_content'));
			$this->cookiebar_config->set_cookiebar_aboutcookie_title($this->form->get_value('cookiebar_aboutcookie_title'));
			$this->cookiebar_config->set_cookiebar_aboutcookie_content($this->form->get_value('cookiebar_aboutcookie_content'));
		}
		else
			$this->cookiebar_config->disable_cookiebar();

		CookieBarConfig::save();

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
		NginxFileCache::regenerate();
	}

	private function clear_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}
}
?>
