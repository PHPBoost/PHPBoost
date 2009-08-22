<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : September 28, 2008
 *   copyright            : (C) 2008 	Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

//Erreur générée par le moteur de template
$LANG['cache_tpl_must_exist_and_be_writable'] = '<h1>PHPBoost installation</h1>
<p><strong>Warning</strong> : the folder cache/tpl must exist and be writable. Please create and/or give it the good CHMOD (777) to be able to continue the installation.</p>
<p>Once it is done, please refresh the page to continue or click <a href="">here</a>.</p>';

//Variables générales
$LANG['page_title'] = 'PHPBoost installation';
$LANG['steps_list'] = 'Steps list';
$LANG['introduction'] = 'Preamble';
$LANG['config_server'] = 'Server configuration';
$LANG['database_config'] = 'Database configuration';
$LANG['advanced_config'] = 'Website configuration';
$LANG['administrator_account_creation'] = 'Administrator account';
$LANG['end'] = 'End of installation';
$LANG['install_progress'] = 'Installation progression';
$LANG['generated_by'] = 'Powered by %s';
$LANG['previous_step'] = 'Previous step';
$LANG['next_step'] = 'Next step';
$LANG['query_loading'] = 'Sending query to the server';
$LANG['query_sent'] = 'Query sent, waiting for response';
$LANG['query_processing'] = 'Query processing';
$LANG['query_success'] = 'The process has been done successfully';
$LANG['query_failure'] = 'An error occured during query processing';

//Introduction
$LANG['intro_title'] = 'Welcome in PHPBoost installation wizard';
$LANG['intro_explain'] = '<p>Thank you to have trusted PHPBoost to build your website.</p>
<p>To install PHPBoost you need to have some informations about your server which must be provided by your hoster. The installation is absolutely automatic, it should take only a few minutes. Click on the right arrow above to start the installation process.</p>
<p>Cordially, the PHPBoost Team.</p>';
$LANG['intro_distribution'] = '%s distribution';
$LANG['intro_distribution_intro'] = '<p>It exists differents distributions of PHPBoost which enable users to configure their website according to their objectives. A distribution contains modules and a kernel configuration.</p>
<p>PHPBoost is going to install itself according to the configuration of this distribution. You will be able to change the configuration, add or remove modules later, when using PHPBoost.</p>';
$LANG['start_install'] = 'Start installation';

//licence
$LANG['license'] = 'License';
$LANG['require_license_agreement'] = 'You must accept the GNU/GPL license terms to install PHPBoost.';
$LANG['license_agreement'] = 'End-user license agreement';
$LANG['license_terms'] = 'License terms';
$LANG['please_agree_license'] = 'I agree to and will be bound by the terms and conditions set forth in this end-user license agreement';
$LANG['alert_agree_license'] = 'You have to agree to end-user license by checking the form off !';

//Configuration du serveur
$LANG['config_server_title'] = 'Checking server configuration';
$LANG['config_server_explain'] = '<p>Before to start installation stages, the configuration of your server is going to be checked to etablish its compatibility with PHPBoost.</p>
<div class="notice">Please check that every required condition is ok.</div>
<p>If you have problems, ask your questions in the <a href="http://www.phpboost.net/forum/index.php">support forum</a>.</p>';
$LANG['php_version'] = 'PHP version';
$LANG['check_php_version'] = 'PHP upper than 4.1.0';
$LANG['check_php_version_explain'] = '<span style="font-weight:bold;color:red;">Compulsory:</span> To run PHPBoost correctly, your server must have a more recent PHP version than PHP 4.1.0. Enough that, you might have problems with some modules. We advise you to contact your hoster or migrate to a younger server.';
$LANG['extensions'] = 'Extensions';
$LANG['check_extensions'] = 'Optional: If those extensions are enabled, you will benefit from additionnal features.';
$LANG['gd_library'] = 'GD library';
$LANG['gd_library_explain'] = 'Librairy used to generate pictures (for instance for robot protection)';
$LANG['url_rewriting'] = 'URL Rewriting';
$LANG['url_rewriting_explain'] = 'URL rewriting allow to have nicer URLs and more research robots friendly';
$LANG['auth_dir'] = 'Directories permissions';
$LANG['check_auth_dir'] = '<span style="font-weight:bold;color:red;">Compulsory :</span> PHPBoost requires that several directories would be writable. If your server allows it, those permissions will be set automatically. However if your server hasn\'t done it lonely, your must do it yourself. You can find help in <a href="http://www.phpboost.net/wiki/change-the-chmod-of-a-directory" title="PHPBoost documentation: chmod">PHPBoost documentation</a> or on your hoster\'s website.';
$LANG['refresh_chmod'] = 'Check again directories permissions';
$LANG['existing'] = 'Existing';
$LANG['unexisting'] = 'Unexisting';
$LANG['writable'] = 'Writable';
$LANG['unwritable'] = 'Not writable';
$LANG['unknown'] = 'Unknown';
$LANG['config_server_dirs_not_ok'] = 'The directories are not all existing and/or writable. Please do the operation manually before to continue.';

