<?php
/*##################################################
 *                       WebsiteConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 29, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class WebsiteConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('config');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$general_config = GeneralConfig::load();
		$general_config->set_site_name($config['site_name']);
		$general_config->set_site_description($config['site_desc']);
		$general_config->set_site_keywords($config['site_keyword']);
		$general_config->set_other_home_page($config['start_page']);
		//$general_config->set_site_url($config['server_name']);
		//$general_config->set_site_path($config['server_path']);
		$general_config->set_site_timezone($config['timezone']);
		GeneralConfig::save();
		
		$server_environment_config = ServerEnvironmentConfig::load();
		$server_environment_config->set_htaccess_manual_content($config['htaccess_manual_content']);
		//$server_environment_config->set_url_rewriting_enabled($config['rewrite']);
		$server_environment_config->set_output_gziping_enabled($config['ob_gzhandler']);
		ServerEnvironmentConfig::save();
		
		$sessions_config = SessionsConfig::load();
		$sessions_config->set_cookie_name($config['site_cookie']);
		$sessions_config->set_session_duration($config['site_session']);
		$sessions_config->set_active_session_duration($config['site_session_invit']);
		SessionsConfig::save();

		$graphical_environment_config = GraphicalEnvironmentConfig::load();
		$graphical_environment_config->set_visit_counter_enabled($config['compteur']);
		$graphical_environment_config->set_page_bench_enabled($config['bench']);
		$graphical_environment_config->set_display_theme_author($config['theme_author']);
		GraphicalEnvironmentConfig::save();

		$user_accounts_config = UserAccountsConfig::load();
		$user_accounts_config->set_default_lang($config['lang']);
		$user_accounts_config->set_default_theme($config['theme']);
		$user_accounts_config->set_max_private_messages_number($config['pm_max']);
		UserAccountsConfig::save();
		
		$mail_config = MailServiceConfig::load();
		$mail_config->set_default_mail_sender($config['mail']);
		$mail_config->set_administrators_mails(explode(',', $config['mail_exp']));
		$mail_config->set_mail_signature($config['sign']);
		MailServiceConfig::save();
		
		$content_formatting_config = ContentFormattingConfig::load();
		$content_formatting_config->set_default_editor($config['editor']);
		$content_formatting_config->set_html_tag_auth($config['html_auth']);
		$content_formatting_config->set_forbidden_tags(isset($config['forbidden_tags']) ? $config['forbidden_tags'] : array());
		ContentFormattingConfig::save();
		        
		$content_management_config = ContentManagementConfig::load();
		$content_management_config->set_anti_flood_enabled($config['anti_flood']);
		$content_management_config->set_anti_flood_duration($config['delay_flood']);
		ContentManagementConfig::save();
		
		$maintenance_config = MaintenanceConfig::load();
		$maintenance_config->disable_maintenance();
		$maintenance_config->set_message($config['maintain_text']);
		MaintenanceConfig::save();
		
		$search_config = SearchConfig::load();
		$search_config->set_cache_lifetime($config['search_cache_time']);
		$search_config->set_cache_max_uses($config['search_max_use']);
		SearchConfig::save();
		
		/*if ($config['debug_mode'])
		{
			Debug::enabled_debug_mode(array());
		}
		else
		{
			Debug::disable_debug_mode();
		}
		*/
        
		return true;
	}
}