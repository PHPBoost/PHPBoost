<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : September 28, 2008
 *   last modified		  : October 3rd, 2009 - JMNaylor
 *   copyright            : (C) 2008 	Sautel Benoit
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
#                      English                     #
 ####################################################

$LANG = array();

// Errors
$LANG['cache_tpl_must_exist_and_be_writable'] = '<h1>PHPBoost installation</h1>
<p><strong>Warning</strong> : the folders cache and cache/tpl must exist and be writable. Please create and/or set them the right CHMOD (777) to be able to continue the installation.</p>
<p>Once it is done, please refresh the page to continue or click <a href="">here</a>.</p>';

// General variables
$LANG['page_title'] = 'PHPBoost installation';
$LANG['steps_list'] = 'Steps list';
$LANG['introduction'] = 'Preamble';
$LANG['config_server'] = 'Server settings';
$LANG['database_config'] = 'Database settings';
$LANG['advanced_config'] = 'General settings';
$LANG['administrator_account_creation'] = 'Administrator details';
$LANG['end'] = 'End of installation';
$LANG['install_progress'] = 'Installation progression';
$LANG['generated_by'] = 'Powered by %s';
$LANG['previous_step'] = 'Go back to previous step';
$LANG['next_step'] = 'Proceed to next step';
$LANG['query_loading'] = 'Sending query to the server';
$LANG['query_sent'] = 'Query sent, waiting for response';
$LANG['query_processing'] = 'Query processing';
$LANG['query_success'] = 'The process has been done successfully';
$LANG['query_failure'] = 'An error occured during query processing';

// Introduction
$LANG['intro_title'] = 'Welcome to PHPBoost installation wizard';
$LANG['intro_explain'] = '<p>Thank you for choosing PHPBoost to build your website.</p>
<p>Before starting installation, be sure to have to hand Database information provided by your hosting company. The installation is automatic and should take only a few minutes of your time.  To start the installation process, please click on the green arrow down below.</p>
<p>Best regards, the PHPBoost team</p>';
$LANG['intro_distribution'] = '%s distribution';
$LANG['intro_distribution_intro'] = '<p>There are several distributions which allow you to setup easily their website according to your needs. PHPBoost will install the kernel and modules according to the chosen distribution. After the installation is complete, you will be able to change settings and add/remove modules. </p>';
$LANG['start_install'] = 'Start installation';

// License
$LANG['license'] = 'License';
$LANG['require_license_agreement'] = 'You must accept the GNU/GPL license terms to install PHPBoost.';
$LANG['license_agreement'] = 'End-user license agreement';
$LANG['license_terms'] = 'License terms';
$LANG['please_agree_license'] = 'I have read and understand the End-user License Terms which applies to this software';
$LANG['alert_agree_license'] = 'You have to agree to the end-user license by checking the form !';

// Server setup
$LANG['config_server_title'] = 'Looking up server configuration...';
$LANG['config_server_explain'] = '<p>Before starting installation, we need to establish the compatibility between PHPBoost and your server. If you have any problems, feel free to ask your questions in our <a href="http://www.phpboost.org/forum/index.php">Support Forums</a>.</p>
<div class="notice">It is very important that each required fields are checked, otherwise you might have trouble using the software.</div>';
$LANG['php_version'] = 'PHP version';
$LANG['check_php_version'] = 'PHP higher than 5.0';
$LANG['check_php_version_explain'] = '<span style="font-weight:bold;color:red;">Required :</span> To run PHPBoost, your server must run PHP 4.1.x or higher. Below that, you might have issues with some modules. Since PHP 4 is no longer supported, we encourage you to ask your hosting company to migrate PHP to version 5.';
$LANG['extensions'] = 'Extensions';
$LANG['check_extensions'] = '<span style="font-weight:bold;">Optional :</span> The activation of these extensions will provide additional features but it is not essential to its operation.';
$LANG['gd_library'] = 'GD library';
$LANG['gd_library_explain'] = 'Library used to generate pictures such as Robot Protection, Statistics Graphics and much more.';
$LANG['url_rewriting'] = 'URL Rewriting';
$LANG['url_rewriting_explain'] = 'Not only does it rewrite URLs, but it helps a lot with search engine robots.';
$LANG['auth_dir'] = 'Directories permissions';
$LANG['check_auth_dir'] = '<span style="font-weight:bold;color:red;">Required :</span> PHPBoost needs to change permissions of several directories to make them writable. If your hosting company allows it, it will be done automatically. However, you might need to do it by yourself to make the installation work. If you don\'t know how to change permissions of a directory, you can find help in our <a href="http://www.phpboost.net/wiki/change-the-chmod-of-a-directory" title="PHPBoost documentation : chmod">PHPBoost documentation</a> or on your host website.';

