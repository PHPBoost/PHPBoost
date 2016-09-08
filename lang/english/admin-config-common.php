<?php
/*##################################################
 *                          admin-config-common.php
 *                            -------------------
 *   begin                : April 12, 2010
 *   copyright            : (C) 2010 Benoit Sautel
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

 ####################################################
#                     English                       #
 ####################################################

$lang = array(
	'configuration' => 'Configuration',
	//Mail config
	'mail-config' => 'Mail configuration',
	'mail-config.general_mail_config' => 'General mail configuration',
	'mail-config.default_mail_sender' => 'Default mail sender',
	'mail-config.default_mail_sender_explain' => 'Mail address which will be used when PHPBoost sends a mail without specifying the sender',
	'mail-config.administrators_mails' => 'Administrators mails',
	'mail-config.administrators_mails_explain' => 'List of the administrators mail addresses (comma-separated) to which will be sent all the administrators mails.',
	'mail-config.mail_signature' => 'Mail signature',
	'mail-config.mail_signature_explain' => 'This signature will be appended to each mail sent by PHPBoost.',
	'mail-config.send_protocol' => 'Sending protocol',
	'mail-config.send_protocol_explain' => 'Generally, website hosting providers configure mail server adequately. However, some users need to change the way
		it sends mails by using a custom SMTP configuration. To choose a custom configuration, check the box below and fill the configuration fields. 
		This configuration will be used each time PHPBoost sends a mail.',
	'mail-config.use_custom_smtp_configuration' => 'Use custom SMTP configuration',
	'mail-config.custom_smtp_configuration' => 'Custom SMTP Configuration',
	'mail-config.smtp_host' => 'Host',
	'mail-config.smtp_port' => 'Port',
	'mail-config.smtp_login' => 'Login',
	'mail-config.smtp_password' => 'Password',
	'mail-config.smtp_secure_protocol' => 'Secured protocol',
	'mail-config.smtp_secure_protocol_none' => 'None',
	'mail-config.smtp_secure_protocol_tls' => 'TLS',
	'mail-config.smtp_secure_protocol_ssl' => 'SSL',
	
	//General config
	'general-config' => 'General configuration',
	'general-config.site_name' => 'Site name',
	'general-config.site_slogan' => 'Site slogan',
	'general-config.site_description' => 'Site description',
	'general-config.site_description-explain' => 'Description of your site in search engines',
	'general-config.default_language' => 'Website\'s default language',
	'general-config.default_theme' => 'Website\'s default theme',
	'general-config.theme_picture' => 'Theme insight',
	'general-config.theme_preview_click' => 'Click to preview',
	'general-config.view_real_preview' => 'View real size',
	'general-config.start_page' => 'Website\'s start page',
	'general-config.other_start_page' => 'Other start page (relative or absolute URL)',
	'general-config.visit_counter' => 'Visit counter',
	'general-config.page_bench' => 'Benchmark',
	'general-config.page_bench-explain' => 'Displays page\'s render time and SQL requests',
	'general-config.display_theme_author' => 'Website\'s theme info',
	'general-config.display_theme_author-explain' => 'Displays theme info in footer',
	
	//Advanced config
	'advanced-config' => 'Advanced configuration',
	'advanced-config.site_url' => 'Server\'s URL',
	'advanced-config.site_url-explain' => 'Ex : http://www.phpboost.com',
	'advanced-config.site_path' => 'PHPBoost path',
	'advanced-config.site_path-explain' => 'Empty by default : website at server\'s root',
	'advanced-config.site_timezone' => 'Timezone',
	'advanced-config.site_timezone-explain' => 'Choose the time that refers to your location',
	
	'advanced-config.url-rewriting' => 'Enable URL rewriting',
	'advanced-config.url-rewriting.explain' => 'Activation of URL rewriting makes URLs much simpler and clearer on your website. 
		Your referencing will be largely optimized with this option.<br /><br />
		Unfortunately this option isn\'t available on all servers. This page tests if your server supports URL rewriting. 
		If after the test you get errors or white pages, remove the file <strong>.htaccess</strong> from the root directory of your server.',
	
	'advanced-config.config.not-available' => 'Not available on your server',
	'advanced-config.config.available' => 'Available on your server',
	'advanced-config.config.unknown' => 'Unknown sur votre serveur',

	'advanced-config.htaccess-manual-content' => 'Content of the .htaccess file',
	'advanced-config.htaccess-manual-content.explain' => 'In this field you can put the instructions you would like to integrate into the .htaccess file 
		which is at the root of the website. For instance, you can force special settings on your Apache web server.',

	'advanced-config.robots-content' => 'Content of the robots.txt file',
	'advanced-config.robots-content.explain' => 'In this field you can put the instructions you would like to integrate into the robots.txt file 
		which is at the root of the website. For instance, you can prevent robots from index some parts of the website.',

	'advanced-config.sessions-config' => 'Users connection',
	'advanced-config.cookie-name' => 'Sessions cookie name',
	'advanced-config.cookie-name.style-wrong' => 'Cookie name must be alphanumeric (letters and numbers)',
	'advanced-config.cookie-duration' => 'Sessions duration',
	'advanced-config.cookie-duration.explain' => '3600 seconds recommended',
	'advanced-config.active-session-duration' => 'Active users duration',
	'advanced-config.active-session-duration.explain' => '300 seconds recommended',
	'advanced-config.integer-required' => 'Value must be numeric',
	
	'advanced-config.miscellaneous' => 'Miscellaneous',
	'advanced-config.output-gziping-enabled' => 'Enable page compression for faster display speed',
	'advanced-config.debug-mode' => 'Debug mode',
	'advanced-config.debug-mode.explain' => 'This mode is very useful for developers who will more easily see the errors encountered during the page 
		execution. You shouldn\'t use this mode on a published web site.',
	'advanced-config.debug-mode.type' => 'Debug mode type',
	'advanced-config.debug-mode.type.normal' => 'Normal',
	'advanced-config.debug-mode.type.strict' => 'Strict',
	'advanced-config.debug-display-database-query-enabled' => 'Enable display and monitoring SQL queries',
);
?>