//Base de données
$LANG['db_title'] = 'Database connection parameters';
$LANG['db_explain'] = '<p>This stage will generate the configuration file which will contain the database login and password. If your don\'t know your database informations please ask them to your hoster. In this step database tables will be created.';
$LANG['dbms_paramters'] = 'DBMS connection parameters';
$LANG['db_host_name'] = 'Host name';
$LANG['db_host_name_explain'] = 'Database managing system server URL, often <em>localhost</em>';
$LANG['db_login'] = 'Login';
$LANG['db_login_explain'] = 'Provided by hoster';
$LANG['db_password'] = 'Password';
$LANG['db_password_explain'] = 'Provided by hoster';
$LANG['db_properties'] = 'Database properties';
$LANG['db_name'] = 'Database name';
$LANG['db_name_explain'] = 'Provided by hoster. If that database doesn\'t exist, PHPBoost will try to create it.';
$LANG['db_prefix'] = 'Tables prefix';
$LANG['db_prefix_explain'] = 'Default value is <em>phpboost_</em>. This value is to change if you want to install several times PHPBoost in the same database.';
$LANG['test_db_config'] = 'Try it';
$LANG['result'] = 'Results';
$LANG['empty_field'] = '%s field is empty';
$LANG['field_dbms'] = 'Datatabase managing system';
$LANG['field_host'] = 'Host';
$LANG['field_login'] = 'Login';
$LANG['field_password'] = 'Password';
$LANG['field_database'] = 'Database name';
$LANG['db_error_connexion'] = 'Impossible to connect to database server. Please check the informations you entered.';
$LANG['db_error_selection_not_creable'] = 'The database you typed doesn\'t exist and the system couldn\'t create it.';
$LANG['db_error_selection_but_created'] = 'The database you typed doesn\'t exist but the system could create it.';
$LANG['db_success'] = 'The connection to your database server has been etablished successful. You can continue installation.';
$LANG['db_error_tables_already_exist'] = 'A PHPBoost instance already exists on this database with this prefix. If you continue, these tables will be deleted and the data that they contain will be lost.';
$LANG['db_unknown_error'] = 'An unknown error has occured.';
$LANG['require_hostname'] = 'You must enter database hostname!';
$LANG['require_login'] = 'You must enter database login!';
$LANG['require_db_name'] = 'You must enter database name!';
$LANG['db_result'] = 'Test results';
$LANG['already_installed'] = 'Existing installation';
$LANG['already_installed_explain'] = '<p>The database on which you want to install PHPBoost already contains a version of PHPBoost.</p>
<p>If you do the installation on this database with the same configuration, you will overwrite the already existing data. If you want to install two sites on the same database, use different prefixes for the two sites.</p>';
$LANG['already_installed_overwrite'] = 'I want to overwrite the already existing data and continue the installation.';

//configuraton du site
$LANG['site_config_title'] = 'Website configuration';
$LANG['site_config_explain'] = '<p>The basic configuration is going to be created into this step to enable PHPBoost to run. However you must know that datas you are going to enter will be editable later into administration panel, in website configuration tab. In this panel you also will manage to edit advanced configuration.</p>';
$LANG['your_site'] = 'Your website';
$LANG['site_url'] = 'Website url :';
$LANG['site_url_explain'] = 'For instance http://www.phpboost.net';
$LANG['site_path'] = 'PHPBoost path :';
$LANG['site_path_explain'] = 'Empty whether your website is at the root of the server, otherwise the directory path, for instance /directory.';
$LANG['site_name'] = 'Website name';
$LANG['site_timezone'] = 'Site timezone';
$LANG['site_timezone_explain'] = 'The default value is the server one. You will be able to change this value later in the administration panel.';
$LANG['site_description'] = 'Website description';
$LANG['site_description_explain'] = '(Optional) Useful for search engine optimization';
$LANG['site_keywords'] = 'Website keywords';
$LANG['site_keywords_explain'] = '(Optional) You have to enter keywords separated by commas.';
$LANG['require_site_url'] = 'You must enter your website\'s url !';
$LANG['require_site_name'] = 'You must enter your website\'s name !';
$LANG['confirm_site_url'] = 'The website address you entered doesn\'t correspond to address powered by the server, are you sure you want to keep the address you entered ?';
$LANG['confirm_site_path'] = 'The website path you entered doesn\'t correspond to path powered by the server, are you sure you want to keep the path you entered ?';
$LANG['site_config_maintain_text'] = 'The site is currently under maintenance.';
$LANG['site_config_mail_signature'] = 'King regards, the site team.';
$LANG['site_config_msg_mbr'] = 'Welcome in the website. You are member of the site, you can access to all spaces requiring a member accoun of the site, edit your profile and consult your contributions.';
$LANG['site_config_msg_register'] = 'You are just going to register yourself on the site. We ask you yo be polite and respectful in your interventions.<br />
<br />
Thanks, the site team.';