$LANG['refresh_chmod'] = 'Double-check directories permissions';
$LANG['existing'] = 'Found';
$LANG['unexisting'] = 'Not found';
$LANG['writable'] = 'Writable';
$LANG['unwritable'] = 'Not writable';
$LANG['unknown'] = 'Unknown';
$LANG['config_server_dirs_not_ok'] = 'Some directories seem to be missing and/or not writable. You need to create the missing directories or make the directories writable.';

// Database
$LANG['db_title'] = 'Database connection parameters';
$LANG['db_explain'] = '<p>This step will generate a configuration file that contains the database settings and database tables will also be created at the same time. If you don\'t know your database settings, you might be able to find them in your host panel or need to ask for them from your host support.</p>';
$LANG['dbms_paramters'] = 'DBMS connection parameters';
$LANG['db_host_name'] = 'Database server hostname or DSN:';
$LANG['db_host_name_explain'] = 'Database managing system server URL, often <em>localhost</em>';
$LANG['db_login'] = 'Database username';
$LANG['db_login_explain'] = 'Provided by your host';
$LANG['db_password'] = 'Database password';
$LANG['db_password_explain'] = 'Provided by your host';
$LANG['db_properties'] = 'Database properties';
$LANG['db_name'] = 'Database name';
$LANG['db_name_explain'] = 'Provided by your host. If that database doesn\'t exist, PHPBoost will try to create it.';
$LANG['db_prefix'] = 'Prefix for tables in database';
$LANG['db_prefix_explain'] = 'Default value is <em>phpboost_</em>. You will need to change this value if you want to install PHPBoost several times in the same database.';
$LANG['test_db_config'] = 'Try to establish database connection!';
$LANG['result'] = 'Results';
$LANG['empty_field'] = '%s field is empty';
$LANG['field_dbms'] = 'Datatabase managing system';
$LANG['field_host'] = 'Host';
$LANG['field_login'] = 'Login';
$LANG['field_password'] = 'Password';
$LANG['field_database'] = 'Database name';
$LANG['db_error_connexion'] = 'Could not connect to the database. Please check the settings you have entered.';
$LANG['db_error_selection_not_creable'] = 'The database name you entered doesn\'t exist and we can\'t create it for you. You need to manually create the database.';
$LANG['db_error_selection_but_created'] = 'The database name you entered doesn\'t exist and we created it for you. You can proceed to the next step!';
$LANG['db_success'] = 'The connection to the database has been established successfully. You can proceed to the next step.';
$LANG['db_error_tables_already_exist'] = 'A PHPBoost installation has been found on this database with the prefix you entered. <span style="font-weight:bold;">If you proceed, all the data in this database will be lost.</span>';
$LANG['db_unknown_error'] = 'An unknown error has occured.';
$LANG['require_hostname'] = 'You must enter database hostname!';
$LANG['require_login'] = 'You must enter database username!';
$LANG['require_db_name'] = 'You must enter database name!';
$LANG['db_result'] = 'Test results';
$LANG['already_installed'] = 'Existing installation';
$LANG['already_installed_explain'] = '<p>The database name you entered contains a PHPBoost installation with the same table prefix.</p>
<p>If you proceed with the installation, all the data in your database will be lost. If you want to install another PHPBoost, you absolutely need to use another table prefix.</p>';
$LANG['already_installed_overwrite'] = 'Yes, I want to overwrite the existing data and proceed with the installation.';

