<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : August 23, 2007
 *   copyright          : (C) 2007 	SAUTEL Benoit
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
#                                                           English                                                                               #
####################################################

$LANG = array();
$LANG['page_title'] = 'PHPBoost installation';
$LANG['steps_list'] = 'Steps list';
$LANG['introduction'] = 'Preamble';
$LANG['config_server'] = 'Server configuration';
$LANG['database_config'] = 'Database configuration';
$LANG['advanced_config'] = 'Website configuration';
$LANG['administrator_account_creation'] = 'Administrator\'s account';
$LANG['modules_installation'] = 'Installation of modules';
$LANG['end'] = 'End of installation';
$LANG['install_progress'] = 'Progression of the installation';
$LANG['generated_by'] = 'Powered by %s';
$LANG['previous_step'] = 'Previous step';
$LANG['next_step'] = 'Next step';
$LANG['query_loading'] = 'Sending of the query to server';
$LANG['query_sent'] = 'Query loaded successful, waiting for the answer of your server';
$LANG['query_processing'] = 'Proccessing the query';
$LANG['query_success'] = 'Processing succed';
$LANG['query_failure'] = 'Processing failed';

//Introduction
$LANG['intro_explain'] = 'Thank you to have trusted PHPBoost to build your website.<br /><br />
To install PHPBoost you need to have some informations about your hosting which would be provided by your hoster. The installation is absolutely automatic, il should take only a few minutes. Click on the right arrow to start the installation process.<br /><br />
Cordially, the PHPBoost Team.';
$LANG['start_install'] = 'Start installation';

//licence
$LANG['license'] = 'License';
$LANG['require_license_agreement'] = 'You must accept GPL license terms to install PHPBoost.';
$LANG['license_agreement'] = 'End-user license agreement';
$LANG['please_agree_license'] = 'I have examited and I accept the terms and conditions of this end-user license agreement.';
$LANG['alert_agree_license'] = 'You have to agree to end-user license by ticking the form !';

//Configuration du serveur
$LANG['config_server_explain'] = 'Checking server configuration<br />
Before to start installations steps, the configuration of your server is going to be checked to establish its compatibility with PHPBoost. Please check that compulsory conditions are good.<br />
If you have problems, ask for some help in <a href="http://en.phpboost.com/forum/index.php">PHPBoost forum</a>.';
$LANG['php_version'] = 'PHP version';
$LANG['check_php_version'] = 'PHP upper than 4.1.0';
$LANG['check_php_version_explain'] = '<span style="font-weight:bold;color:red;">Compulsory :</span> To make PHPBoost correctly working, your server must have a PHP version more recent than PHP 4.1.0. Enough that you will have problems with some modules, please contact your hoster or move to a more recent server.';
$LANG['extensions'] = 'Extensions';
$LANG['check_extensions'] = 'Optional : If those extensions are enabled, you will manage to benefit from additional functions but they are not compulsory.';
$LANG['gd_library'] = 'GD library';
$LANG['gd_library_explain'] = 'Checking code to refuse bots and other instances of pictures processing';
$LANG['url_rewriting'] = 'URL Rewriting';
$LANG['url_rewriting_explain'] = 'URL rewriting to have more clearness';
$LANG['auth_dir'] = 'Directories permissions';
$LANG['check_auth_dir'] = '<span style="font-weight:bold;color:red;">Compulsory :</span> PHPBoost requires that several directories would allow writing in themselves, if your server can do it, those permissions will be set automatically. However if your server hasn\'t done it your must do it yourself. You can find help in <a href="http://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" title="PHPBoost documentation: chmod">PHPBoost documentation</a> or in your hoster\'s website.';
$LANG['refresh_chmod'] = 'Check again directories permissions';
$LANG['existing'] = 'Existing';
$LANG['unexisting'] = 'Unexisting';
$LANG['writable'] = 'Writable';
$LANG['unwritable'] = 'Not writable';
$LANG['unknown'] = 'Unknown';

