<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 26
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['configuration.title']         = 'Configuration';
$lang['configuration.not.available'] = 'Not available on your server';
$lang['configuration.available']     = 'Available on your server';
$lang['configuration.unknown']       = 'Unknown sur votre serveur';

// General config
$lang['configuration.general']                   = 'General configuration';
$lang['configuration.site.name']                 = 'Site name';
$lang['configuration.site.slogan']               = 'Site slogan';
$lang['configuration.site.description']          = 'Site description';
$lang['configuration.site.description.clue']     = 'Description of your site in search engines';
$lang['configuration.default.language']          = 'Website\'s default language';
$lang['configuration.default.theme']             = 'Website\'s default theme';
$lang['configuration.theme.picture']             = 'Theme insight';
$lang['configuration.theme.preview']             = 'Click to preview';
$lang['configuration.no.module.startable']       = 'No configurable module on the start page ';
$lang['configuration.start.page']                = 'Website\'s start page';
$lang['configuration.other.start.page']          = 'Other start page (relative or absolute URL)';
$lang['configuration.visit.counter']             = 'Visit counter';
$lang['configuration.page.bench']                = 'Benchmark';
$lang['configuration.page.bench.clue']           = 'Displays page\'s render time and SQL requests';
$lang['configuration.display.theme.author']      = 'Website\'s theme info';
$lang['configuration.display.theme.author.clue'] = 'Displays theme info in footer';

// Advanced config
$lang['configuration.advanced']           = 'Advanced configuration';
$lang['configuration.site.url']           = 'Server\'s URL';
$lang['configuration.site.url.clue']      = 'Ex : https://www.phpboost.com';
$lang['configuration.site.path']          = 'PHPBoost path';
$lang['configuration.site.path.clue']     = 'Empty by default : website at server\'s root';
$lang['configuration.site.timezone']      = 'Timezone';
$lang['configuration.site.timezone.clue'] = 'Choose the time that refers to your location';
	// Redirections
$lang['configuration.enable.redirection']         = 'Domain redirection activation';
$lang['configuration.redirection.local']          = 'Redirection can not be activated in local host';
$lang['configuration.redirection.subdomain']      = 'Redirection can not be activated with sub-domain';
$lang['configuration.redirection.mode']           = 'Redirection mode';
$lang['configuration.redirection.with.www']       = 'Redirect urls ' . AppContext::get_request()->get_domain_name() . ' to www.'. AppContext::get_request()->get_domain_name();
$lang['configuration.redirection.without.www']    = 'Redirect urls www.' . AppContext::get_request()->get_domain_name() . ' to '. AppContext::get_request()->get_domain_name();
$lang['configuration.enable.redirection.https']   = 'Enable HTTPS redirection';
$lang['configuration.redirection.https.clue']     = 'Redirect urls HTTP to HTTPS';
$lang['configuration.redirection.https.disabled'] = 'Your website doesn\'t use HTTPS protocol, mod activation is unavailable';
$lang['configuration.enable.hsts']                = 'Enabled HSTS security';
$lang['configuration.hsts.clue']                  = 'HTTP Strict Transport Security (HSTS) is a web security policy mechanism which allows web servers to declare that web browsers should only interact with it using secure HTTPS connections, and never via the insecure HTTP protocol.<br /><span class="text-strong error">It is strongly recommended that you do not enable this option if your switching to HTTPS is temporary.</span>';
$lang['configuration.hsts.duration']              = 'HSTS Security renewal time';
$lang['configuration.hsts.duration.clue']         = 'In days. 30 days recommended.';
$lang['configuration.hsts.subdomain']             = 'Include subdomain';
$lang['configuration.hsts.subdomain.clue']        = 'Set HSTS on subdomain';
	// Rewriting
$lang['configuration.url.rewriting']      = 'Enable URL rewriting';
$lang['configuration.url.rewriting.clue'] = '
	Activation of URL rewriting makes URLs much simpler and clearer on your website.
	Your referencing will be largely optimized with this option.<br /><br />
	Unfortunately this option isn\'t available on all servers. This page tests if your server supports URL rewriting.
	If after the test you get errors or white pages, remove the file <strong>.htaccess</strong> from the root directory of your server.
';
	// Server access
$lang['configuration.htaccess.manual.content']      = 'Content of the .htaccess file';
$lang['configuration.htaccess.manual.content.clue'] = 'In this field you can put the instructions you would like to integrate into the .htaccess file which is at the root of the website. For instance, you can force special settings on your Apache web server.';
$lang['configuration.nginx.manual.content']         = 'Content of the nginx.conf file';
$lang['configuration.nginx.manual.content.clue']    = 'In this field you can put the instructions you would like to integrate into the nginx.conf file which is at the root of the website. For instance, you can force special settings on your web server.';
$lang['configuration.robots.content']               = 'Content of the robots.txt file';
$lang['configuration.robots.content.clue']          = 'In this field you can put the instructions you would like to integrate into the robots.txt file which is at the root of the website. For instance, you can prevent robots from index some parts of the website.';
	// Session
