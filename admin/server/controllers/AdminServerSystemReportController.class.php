<?php
/*##################################################
 *                              AdminServerSystemReportController.class.php
 *                            -------------------
 *   begin                : May 20, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 ################################################### */

class AdminServerSystemReportController extends AdminController
{
	private $admin_lang;
	private $form;
	private $tpl;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$this->tpl->put('FORM', $this->form->display());

		return new AdminServerDisplayResponse($this->tpl, $this->admin_lang['system_report']);
	}

	private function init()
	{
		$this->admin_lang = LangLoader::get('admin');
		$this->tpl = new StringTemplate('# INCLUDE FORM #');
		$this->tpl->add_lang($this->admin_lang);
	}

	private function build_form()
	{
		$picture_yes = '<i class="fa fa-success fa-2x" aria-hidden="true" title="' . LangLoader::get_message('yes', 'common') . '"></i><span class="sr-only">' . LangLoader::get_message('yes', 'common') . '</span>';
		$picture_no = '<i class="fa fa-error fa-2x" aria-hidden="true" title="' . LangLoader::get_message('no', 'common') . '"></i><span class="sr-only">' . LangLoader::get_message('no', 'common') . '</span>';
		$picture_unknown = '<i class="fa fa-question fa-2x" aria-hidden="true" title="' . LangLoader::get_message('unknown', 'main') . '"></i><span class="sr-only">' . LangLoader::get_message('unknown', 'main') . '</span>';

		$default_lang_config = LangsManager::get_lang(LangsManager::get_default_lang())->get_configuration();
		$default_theme_config = ThemesManager::get_theme(ThemesManager::get_default_theme())->get_configuration();
		$editors = AppContext::get_content_formatting_service()->get_available_editors();
		$default_editor = $editors[ContentFormattingConfig::load()->get_default_editor()];
		$server_configuration = new ServerConfiguration();
		$general_config = GeneralConfig::load();
		$server_environment_config = ServerEnvironmentConfig::load();
		$sessions_config = SessionsConfig::load();
		$maintenance_config = MaintenanceConfig::load();

		$url_rewriting_available = false;
		$url_rewriting_known = true;

		try
		{
			$url_rewriting_available = $server_configuration->has_url_rewriting();
		}
        catch(UnsupportedOperationException $ex)
		{
			$url_rewriting_known = false;
		}

		$summerization =
"---------------------------------System report---------------------------------
-----------------------------generated by PHPBoost-----------------------------
SERVER CONFIGURATION-----------------------------------------------------------
php version			: " . ServerConfiguration::get_phpversion() . "
dbms version			: " . PersistenceContext::get_dbms_utils()->get_dbms_version() . "
gd library			: " . (int)$server_configuration->has_gd_library() . "
curl extension			: " . (int)$server_configuration->has_curl_library() . "
mbstring extension		: " . (int)$server_configuration->has_mbstring_library() . "
url rewriting			: " . ($url_rewriting_known ? (int)$url_rewriting_available : 'N/A') . "
apcu cache			: " . (int)DataStoreFactory::is_apc_available() . "
PHPBOOST CONFIGURATION---------------------------------------------------------
phpboost version		: " . Environment::get_phpboost_version() . "
server url			: " . $general_config->get_site_url() . "
site path			: " . $general_config->get_site_path() . "
default theme			: " . $default_theme_config->get_name() . " (" . LangLoader::get_message('version', 'admin') . " " . $default_theme_config->get_version() . ")
default language		: " . $default_lang_config->get_name() . "
default editor			: " . $default_editor . "
home page			: " . Environment::get_home_page() . "
url rewriting			: " . (int)$server_environment_config->is_url_rewriting_enabled() . "
apcu cache			: " . (int)DataStoreFactory::is_apc_enabled() . "
output gzip			: " . (int)$server_environment_config->is_output_gziping_enabled() . "
session cookie name		: " . $sessions_config->get_cookie_name() . "
session duration		: " . $sessions_config->get_session_duration() . "
active session duration		: " . $sessions_config->get_active_session_duration() . "
DIRECTORIES AUTHORIZATIONS-----------------------------------------------------
";

		$form = new HTMLForm('system-report', '', false);

		$this->get_advises($form);

		$fieldset = new FormFieldsetHTMLHeading('server-report', $this->admin_lang['server']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFree('php_version', $this->admin_lang['php_version'], ServerConfiguration::get_phpversion()));
		$fieldset->add_field(new FormFieldFree('dbms_version', $this->admin_lang['dbms_version'], PersistenceContext::get_dbms_utils()->get_dbms_version()));
		$fieldset->add_field(new FormFieldFree('gd_library', $this->admin_lang['gd_library'], $server_configuration->has_gd_library() ? $picture_yes : $picture_no));
		$fieldset->add_field(new FormFieldFree('curl_library', $this->admin_lang['curl_library'], $server_configuration->has_curl_library() ? $picture_yes : $picture_no));
		$fieldset->add_field(new FormFieldFree('mbstring_library', $this->admin_lang['mbstring_library'], $server_configuration->has_mbstring_library() ? $picture_yes : $picture_no));
		$fieldset->add_field(new FormFieldFree('url_rewriting', $this->admin_lang['url_rewriting'], $url_rewriting_known ? ($url_rewriting_available ? $picture_yes : $picture_no) : $picture_unknown));
		$fieldset->add_field(new FormFieldFree('apcu_cache', LangLoader::get_message('apcu_cache', 'admin-cache-common'), DataStoreFactory::is_apc_available() ? $picture_yes : $picture_no));

		$fieldset = new FormFieldsetHTML('phpboost-config-report', $this->admin_lang['phpboost_config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFree('kernel_version', $this->admin_lang['kernel_version'], Environment::get_phpboost_version()));
		$fieldset->add_field(new FormFieldFree('site_url', LangLoader::get_message('advanced-config.site_url', 'admin-config-common'), $general_config->get_site_url()));
		$fieldset->add_field(new FormFieldFree('site_path', LangLoader::get_message('advanced-config.site_path', 'admin-config-common'), $general_config->get_site_path()));
		$fieldset->add_field(new FormFieldFree('default_theme', LangLoader::get_message('general-config.default_theme', 'admin-config-common'), $default_theme_config->get_name() . " (" . LangLoader::get_message('version', 'admin') . " " . $default_theme_config->get_version() . ")"));
		$fieldset->add_field(new FormFieldFree('default_language', LangLoader::get_message('general-config.default_language', 'admin-config-common'), $default_lang_config->get_name()));
		$fieldset->add_field(new FormFieldFree('default_editor', LangLoader::get_message('content.config.default-formatting-language', 'admin-contents-common'), $default_editor));
		$fieldset->add_field(new FormFieldFree('start_page', LangLoader::get_message('general-config.start_page', 'admin-config-common'), Environment::get_home_page()));
		$fieldset->add_field(new FormFieldFree('phpboost_url_rewriting', $this->admin_lang['url_rewriting'], $server_environment_config->is_url_rewriting_enabled() ? $picture_yes : $picture_no));
		$fieldset->add_field(new FormFieldFree('phpboost_apcu_cache', LangLoader::get_message('apcu_cache', 'admin-cache-common'), DataStoreFactory::is_apc_enabled() ? $picture_yes : $picture_no));
		$fieldset->add_field(new FormFieldFree('output_gz', $this->admin_lang['output_gz'], $server_environment_config->is_output_gziping_enabled() ? $picture_yes : $picture_no));
		$fieldset->add_field(new FormFieldFree('cookie_name', LangLoader::get_message('advanced-config.cookie-name', 'admin-config-common'), $sessions_config->get_cookie_name()));
		$fieldset->add_field(new FormFieldFree('session_length', LangLoader::get_message('advanced-config.cookie-duration', 'admin-config-common'), $sessions_config->get_session_duration()));
		$fieldset->add_field(new FormFieldFree('session_guest_length', LangLoader::get_message('advanced-config.active-session-duration', 'admin-config-common'), $sessions_config->get_active_session_duration()));

		$fieldset = new FormFieldsetHTML('directories_auth', $this->admin_lang['directories_auth']);
		$form->add_fieldset($fieldset);

		$directories_summerization = '';
		foreach(PHPBoostFoldersPermissions::get_permissions() as $key => $folder)
		{
			$fieldset->add_field(new FormFieldFree(str_replace('/', '_', $key), $key, $folder->is_writable() ? $picture_yes : $picture_no));
			$directories_summerization .= $key . str_repeat('	', 5 - (TextHelper::strlen($key) / 8)) . ": " . (int)($folder->is_writable()) . "
";
		}

		$fieldset = new FormFieldsetHTML('summerization', $this->admin_lang['system_report_summerization']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldLabel($this->admin_lang['system_report_summerization_explain'], array('class' => 'half-field')));

		$fieldset->add_element(new FormButtonButton($this->admin_lang['copy_report'], 'copy_code_clipboard(\'system-report_report-content\')', $this->admin_lang['copy_report'], 'copy-to-clipboard'));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('report-content', '', $summerization . $directories_summerization, array('rows' => 20, 'cols' => 15, 'class' => 'system-report')
		));

		$this->form = $form;
	}

	public static function get_advises(HTMLForm $html_form)
	{
		$lang = LangLoader::get('admin-server-common');

		$server_configuration = new ServerConfiguration();
		$maintenance_config = MaintenanceConfig::load();
		$general_config = GeneralConfig::load();
		$server_environment_config = ServerEnvironmentConfig::load();
		$security_config = SecurityConfig::load();

		$url_rewriting_available = false;
		try
		{
			$url_rewriting_available = $server_configuration->has_url_rewriting();
		}catch(UnsupportedOperationException $ex) {}

		$fieldset = new FormFieldsetHTML('advises', $lang['advises']);

		if (ModulesManager::is_module_installed('QuestionCaptcha') && ModulesManager::is_module_activated('QuestionCaptcha') && ContentManagementConfig::load()->get_used_captcha_module() == 'QuestionCaptcha' && QuestionCaptchaConfig::load()->count_questions() < 3)
			$fieldset->add_field(new FormFieldFree('QuestionCaptcha_questions_number', '', MessageHelper::display(LangLoader::get_message('advises.QuestionCaptcha_questions_number', 'common', 'QuestionCaptcha'), MessageHelper::WARNING)->render()));

		$fieldset->add_field(new FormFieldFree('modules_management', '', MessageHelper::display($lang['advises.modules_management'], MessageHelper::SUCCESS)->render()));

		if ($maintenance_config->is_under_maintenance())
			$fieldset->add_field(new FormFieldFree('check_modules_authorizations', '', MessageHelper::display($lang['advises.check_modules_authorizations'], MessageHelper::SUCCESS)->render()));

		if (!TextHelper::strstr($general_config->get_site_url(), 'localhost') && !TextHelper::strstr($general_config->get_site_url(), '127.0.0.1') && !$maintenance_config->is_under_maintenance() && Debug::is_debug_mode_enabled())
			$fieldset->add_field(new FormFieldFree('disable_debug_mode', '', MessageHelper::display($lang['advises.disable_debug_mode'], MessageHelper::WARNING)->render()));

		if ($maintenance_config->is_under_maintenance())
			$fieldset->add_field(new FormFieldFree('disable_maintenance', '', MessageHelper::display($lang['advises.disable_maintenance'], MessageHelper::NOTICE)->render()));

		if ($url_rewriting_available && !$server_environment_config->is_url_rewriting_enabled())
			$fieldset->add_field(new FormFieldFree('enable_url_rewriting', '', MessageHelper::display($lang['advises.enable_url_rewriting'], MessageHelper::NOTICE)->render()));

		if (function_exists('ob_gzhandler') && @extension_loaded('zlib') && !$server_environment_config->is_output_gziping_enabled())
			$fieldset->add_field(new FormFieldFree('enable_output_gz', '', MessageHelper::display($lang['advises.enable_output_gz'], MessageHelper::NOTICE)->render()));

		if (DataStoreFactory::is_apc_available() && !DataStoreFactory::is_apc_enabled())
			$fieldset->add_field(new FormFieldFree('enable_apcu_cache', '', MessageHelper::display($lang['advises.enable_apcu_cache'], MessageHelper::NOTICE)->render()));
		$fieldset->add_field(new FormFieldFree('save_database', '', MessageHelper::display($lang['advises.save_database'], MessageHelper::SUCCESS)->render()));

		if (!DatabaseConfig::load()->is_database_tables_optimization_enabled())
			$fieldset->add_field(new FormFieldFree('optimize_database_tables', '', MessageHelper::display($lang['advises.optimize_database_tables'], MessageHelper::SUCCESS)->render()));

		if ($security_config->get_internal_password_min_length() == 6 && $security_config->get_internal_password_strength() == SecurityConfig::PASSWORD_STRENGTH_WEAK && !$security_config->are_login_and_email_forbidden_in_password())
			$fieldset->add_field(new FormFieldFree('password_security', '', MessageHelper::display($lang['advises.password_security'], MessageHelper::NOTICE)->render()));

		if (ServerConfiguration::get_phpversion() < '5.6')
			$fieldset->add_field(new FormFieldFree('upgrade_php_version', '', MessageHelper::display($lang['advises.upgrade_php_version'], MessageHelper::NOTICE)->render()));

		if (count($fieldset->get_fields()))
			$html_form->add_fieldset($fieldset);
	}
}
?>