//Base de données
$LANG['db_explain'] = 'This stage permits you to generate config file which will contain database login and password. If your don\'t know your database informations please ask it to your hoster. In this step database tables will be created.';
$LANG['dbms'] = 'Database management system';
$LANG['choose_dbms'] = 'Select system';
$LANG['choose_dbms_explain'] = 'Most of servers use MySQL';
$LANG['db_informations'] = 'Database parameters';
$LANG['db_host_name'] = 'Host name';
$LANG['db_host_name_explain'] = 'Address of the database management system.';
$LANG['db_login'] = 'Login';
$LANG['db_login_explain'] = 'Provided by hoster';
$LANG['db_password'] = 'Password';
$LANG['db_password_explain'] = 'Provided by hoster';
$LANG['db_name'] = 'Database name';
$LANG['db_name_explain'] = 'Provided by hoster';
$LANG['db_prefix'] = 'Tables prefix';
$LANG['db_prefix_explain'] = 'Default value is <em>phpboost_</em>';
$LANG['test_db_config'] = 'Try it';
$LANG['result'] = 'Results';
$LANG['empty_field'] = '%s field is empty';
$LANG['field_dbms'] = 'Datatabase management system';
$LANG['field_host'] = 'Host';
$LANG['field_login'] = 'Login';
$LANG['field_password'] = 'Password';
$LANG['field_database'] = 'Database name';
$LANG['db_error_dbms'] = 'The database management system you selected is untraceable.';
$LANG['db_error_connexion'] = 'Impossible to connect to database server. Please check informations which you entered.';
$LANG['db_error_selection'] = 'Impossible to select database on the server you entered. Please check that it exists.';
$LANG['db_success'] = 'The connection to your database server has been etablished successful. You can continue installation.';
$LANG['require_hostname'] = 'You must enter database hostname !';
$LANG['require_login'] = 'You must enter database login !';
$LANG['require_db_name'] = 'You must enter database name !';
$LANG['db_result'] = 'Test results';

//configuraton du site
$LANG['config_site_explain'] = 'Website configuration<br />
The basic configuration is going to be created during this step to permit PHPBoost to run. However you must know that the data you are going to enter will be editable later into administration panel, in website configuration tab. In this panel you also will manage to edit advanced configuration.';
$LANG['your_site'] = 'Your website';
$LANG['site_url'] = 'Website url :';
$LANG['site_url_explain'] = 'Shaped like http://www.google.com';
$LANG['site_path'] = 'PHPBoost path :';
$LANG['site_path_explain'] = 'Empty if your website is at the server root, else shaped like /directory.';
$LANG['default_language'] = 'Default language';
$LANG['default_theme'] = 'Default theme';
$LANG['site_name'] = 'Website name';
$LANG['site_description'] = 'Website description';
$LANG['site_description_explain'] = '(Optional) Useful for search engine optimization';
$LANG['site_keywords'] = 'Website keywords';
$LANG['site_keywords_explain'] = '(Optional) You have to enter keywords separated by commas.';
$LANG['require_site_url'] = 'You must enter your website url !';
$LANG['require_site_name'] = 'You must enter your website name !';
$LANG['confirm_site_url'] = 'The website address you entered doesn\'t correspond to address given by the server, are you sure you want to keep the address you entered ?';
$LANG['confirm_site_path'] = 'The website path you entered doesn\'t correspond to path given by the server, are you sure you want to keep the path you entered ?';

//administration
$LANG['admin_account_creation_explain'] = 'Creation of the administrator\'s account
<br />This account gives you access to administration panel in which you can configure your website. You will edit those information by editing your profile.';
$LANG['admin_account'] = 'Administrator account';
$LANG['admin_pseudo'] = 'Login';
$LANG['admin_pseudo_explain'] = 'Minimum 3 characters ';
$LANG['admin_password'] = 'Password';
$LANG['admin_password_explain'] = 'Minimum 6 charecters';
$LANG['admin_password_repeat'] = 'Repeat password';
$LANG['admin_mail'] = 'Email address';
$LANG['admin_mail_explain'] = 'Must exist to receive unlocking administration code.';
$LANG['admin_lang'] = 'Language';
$LANG['admin_require_login'] = 'You must enter a login !';
$LANG['admin_login_too_short'] = 'Your login is too short (3 characters minimum)';
$LANG['admin_require_password'] = ' You must enter a password !';
$LANG['admin_require_password_repeat'] = 'You must confirm your password !';
$LANG['admin_require_mail'] = 'You must enter an emain address !';
$LANG['admin_passwords_error'] = 'The two passwords you entered didn\'t correspond, please correct them.';
$LANG['admin_email_error'] = 'The email address your entered hasn\'t got a correct form.';
$LANG['admin_create_session'] = 'Be automatically identified at the end of the installation';
$LANG['admin_auto_connection'] = 'Remain connected systematically each time I visit my website';
$LANG['admin_error'] = 'Error';
$LANG['admin_mail_object'] = 'PHPBoost : message to be keeped';
$LANG['admin_mail_unlock_code'] = 'Dear %s,

First of all, thank you to have powered your website with PHPBoost software, we wish you will be satisfied. For any problem ask your question on PHPBoost official forum : http://en.phpboost.com/forum/index.php