//administration
$LANG['admin_account_creation'] = 'Administrator account creation';
$LANG['admin_account_creation_explain'] = '<p>This account gives you access to administration panel in which you can configure your website. You will manage to edit those information by editing your profile.</p>
<p>You will be able to grant other people administrator rights later. Here you just create the first administrator account, without which you couldn\'t manage your website.</p>';
$LANG['admin_account'] = 'Administrator account';
$LANG['admin_pseudo'] = 'Login';
$LANG['admin_pseudo_explain'] = 'Minimum 3 characters';
$LANG['admin_password'] = 'Password';
$LANG['admin_password_explain'] = 'Minimum 6 characters';
$LANG['admin_password_repeat'] = 'Repeat password';
$LANG['admin_mail'] = 'Email address';
$LANG['admin_mail_explain'] = 'Must exist to receive unlocking administration code.';
$LANG['admin_require_login'] = 'You must enter a login !';
$LANG['admin_login_too_short'] = 'Your login is too short (at least 3 characters)';
$LANG['admin_password_too_short'] = 'Your password is too short (at least 6 characters)';
$LANG['admin_require_password'] = ' You must enter a password !';
$LANG['admin_require_password_repeat'] = 'You must confirm your password !';
$LANG['admin_require_mail'] = 'You must enter an emain address !';
$LANG['admin_passwords_error'] = 'The two passwords you entered didn\'t correspond, please correct them.';
$LANG['admin_email_error'] = 'The email address your entered hasn\'t got a correct form.';
$LANG['admin_invalid_email_error'] = 'Invalid email';
$LANG['admin_create_session'] = 'Be automatically identified at the end of the installation';
$LANG['admin_auto_connection'] = 'Remain connected systematically each time I visit my website';
$LANG['admin_error'] = 'Error';
$LANG['admin_mail_object'] = 'PHPBoost : message to be preserved';
$LANG['admin_mail_unlock_code'] = 'Dear %s,

First of all, thank you to have powered your website with PHPBoost software, we wish you will be satisfied. For any problem ask your question on PHPBoost official forum : http://www.phpboost.net/forum/index.php

Here your login and password (don\'t loose then you will need them to configurate your website) : 

Login: %s 
Password: %s

Please preserve this code (il won\'t be delivered to you anymore) : %s

This code permits you to unlock administration panel if your website undergoes a hacking attempt, I will be asked to you into the direct connexion form (%s/admin/admin_index.php) 

Cordialy, the PHPBoost Team.';

//Fin de l'installation
$LANG['end_installation'] = '<fieldset>
							<legend>PHPBoost is now installed and ready to run !</legend>
							<p class="success">The installation of PHPBoost has been powered successfully. The PHPBoost Team thanks you to have trusted it and is proud to count you among its users.</p>
							<p>We advise you keep yourself informed about the evolution of PHPBoost thanks to the english community website, <a href="http://www.phpboost.net">www.phpboost.net</a>. You will be warned in the administration panel when some updates will be available. It\'s important to keep your site up to date, you can take advantage of the new features and known bugs or error are corrected.</p>
							<p class="warning">For security reasons we advice you to delete the installation folder and all its contents, hackers could manage to run installation script and you could loose some data !</p>
							<p>Don\'t forget the <a href="http://www.phpboost.net/wiki/">documentation</a> which will help you for using PHPBoost and the <a href="http://www.phpboost.net/faq/faq.php"><acronym title="Frequently Asked Questions">FAQ</acronym></a>.</p>
							<p>If you have any problem please go to the <a href="http://www.phpboost.net/forum/">support of PHPBoost</a>.</p>
						</fieldset>
						<fieldset>
							<legend>Thanks</legend>
							<h2>Members</h2>
							<p>Thanks to all the members of the community who cheer us on daily and contribute to the software quality by reporting bugs and suggestion improvements, which allows to lead to a stable and powerful version 3.0.</p>
							<p>Thanks to the members of our teams and particulary to <strong>Ptithom</strong> and <strong>giliam</strong> for the documentation writing, <strong>KONA</strong>, <strong>Frenchbulldog</strong>, <strong>Grenouille</strong>, <strong>EnimSay</strong>, <strong>swan</strong> for the graphics, <strong>Gsgsd</strong>, <strong>Alain91</strong> and <strong>Crunchfamily</strong> for the modules development, <strong>Forensic</strong>, <strong>PiJean</strong> and <strong>Beowulf</strong> for the English translation and <strong>Shadow</strong> and <strong>Kak Miortvi Pengvin</strong> for the moderation of the community.</p>
							<h2>Other projects</h2>
							<p>PHPBoost uses differents tools allowing it to enlarge its features panel enough implying the development time rising. Most of these tools are under GNU/GPL license.</p>
							<ul>
								<li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Very powerfull text editor used for the whole development, thanks a lot !</li>
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
				
//Divers
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['appendices'] = 'Appendices';
$LANG['documentation'] = 'Documentation';
$LANG['documentation_link'] = 'http://www.phpboost.net/wiki/install-phpboost';
$LANG['restart_installation'] = 'Restart the installation';
$LANG['confirm_restart_installation'] = addslashes('Are you sure you want to restart the installation ?');
$LANG['change_lang'] = 'Change language';
$LANG['change'] = 'Change';

$LANG['powered_by'] = 'Powered by';
$LANG['phpboost_right'] = '';

?>