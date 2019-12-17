<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

####################################################
#                     English                      #
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
	'advanced-config.site_url-explain' => 'Ex : https://www.phpboost.com',
	'advanced-config.site_path' => 'PHPBoost path',
	'advanced-config.site_path-explain' => 'Empty by default : website at server\'s root',
	'advanced-config.site_timezone' => 'Timezone',
	'advanced-config.site_timezone-explain' => 'Choose the time that refers to your location',

	'advanced-config.redirection_www_enabled' => 'Domain redirection activation',
	'advanced-config.redirection_www_enabled.local' => 'Redirection can not be activated in local host',
	'advanced-config.redirection_www_enabled.subdomain' => 'Redirection can not be activated with sub-domain',
	'advanced-config.redirection_www_mode' => 'Redirection mode',
	'advanced-config.redirection_www.with_www' => 'Redirect urls ' . AppContext::get_request()->get_domain_name() . ' to www.'. AppContext::get_request()->get_domain_name(),
	'advanced-config.redirection_www.without_www' => 'Redirect urls www.' . AppContext::get_request()->get_domain_name() . ' to '. AppContext::get_request()->get_domain_name(),
	'advanced-config.redirection_https_enabled' => 'Enable HTTPS redirection',
	'advanced-config.redirection_https_enabled.explain' => 'Redirect urls HTTP to HTTPS',
	'advanced-config.redirection_https_enabled.explain-disable' => 'Your website doesn\'t use HTTPS protocol, mod activation is unavailable',
	'advanced-config.hsts_security_enabled' => 'Enabled HSTS security',
	'advanced-config.hsts_security.explain' => 'HTTP Strict Transport Security (HSTS) is a web security policy mechanism which allows web servers to declare that web browsers should only interact with it using secure HTTPS connections, and never via the insecure HTTP protocol.<br /><span class="text-strong error">It is strongly recommended that you do not enable this option if your switching to HTTPS is temporary.</span>',
	'advanced-config.hsts_security_duration' => 'HSTS Security renewal time',
	'advanced-config.hsts_security_duration.explain' =>  'In days. 30 days recommended.',
	'advanced-config.hsts_security_subdomain' => 'Include subdomain',
	'advanced-config.hsts_security_subdomain.explain' => 'Set HSTS on subdomain',
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

	//Cookie Bar
	'advanced-config.cookiebar-config' => 'Warning Cookies configuration',
	'advanced-config.cookiebar-more' => 'The law requires that site managers and application providers, inform users and get their consent before activate cookies or other tracers.',
	'advanced-config.cookiebar-activation' => 'Activation of the warning cookies',
	'advanced-config.cookiebar-activation.desc' => 'We recommend that you keep the warning activated to be sure to respect the law',
	'advanced-config.cookiebar-duration' => 'Cookie duration',
	'advanced-config.cookiebar-duration.desc' => 'Cookie lifetime can\'t by more than 13 months',
	'advanced-config.cookiebar-tracking.choice' => 'Choose your mode',
	'advanced-config.cookiebar-tracking.track' => 'Using tracking cookies',
	'advanced-config.cookiebar-tracking.notrack' => 'Using only technicals cookies',
	'advanced-config.cookiebar-content' => 'Message displayed in the warning bar',
	'advanced-config.cookiebar-content.explain' => 'You can customize the message, but you must specify if you are using tracking cookies',
	'advanced-config.cookiebar-aboutcookie-title' => 'Title of the page "<a href="' . UserUrlBuilder::aboutcookie()->rel() . '">Learn more</a>"',
	'advanced-config.cookiebar-aboutcookie' => 'Message displayed in the page "Learn more"',
	'advanced-config.cookiebar-aboutcookie.explain' => 'You can customize the message, but you must describe tracking cookies you use',
);
?>