Here your login and password (don\'t loose them ,you will need them to configure your website) : 

Login: %s 
Password: %s

Please keep this code (it won\'t be delivered to you anymore) : %s

This code allows you to unlock administration panel if your website undergoes a hacking attempt, I will be asked to you into the direct connexion form (%s/admin/admin.php) 

Cordialy, the PHPBoost Team.';

//Installation des modules
$LANG['modules_explain'] = 'Modules installation
<br />You can now install the modules which will allow you to elaborate a personalized website. We purpose you several preselections to simplify the installation of PHPBoost, but you can design your own selection. It\'s important to note that you will be able to install and uninstall any module, this steps only permits you te begin your website with an adapted configuration.';
$LANG['modules_list'] = 'Availabe modules list';
$LANG['modules_preselections'] = 'Available preselections';
$LANG['modules_no_module'] = 'No module';
$LANG['modules_all'] = 'All available modules';
$LANG['modules_community'] = 'Community portal';
$LANG['modules_publication'] = 'Publication website';
$LANG['modules_perso'] = 'Personalized';
$LANG['modules_other_options'] = 'Other options';
$LANG['modules_activ_member_accounts'] = 'Enable member\'s registering';
$LANG['modules_index_module'] = 'Starting module : ';
$LANG['modules_default_index'] = 'Default page';
$LANG['modules_require_javascript'] = 'You have to enable javascript execution to benefit from all automatical preselections.';

//Fin de l'installation
$LANG['end_installation'] = '<fieldset>
							<legend>PHPBoost is now installed and ready to run !</legend>
							<p class="success">
								The installation of PHPBoost has been done successfully. The PHPBoost Team thanks you to have trusted it and is proud to count you among its users.
							</p>
							<p>On administration panel index you will find news of official website, look at them and don\'t forget to update your software when a new release of PHPBoost kernel or modules is available, it\'s important for security corrections and new functionalities.</p>
							<p class="warning">
								For security reasons we advice you to delete installation folder and all its contents, hackers could manage to run installation script and destroy your data !</p>
							<p>Don\'t forget the <a href="http://en.phpboost.com/wiki/index.php">documentation</a> which will help you for using PHPBoost.</p>
							<p>If you have any problem please go to support of PHPBoost: <a href="http://en.phpboost.com/forum/index.php">forum PHPBoost</a>.</p>
						</fieldset>
						<fieldset>
							<legend>Thanks</legend>
							Members
							<br />
							<ul>
								<li>Thanks to every members who encouraged us and helped us to find bugs, which will permit to PHPBoost to be a stable software.</li>
								<li>Thank Ptithom for every thing he has done for us (documentation writing, finding bugs)</li>
							</ul>
							<br />
							Other projects
							<br />
							<ul>
								<li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Very powerfull text editor used for the whole development, thanks a lot !</li>
								<li><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Desktop Project</a> : Icon set used in the whole interface.</li>
								<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP by PHPConcept</a> : PHP library which manage work with zip files.</li>
								<li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Functions which permit us to interpret LaTeX language and export it to pictures viewable by a web browser.</li>
							</ul>
							<p style="text-align:center"><img src="images/npp_logo.gif" alt="" /></p>
						</fieldset>
						<fieldset>
							<legend>Credits</legend>
							<ul>
								<li>Régis VIARRE <em>(alias CrowkaiT)</em>, founder of PHPBoost project and developer</li>
								<li>Benoît SAUTEL <em>(alias ben.popeye)</em>, developer</li>
							</ul>
						</fieldset>';
$LANG['site_index'] = 'Go to my website';
						
//Enregistrement en ligne
$LANG['register_online'] = 'Online registering';
$LANG['register_online_explain'] = 'You can automatically register your website online. It will allow your website to appear on a list of websites powered by PHPBoost (<a href="http://www.phpboost.com/phpboost/list.php">current list</a>).
<br />
It\'s not compulsory to register your website, we just want to help you to have links to your website. If you install PHPBoost only for tests, a local working or a private using you shouldn\'t register it.
<br />
<div class="notice">Warning : you must be connected on Internet to register your website.</div>';
$LANG['register'] = 'Register my website.';
$LANG['register_i_want_to'] = 'I wish register my website and appear on list.';

//Divers
$LANG['yes'] = 'Yes';
$LANG['no'] = 'No';
$LANG['appendices'] = 'Appendices';
$LANG['documentation'] = 'Documentation';
$LANG['documentation_link'] = 'http://www.phpboost.com/wiki/installer-phpboost';
$LANG['restart_installation'] = 'Restart the installation';
$LANG['confirm_restart_installation'] = addslashes('Are you sure you want to restart the installation?');
$LANG['change_lang'] = 'Change language';
$LANG['change'] = 'Change';
		
?>