// Website settings
$LANG['site_config_title'] = 'Website settings';
$LANG['site_config_explain'] = '<p>At this stage, some basic settings will be created that will allow PHPBoost to work. Anything you enter in this form may be modified after installation in the administration panel. Also after the installation, you will have to set advanced configurations in the admin panel.</p>';
$LANG['your_site'] = 'Your website';
$LANG['site_url'] = 'Website url :';
$LANG['site_url_explain'] = 'e.g. http://www.phpboost.net';
$LANG['site_path'] = 'PHPBoost path :';
$LANG['site_path_explain'] = 'The path where PHPBoost is located relative to the domain name, e.g. /PHPBoost.';
$LANG['site_name'] = 'Website name';
$LANG['site_timezone'] = 'System timezone';
$LANG['site_timezone_explain'] = 'The default value is the server one. You will be able to change this value later in the administration panel.';
$LANG['site_description'] = 'Website description';
$LANG['site_description_explain'] = '<span style="font-weight:bold;">Optional:</span> Useful for search engine optimization';
$LANG['site_keywords'] = 'Website keywords';
$LANG['site_keywords_explain'] = '<span style="font-weight:bold;">Optional:</span> Enter keywords separated by commas.';
$LANG['require_site_url'] = 'You must enter your website\'s url !';
$LANG['require_site_name'] = 'You must enter your website\'s name !';
$LANG['confirm_site_url'] = 'The website address you entered doesn\'t match your server address. Are you sure you want to keep to proceed ?';
$LANG['confirm_site_path'] = 'The website path you entered doesn\'t correspond to path powered by the server, are you sure you want to keep the path you entered ?';
$LANG['site_config_maintain_text'] = 'The site is currently under maintenance.';
$LANG['site_config_mail_signature'] = 'Best regards, the site team.';
$LANG['site_config_msg_mbr'] = 'Welcome on the website. You are member of the site and you can access all parts of the website requiring a member account.';
$LANG['site_config_msg_register'] = 'You are just going to register yourself on the site. We ask you yo be polite and respectful.<br />
<br />
Thanks, the site team.';

// Administration
$LANG['admin_account_creation'] = 'Administrator account creation';
$LANG['admin_account_creation_explain'] = '<p>This account gives you access to administration panel in which you can setup your website.</p>
<p>You will be able to grant administrator rights to other members later. Here you just create the first administrator account, without which you can\'t manage your website.</p>';
$LANG['admin_account'] = 'Administrator account';
$LANG['admin_pseudo'] = 'Administrator username';
$LANG['admin_pseudo_explain'] = 'Enter a username between 3 and 25 characters in length';
$LANG['admin_password'] = 'Administrator password';
$LANG['admin_password_explain'] = 'Enter a password between 6 and 30 characters in length';
$LANG['admin_password_repeat'] = 'Confirm administrator password';
$LANG['admin_mail'] = 'Contact e-mail address';
$LANG['admin_mail_explain'] = 'Must be valid to receive unlock administration code.';
$LANG['admin_require_login'] = 'You must enter a username !';
$LANG['admin_login_too_short'] = 'Your username is too short (at least 3 characters)';
$LANG['admin_password_too_short'] = 'Your password is too short (at least 6 characters)';
$LANG['admin_require_password'] = ' You must enter a password !';
$LANG['admin_require_password_repeat'] = 'You must confirm your password !';
$LANG['admin_require_mail'] = 'You must enter an e-mail address !';
$LANG['admin_passwords_error'] = 'The passwords you entered did not match.';
$LANG['admin_email_error'] = 'The email address you entered is invalid.';
$LANG['admin_invalid_email_error'] = 'The email address you entered is invalid.';
$LANG['admin_create_session'] = 'Log me on at the end of the installation';
$LANG['admin_auto_connection'] = 'Log me on automatically each visit';
$LANG['admin_error'] = 'Error';
$LANG['admin_mail_object'] = 'PHPBoost : message to be preserved';
$LANG['admin_mail_unlock_code'] = 'Dear %s,

First of all, thank you for powering your website with PHPBoost software, we hope you will be satisfied. If you have any problems ask your questions on the PHPBoost support forum : http://www.phpboost.org/forum/index.php

