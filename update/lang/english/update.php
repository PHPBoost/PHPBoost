<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 3.0 - 2012 08 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      English                     #
####################################################

$lang = array(
	//Variables générales
	'update.title' => 'PHPBoost Update',
	'steps.list' => 'Steps list',
	'step.list.introduction' => 'Preamble',
	'step.list.license' => 'License',
	'step.list.server' => 'Server Configuration',
	'step.list.database' => 'Database settings',
	'step.list.website' => 'General settings',
	'step.list.execute' => 'Update',
	'step.list.end' => 'End of update',
	'installation.progression' => 'Update progression',
	'language.change' => 'Change language',
	'change' => 'Change',
	'step.previous' => 'Previous step',
	'step.next' => 'Next step',
	'yes' => 'Yes',
	'no' => 'No',
	'unknown' => 'Unknown',
	'generatedBy' => 'Powered by %s',
	'poweredBy' => 'Powered by',
	'phpboost.link' => 'Link to PHPBoost CMS official website',
	'phpboost.rights' => '',
	'phpboost.slogan' => 'Create your website easily and for free!',
	'phpboost.logo' => 'PHPBoost logo',

//Introduction
	'step.introduction.title' => 'Preamble',
	'step.introduction.message' => 'Welcome to PHPBoost update wizard',
	'step.introduction.explanation' => '<p>Thank you to have trusted PHPBoost to build your website.</p>
<p>To install PHPBoost you need to have some informations about your hosting which would be provided by your hoster. The installation is absolutely automatic, il should take only a few minutes. Click on the right arrow to start the installation process.</p>',
	'step.introduction.maintenance_notice' => '<div class="message-helper bgc notice">Your site will automatically be placed in maintenance. Consider disabling maintenance when you have verified that everything works properly.</div>',
	'step.introduction.team_signature' => '<p>Best regards, the PHPBoost team.</p>',

//Configuration du serveur
	'step.server.title' => 'Looking up server configuration...',
	'step.server.explanation' => '<p>Before starting update, we need to establish the compatibility between PHPBoost and your server. If you have any problems, feel free to ask your questions in our <a href="https://www.phpboost.com/forum/">Support Forums</a>.</p>
<div class="message-helper bgc notice">It is very important that each required fields are checked, otherwise you might have trouble using the software.</div>',
	'php.version' => 'PHP version',
	'php.version.check' => 'PHP higher than :min_php_version',
	'php.version.check.explanation' => '<span style="font-weight: bold;color: red;">Required:</span> To run PHPBoost, your server must run PHP :min_php_version or higher. Below that, you might have issues with some modules.',
	'php.extensions' => 'Extensions',
	'php.extensions.check' => 'The activation of these extensions will provide additional features but it is not essential to its operation except Mbstring library.',
	'php.extensions.check.gdLibrary' => 'GD library',
	'php.extensions.check.gdLibrary.explanation' => 'Library used to generate pictures such as Robot Protection, Statistics Graphics and much more.',
	'php.extensions.check.curlLibrary' => 'Curl library',
	'php.extensions.check.curlLibrary.explanation' => 'Library used to get distant elements. Mandatory to enable external authentication for instance.',
	'php.extensions.check.mbstringLibrary' => 'MBstring library',
	'php.extensions.check.mbstringLibrary.explanation' => 'Library used for UTF-8 characters management. Mandatory to have a working website.',
	'php.extensions.check.mbstringLibrary.error' => 'PHP <b>mbstring</b> extension is not activated. Please activate it or contact your web hosting company before proceeding with the installation.',
	'server.urlRewriting' => 'URL Rewriting',
	'server.urlRewriting.explanation' => 'Not only does it rewrite URLs, but it helps a lot with search engine robots.',
	'folders.chmod' => 'Directories permissions',
	'folders.chmod.check' => '<span style="font-weight: bold;color: red;">Required:</span> PHPBoost needs to change permissions of several directories to make them writable. If your hosting company allows it, it will be done automatically. However, you might need to do it by yourself to make the installation work. If you don\'t know how to change permissions of a directory, you can find help on your host website.',
	'folders.chmod.refresh' => 'Double-check directories permissions',
	'folder.exists' => 'Found',
	'folder.doesNotExist' => 'Not found',
	'folder.isWritable' => 'Writable',
	'folder.isNotWritable' => 'Not writable',
	'folders.chmod.error' => 'Some directories seem to be missing and/or not writable. You need to create the missing directories or make the directories writable.',

//Base de données
	'step.dbConfig.title' => 'Database settings',
	'db.parameters.config' => 'Database connection parameters',
	'db.parameters.config.explanation' => '<p>This step will generate a configuration file that contains the database settings and database tables will also be created at the same time. If you don\'t know your database settings, you might be able to find them in your host panel or need to ask for them from your host support.</p>',
	'dbms.parameters' => 'DBMS connection parameters',
	'dbms.host' => 'Database server hostname or DSN:',
	'dbms.host.explanation' => 'Database managing system server URL, often <em>localhost</em>',
	'dbms.port' => 'Database port',
	'dbms.port.explanation' => 'Database server port, <em>3306</em> most of the time.',
	'dbms.login' => 'Database username',
	'dbms.login.explanation' => 'Provided by your host',
	'dbms.password' => 'Database password',
	'dbms.password.explanation' => 'Provided by your host',
	'schema.properties' => 'Database properties',
	'schema' => 'Database name',
	'schema.explanation' => 'Provided by your host. If that database doesn\'t exist, PHPBoost will try to create it.',
	'schema.tablePrefix' => 'Prefix for tables in database',
	'schema.tablePrefix.explanation' => 'Default value is <em>phpboost_</em>. You will need to change this value if you want to install PHPBoost several times in the same database.',
	'db.config.check' => 'Try to establish database connection!',
	'db.connection.success' => 'The connection to the database has been established successfully. You can proceed to the next step.',
	'db.connection.error' => 'Could not connect to the database. Please check the settings you have entered.',
	'db.creation.error' => 'The database name you entered doesn\'t exist.',
	'db.unknown.error' => 'An unknown error has occured.',
	'db.required.host' => 'You must enter database hostname!',
	'db.required.port' => 'You must enter database port!',
	'db.required.login' => 'You must enter database username!',
	'db.required.schema' => 'You must enter database name!',
	'db.unexisting_database' => 'The database doesn\'t exists. Check parameters',
	'phpboost.notInstalled' => 'Unexisting installation',
	'phpboost.notInstalled.explanation' => '<p>The database on which you want to update PHPBoost contains no installation.</p>
	<p> Please check that you have specified the correct prefix and good database.</p>',

//Execute update
	'congratulations' => 'Congratulations!',
	'step.execute.title' => 'Execute update',
	'step.execute.update_in_progress' => 'Update in progress',
	'step.execute.message' => 'Site update',
	'step.execute.explanation' => 'This step will convert your site PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' to ' . UpdateServices::NEW_KERNEL_VERSION . ' PHPBoost.
	<br /><br />
	Be careful, this step is irreversible, as a precaution please backup your data first!',
	'step.execute.incompatible_modules' => 'The following modules will be disabled because they are not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. ': :modules.
	<br />To reactivate them, download their compatible version from the <a href="https://www.phpboost.com/download">PHPBoost</a> website if it exists and go to the module update page of your site to update them (they will then be reactivated automatically).',
	'step.execute.incompatible_module' => 'The module :modules will be disabled because it is not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. '.
	<br />To reactivate it, download its compatible version from the <a href="https://www.phpboost.com/download">PHPBoost</a> website if it exists and go to the module update page of your site to update it (it will then be reactivated automatically).',
	'step.execute.incompatible_module.default' => '<br /><br /><b>:old_default</b> module placed at site startup will be replaced by <b>:new_default</b> module. When you install the new compatible version of <b>:old_default</b> module, remember to reconfigure your site\'s start page in the general configuration.',
	'step.execute.incompatible_themes' => 'The following themes will be disabled because they are not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. ': :themes.
	<br />To reactivate them, download their compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the themes on your site FTP in templates folder and then go to the themes management page of your site to reactivate them.',
	'step.execute.incompatible_theme' => 'The theme :themes will be disabled because it is not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. '.
	<br /> To reactivate it, download its compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the theme on you site FTP in templates folder then go to the theme management page of your site to reactivate it.',
	'step.execute.incompatible_theme.default' => '<br /><br /><b>:old_default</b> default theme for the site will be replaced by <b>:new_default</b> theme. When you have installed the new compatible version of <b>:old_default</b> theme, remember to reconfigure your site\'s default theme in the general configuration (if that was the only theme then uninstall <b>:new_default</b> theme so that users of the site have this theme active).',
	'step.execute.incompatible_langs' => 'The following langs will be disabled because they are not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. ': :langs.
	<br />To reactivate them, download their compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the langs on your site FTP in lang folder and then go to the langs management page of your site to reactivate them.',
	'step.execute.incompatible_lang' => 'The lang :langs will be disabled because it is not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. '.
	<br /> To reactivate it, download its compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the lang on you site FTP in lang folder then go to the lang management page of your site to reactivate it.',
	'step.execute.incompatible_lang.default' => '<br /><br /><b>:old_default</b> default lang for the site will be replaced by <b>:new_default</b> lang. When you have installed the new compatible version of <b>:old_default</b> lang, remember to reconfigure your site\'s default lang in the general configuration (if that was the only lang then uninstall <b>:new_default</b> lang so that users of the site have this lang active).',

//Finish update
	'finish.message' => '<fieldset>
							<legend>PHPBoost is now updated and ready to run!</legend>
							<div class="fielset-inset">
								<p class="message-helper bgc success">The update of PHPBoost has been powered successfully. The PHPBoost Team thanks you for using its software and is proud to count you among its users.</p>
								<p class="message-helper bgc warning">Please download the <a href="' . GeneralConfig::load()->get_complete_site_url() . '/update/update_log.txt" download>log file</a> of your update, it could be required on PHPBoost forum if you ask for support.</p>
								' . (class_exists('FacebookSocialNetwork') ? '<p class="message-helper bgc warning">For those who use Facebook authentication, go to the configuration page of your Facebook Application on <a href="https://developers.facebook.com">Facebook developers</a> and change redirect URL to : <a href="' . UserUrlBuilder::connect(FacebookSocialNetwork::SOCIAL_NETWORK_ID)->absolute() . '">' . UserUrlBuilder::connect(FacebookSocialNetwork::SOCIAL_NETWORK_ID)->absolute() . '</a>.</p>' : '') . '
								<p>Keep yourself informed about the evolution of PHPBoost by visiting our website, <a href="https://www.phpboost.com">www.phpboost.com</a>. You will be warned in the administration panel when updates are available. We strongly recommend to keep your website up to date so you can take advantage of the latest features and correct any flaws or errors.</p>
								<p class="message-helper bgc warning">For security reasons we also recommand you to delete the <b>update</b> folder and all its contents, hackers could manage to run the update script and you could lose data! An option will be offered once connected to the site to perform this deletion.</p>
								<p>Don\'t forget the <a href="https://www.phpboost.com/wiki/">documentation</a> which will help you for using PHPBoost and the <a href="https://www.phpboost.com/faq/"><acronym aria-label="Frequently Asked Questions">FAQ</acronym></a>.</p>
								<p>If you have any problem please go to the <a href="https://www.phpboost.com/forum/">support forum of PHPBoost</a>.</p>
							</div>
						</fieldset>
						<fieldset>
							<legend>Thanks</legend>
							<div class="fielset-inset">
								<h2>Members</h2>
								<p>Thanks to all the members of the community who cheer us on daily and contribute to the software quality by reporting bugs and suggestion improvements, which allows to lead to a stable and powerful CMS.</p>
								<p>Thanks to the members of our teams and particulary to <strong>benflovideo</strong> for communication team, <strong>mipel</strong>, <strong>olivierb</strong> and <strong>xela</strong> for the documentation writing, <strong>ElenWii</strong> and <strong>babsolune</strong> for the graphics, <strong>benflovideo</strong>, <strong>mipel</strong> and <strong>olivierb</strong> for the moderation of the community and <strong>janus57</strong> for support in development and community help on the forum.</p>
								<h2>Other projects</h2>
								<p>PHPBoost uses different tools allowing it to enlarge its features panel enough implying the development time rising. Most of these tools are under GNU/GPL license.</p>
								<ul>
									<li><a href="https://notepad-plus-plus.org">Notepad++</a> and <a href="http://www.sublimetext.com">Sublime Text</a>: Very powerful text editors used for the whole development, thanks a lot!</li>
									<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP by PHPConcept</a>: PHP library which manage work with zip files.</li>
									<li><a href="http://www.xm1math.net/phpmathpublisher/">PHPMathPublisher</a>: Functions which permit us to interpret LaTeX language and export it to pictures viewable by a web browser.</li>
									<li><a href="http://www.tinymce.com">TinyMCE</a>: TinyMCE is a <acronym aria-label="What You See Is What You Get">WYSIWYG</acronym> editor which allows users to see their text formatting in real time.</li>
									<li><a href="http://qbnz.com/highlighter/">GeSHi</a>: Generic Syntax Highlighter used to highlight the source code of many programming languages.</li>
									<li><a href="http://jquery.com">jQuery</a>: Javascript and <acronym aria-label="Asynchronous Javascript And XML">AJAX</acronym> Framework</li>
									<li><a href="http://flowplayer.org">Flowplayer</a>: flash video reader</li>
									<li><a href="http://fontawesome.io">Font Awesome</a>: icons librairy</li>
									<li><a href="http://l-lin.github.io/font-awesome-animation/">Font Awesome Animation</a> : Animation for librairy Font Awesome</li>
									<li><a href="http://cornel.bopp-art.com/lightcase/">Lightcase.js</a>: responsive Lightbox</li>
									<li><a href="https://github.com/cssmenumaker/jQuery-Plugin-Responsive-Drop-Down">CssMenuMaker</a>: Menus responsive</li>
									<li><a href="http://www.jerrylow.com/basictable/">BasicTable.js</a>: Tables responsive</li>
								</ul>
							</div>
						</fieldset>
						<fieldset>
							<legend>Make a donation</legend>
							<div class="fielset-inset">
								If you want to financially support PHPBoost, you can donate via paypal :

								<div class="align-center">
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
										<input type="hidden" name="cmd" value="_s-xclick">
										<input type="hidden" name="hosted_button_id" value="7EFHMABH75HPE">
										<input type="image" src="https://resources.phpboost.com/documentation/paypal/button_english.png" border="0" name="submit" alt="PHPBoost - PayPal">
									</form>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>Credits</legend>
							<div class="fielset-inset">
								<ul>
									<li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, founder of PHPBoost project and developer retired</li>
									<li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, developer retired</li>
									<li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, developer retired</li>
									<li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, developer</li>
									<li><strong>Julien BRISWALTER</strong> <em>(alias j1.seth)</em>, developer</li>
								</ul>
							</div>
						</fieldset>',
	'site.index' => 'Go to the website',
	'admin.index' => 'Go to the administration panel'
);
?>