$lang['configuration.sessions']                     = 'Users connection';
$lang['configuration.cookie.name']                  = 'Sessions cookie name';
$lang['configuration.cookie.duration']              = 'Sessions duration';
$lang['configuration.cookie.duration.clue']         = '3600 seconds recommended';
$lang['configuration.active.session.duration']      = 'Active users duration';
$lang['configuration.active.session.duration.clue'] = '300 seconds recommended';
	// Cookie Bar
$lang['configuration.cookiebar']                   = 'Warning Cookies configuration';
$lang['configuration.cookiebar.more']              = 'The law requires that site managers and application providers, inform users and get their consent before activate cookies or other tracers.';
$lang['configuration.enable.cookiebar']            = 'Activation of the warning cookies';
$lang['configuration.cookiebar.clue']              = 'We recommend that you keep the warning activated to be sure to respect the law';
$lang['configuration.cookiebar.duration']          = 'Cookie duration';
$lang['configuration.cookiebar.duration.clue']     = 'Cookie lifetime can\'t by more than 13 months';
$lang['configuration.cookiebar.tracking.mode']     = 'Choose your mode';
$lang['configuration.cookiebar.trackers']          = 'Using tracking cookies';
$lang['configuration.cookiebar.no.tracker']        = 'Using only technicals cookies';
$lang['configuration.cookiebar.content']           = 'Message displayed in the warning bar';
$lang['configuration.cookiebar.content.clue']      = 'You can customize the message, but you must specify if you are using tracking cookies';
$lang['configuration.cookiebar.aboutcookie.title'] = 'Title of the page "<a class="offload" href="' . UserUrlBuilder::aboutcookie()->rel() . '">Learn more</a>"';
$lang['configuration.cookiebar.aboutcookie']       = 'Message displayed in the page "Learn more"';
$lang['configuration.cookiebar.aboutcookie.clue']  = 'You can customize the message, but you must describe tracking cookies you use';
	// Miscellaneous
$lang['configuration.miscellaneous']           = 'Miscellaneous';
$lang['configuration.enable.page.compression'] = 'Enable page compression for faster display speed';
$lang['configuration.debug.mode']              = 'Debug mode';
$lang['configuration.debug.mode.clue']         = '
	This mode is very useful for developers who will more easily see the errors encountered during the page,
	execution. You shouldn\'t use this mode on a published web site.
';
$lang['configuration.debug.mode.type']               = 'Debug mode type';
$lang['configuration.debug.mode.type.normal']        = 'Normal';
$lang['configuration.debug.mode.type.strict']        = 'Strict';
$lang['configuration.enable.database.query.display'] = 'Enable display and monitoring SQL queries';

// Emails
$lang['configuration.email']                             = 'Mail configuration';
$lang['configuration.email.sending']                     = 'Email sending configuration';
$lang['configuration.email.default.sender']              = 'Default mail sender';
$lang['configuration.email.default.sender.clue']         = 'Mail address which will be used when PHPBoost sends a mail without specifying the sender';
$lang['configuration.email.administrators.address']      = 'Administrators mails';
$lang['configuration.email.administrators.address.clue'] = 'List of the administrators mail addresses (comma-separated) to which will be sent all the administrators mails.';
$lang['configuration.email.signature']                   = 'Mail signature';
$lang['configuration.email.signature.clue']              = 'This signature will be appended to each mail sent by PHPBoost.';
$lang['configuration.email.send.protocol']               = 'Sending protocol';
$lang['configuration.email.send.protocol.clue']          = '
	Generally, website hosting providers configure mail server adequately. However, some users need to change the way
	it sends mails by using a custom SMTP configuration. To choose a custom configuration, check the box below and fill the configuration fields.
	This configuration will be used each time PHPBoost sends a mail.
';
$lang['configuration.email.use.custom.smtp.configuration'] = 'Use custom SMTP configuration';
$lang['configuration.email.custom.smtp.configuration']     = 'Custom SMTP Configuration';
$lang['configuration.email.smtp.host']                     = 'Host';
$lang['configuration.email.smtp.port']                     = 'Port';
$lang['configuration.email.smtp.login']                    = 'Login';
$lang['configuration.email.smtp.password']                 = 'Password';
$lang['configuration.email.smtp.secure.protocol']          = 'Secured protocol';
$lang['configuration.email.smtp.secure.protocol.none']     = 'None';
$lang['configuration.email.smtp.secure.protocol.tls']      = 'TLS';
$lang['configuration.email.smtp.secure.protocol.ssl']      = 'SSL';

?>