Here is your login and password (don\'t lose them, you will need them to setup your website) : 

Login: %s 
Password: %s

Please note the following code (It won\'t be delivered anymore unless you ask to in the administration panel) : %s

If your website undergoes a hacking attempt, you will have to use this security code to unlock the administration panel. You will be asked to enter the security code when you log in to the administration panel.  (%s/admin/admin_index.php) 

Best regards,
The PHPBoost Team.';

// End of installation
$LANG['end_installation'] = '<fieldset>
							<legend>PHPBoost is now installed and ready to run !</legend>
							<p class="success">The installation of PHPBoost has been powered successfully. The PHPBoost Team thanks you for using its software and is proud to count you among its users.</p>
							<p>Keep yourself informed about the evolution of PHPBoost by visiting our website, <a href="http://www.phpboost.org">www.phpboost.org</a>. You will be warned in the administration panel when updates are available. We strongly recommend to keep your website up to date so you can take advantage of the latest features and correct any flaws or errors.</p>
							<p class="warning">For security reasons we also recommand you to delete the installation folder and all its contents, hackers could manage to run the installation script and you could lose data !</p>
							<p>Don\'t forget the <a href="http://www.phpboost.org/wiki/">documentation</a> which will help you for using PHPBoost and the <a href="http://www.phpboost.org/faq/faq.php"><acronym title="Frequently Asked Questions">FAQ</acronym></a>.</p>
							<p>If you have any problem please go to the <a href="http://www.phpboost.org/forum/">support forum of PHPBoost</a>.</p>
						</fieldset>
						<fieldset>
							<legend>Thanks</legend>
							<h2>Members</h2>
							<p>Thanks to all the members of the community who cheer us on daily and contribute to the software quality by reporting bugs and suggestion improvements, which allows to lead to a stable and powerful version 3.0.</p>
							<p>Thanks to the members of our teams and particulary to <strong>Ptithom</strong> and <strong>giliam</strong> for the documentation writing, <strong>KONA</strong>, <strong>Frenchbulldog</strong>, <strong>Grenouille</strong>, <strong>EnimSay</strong>, <strong>swan</strong> for the graphics, <strong>Gsgsd</strong>, <strong>Alain91</strong> and <strong>Crunchfamily</strong> for the modules development, <strong>Forensic</strong>, <strong>PiJean</strong> and <strong>Beowulf</strong> for the English translation and <strong>Shadow</strong> and <strong>Kak Miortvi Pengvin</strong> for the moderation of the community.</p>
							<h2>Other projects</h2>
							<p>PHPBoost uses different tools allowing it to enlarge its features panel enough implying the development time rising. Most of these tools are under GNU/GPL license.</p>
							<ul>
								<li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Very powerful text editor used for the whole development, thanks a lot !</li>
								<li><a href="http://www.eclipse.org/pdt/">Eclipse <acronym title="PHP Development Tools">PDT</acronym></a> : Eclipse based PHP <acronym title="Integrated Development Environment">IDE</acronym> (development tool).</li>
								<li><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Desktop Project</a> : Icon set used in the whole interface.</li>
								<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP by PHPConcept</a> : PHP library which manage work with zip files.</li>
								<li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Functions which permit us to interpret LaTeX language and export it to pictures viewable by a web browser.</li>
								<li><a href="http://tinymce.moxiecode.com/">TinyMCE</a> : TinyMCE is a <acronym title="What You See Is What You Get">WYSIWYG</acronym> editor which allows users to see their text formatting in real time.</li>
								<li><a href="http://qbnz.com/highlighter/">GeSHi</a> : Generic Syntax Highlighter used to highlight the source code of many programming languages.</li>
								<li><a href="http://script.aculo.us/">script.aculo.us</a> : Javascript and <acronym title="Asynchronous Javascript And XML">AJAX</acronym> Framework</li>
								<li><a href="http://www.alsacreations.fr/mp3-dewplayer.html">Dewplayer</a> : flash audio reader</li>
								<li><a href="http://flowplayer.org">Flowplayer</a> : flash video reader</li>
							</ul>
						</fieldset>
						<fieldset>
							<legend>Credits</legend>
							<ul>
								<li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, founder of PHPBoost project and developer</li>
								<li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, developer</li>
								<li><strong>Loïc ROUCHON</strong> <em>(alias horn)</em>, developer</li>
							</ul>
						</fieldset>';
$LANG['site_index'] = 'Go to the website';
$LANG['admin_index'] = 'Go to the administration panel';
				
// Miscalleneous
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['appendices'] = 'Appendices';
$LANG['documentation'] = 'Documentation';
$LANG['documentation_link'] = 'http://www.phpboost.org/wiki/install-phpboost';
$LANG['restart_installation'] = 'Restart the installation';
$LANG['confirm_restart_installation'] = addslashes('Are you sure you want to restart the installation ?');
$LANG['change_lang'] = 'Change language';
$LANG['change'] = 'Change';
$LANG['powered_by'] = 'Powered by';
$LANG['phpboost_right'] = '';

?>
