<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 20
 * @since       PHPBoost 3.0 - 2010 05 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['install.chmod.cache.not.writable'] = '
	<h1>PHPBoost installation</h1>
	<p><strong>Warning</strong>: the folders cache and cache/tpl must exist and be writable. Please create and/or set them the right CHMOD (755) to be able to continue the installation.</p>
	<p>Once it is done, please refresh the page to continue or click <a href="#">here</a>.</p>
';

// Steps menu
$lang['install.change.language']   = 'Change language';

$lang['install.steps.list']        = 'Steps list';
$lang['install.step.introduction'] = 'Preamble';
$lang['install.step.license']      = 'License';
$lang['install.step.server']       = 'Server Configuration';
$lang['install.step.database']     = 'Database settings';
$lang['install.step.website']      = 'General settings';
$lang['install.step.admin']        = 'Administrator account';
$lang['install.step.end']          = 'End of installation';

$lang['install.appendices'] = 'Appendices';

$lang['install.restart'] = 'Restart the installation';
$lang['install.confirm.restart'] = 'Are you sure you want to restart the installation?';

// General variables
$lang['install.title'] = 'PHPBoost installation';
$lang['install.documentation.link'] = 'https://www.phpboost.com/wiki/installer-phpboost';

// Welcome
$lang['install.welcome.title']                    = 'Preamble';
$lang['install.welcome.message']                  = 'Welcome to PHPBoost installation wizard';
$lang['install.welcome.description']              = '
	<p>Thank you for choosing PHPBoost to build your website.</p>
	<p>Before starting installation, be sure to have to hand Database information provided by your hosting company. The installation is automatic and should take only a few minutes of your time.  To start the installation process, please click on the green arrow down below.</p>
	<p>Best regards, the PHPBoost team</p>
';
$lang['install.welcome.distribution']             = ':distribution Distribution';
$lang['install.welcome.distribution.description'] = '<p>There are several distributions which allow you to setup easily their website according to your needs. PHPBoost will install the kernel and modules according to the chosen distribution. After the installation is complete, you will be able to change settings and add/remove modules.</p>';

// License
$lang['install.license.title']             = 'License';
$lang['install.license.terms']             = 'GNU/GPL license terms.';
$lang['install.license.agreement']         = 'I have read and understand the End-user License Terms which applies to this software';
$lang['install.license.warning.agreement'] = 'You must accept the license to be able to continue!';

// Server setup
$lang['install.server.title']       = 'Checking the server configuration';
$lang['install.server.description'] = '
	<p>Before starting installation, we need to establish the compatibility between PHPBoost and your server. If you have any problems, feel free to ask your questions in our <a href="https://www.phpboost.com/forum/">Support Forums</a>.</p>
	<div class="message-helper bgc notice">It is very important that each required fields are checked, otherwise you might have trouble using the software.</div>
	<p>In case of issue, feel free to ask some help on the <a href="https://www.phpboost.com/forum/">support forum</a>.</p>
';

$lang['install.php.version']                         = 'PHP version';
$lang['install.php.version.check']                   = 'PHP minimum version :min_php_version';
$lang['install.php.version.check.clue']              = 'Your PHP version is <b>:php_version</b>';
$lang['install.php.version.check.description']       = '<span class="text-strong error">Required:</span> To run PHPBoost, your server must run PHP :min_php_version or higher. Below that, you might have issues with some modules.';
$lang['install.php.extensions']                      = 'Extensions';
$lang['install.php.extensions.check']                = 'The activation of these extensions will provide additional features but it is not essential to its operation except Mbstring library.';
$lang['install.php.extensions.check.gd']             = 'GD library';
$lang['install.php.extensions.check.gd.clue']        = 'Library used to generate pictures such as Robot Protection, Statistics Graphics and much more.';
$lang['install.php.extensions.check.curl']           = 'Curl library';
$lang['install.php.extensions.check.curl.clue']      = 'Library used to get distant elements. Mandatory to enable external authentication for instance.';
$lang['install.php.extensions.check.mbstring']       = 'MBstring library';
$lang['install.php.extensions.check.mbstring.clue']  = 'Library used for UTF-8 characters management. Mandatory to have a working website.';
$lang['install.php.extensions.check.mbstring.error'] = 'PHP <b>mbstring</b> extension is not activated. Please activate it or contact your web hosting company before proceeding with the installation.';
$lang['install.url.rewriting']                       = 'URL Rewriting';
$lang['install.url.rewriting.clue']                  = 'Not only does it rewrite URLs, but it helps a lot with search engine robots.';

$lang['install.folders.chmod']         = 'Directories permissions';
$lang['install.folders.chmod.check']   = '<span class="text-strong error">Required:</span> PHPBoost needs to change permissions of several directories to make them writable. If your hosting company allows it, it will be done automatically. However, you might need to do it by yourself to make the installation work. If you don\'t know how to change permissions of a directory, you can find help on your host website.';
$lang['install.folder.existing']       = 'Found';
$lang['install.folder.non.existent']   = 'Not found';
$lang['install.folder.writable']       = 'Writable';
$lang['install.folder.not.writable']   = 'Not writable';
$lang['install.folders.chmod.error']   = 'Some directories seem to be missing and/or not writable. You need to create the missing directories or make the directories writable.';
$lang['install.folders.chmod.refresh'] = 'Recheck directories permissions';

// Database
$lang['install.db.config.title']                  = 'Database settings';
$lang['install.db.parameters.config']             = 'Database connection parameters';
$lang['install.db.parameters.config.description'] = '<p>This step will generate a configuration file that contains the database settings and database tables will also be created at the same time. If you don\'t know your database settings, you might be able to find them in your host panel or need to ask for them from your host support.</p>';

$lang['install.dbms.parameters']    = 'DBMS connection parameters';
$lang['install.dbms.host']          = 'Database server hostname or DSN:';
$lang['install.dbms.host.clue']     = 'Database managing system server URL, often <em>localhost</em>';
$lang['install.dbms.port']          = 'Database port';
$lang['install.dbms.port.clue']     = 'Database server port, <em>3306</em> most of the time.';
$lang['install.dbms.login']         = 'Database username';
$lang['install.dbms.login.clue']    = 'Provided by your host';
$lang['install.dbms.password']      = 'Database password';
$lang['install.dbms.password.clue'] = 'Provided by your host';

$lang['install.schema.properties']        = 'Database properties';
$lang['install.schema']                   = 'Database name';
$lang['install.schema.clue']              = 'Provided by your host. If that database doesn\'t exist, PHPBoost will try to create it.';
$lang['install.schema.table.prefix']      = 'Prefix for tables in database';
$lang['install.schema.table.prefix.clue'] = 'Default value is <em>phpboost_</em>. You will need to change this value if you want to install PHPBoost several times in the same database.';
$lang['install.db.config.check']          = 'Try to establish database connection!';
$lang['install.db.connection.success']    = 'The connection to the database has been established successfully. You can proceed to the next step.';
$lang['install.db.connection.error']      = 'Could not connect to the database. Please check the settings you have entered.';
$lang['install.db.creation.error']        = 'The database name you entered doesn\'t exist and we can\'t create it for you. You need to manually create the database.';
$lang['install.db.unknown.error']         = 'An unknown error has occured.';
$lang['install.db.unknown.error.detail']  = 'An unknown error has occured. You can get the complete error in the file <em>/cache/error.log</em> on your FTP if the support ask for it.';
$lang['install.db.required.host']         = 'You must enter database hostname!';
$lang['install.db.required.port']         = 'You must enter database port!';
$lang['install.db.required.login']        = 'You must enter database username!';
$lang['install.db.required.schema']       = 'You must enter database name!';

$lang['install.phpboost.already.installed']                   = 'Existing installation';
$lang['install.phpboost.already.installed.alert']             = 'A PHPBoost installation has been found on this database with the prefix you entered. <span style="font-weight: bold;">If you proceed, all the data in this database will be lost.</span>';
$lang['install.phpboost.already.installed.description']       = '
	<p>The database name you entered contains a PHPBoost installation with the same table prefix.</p>
	<p>If you proceed with the installation, all the data in your database will be lost. If you want to install another PHPBoost, you absolutely need to use another table prefix.</p>
';
$lang['install.phpboost.already.installed.overwrite']         = 'Yes, I want to overwrite the existing data and proceed with the installation.';
$lang['install.phpboost.already.installed.overwrite.confirm'] = 'Please, confirm the previous installation override';

// Website settings
$lang['install.website.config.title']          = 'Server settings';
$lang['install.website.config']             = 'Website settings';
$lang['install.website.config.description'] = '<p>At this stage, some basic settings will be created that will allow PHPBoost to work. Anything you enter in this form may be modified after installation in the administration panel. Also after the installation, you will have to set advanced configurations in the admin panel.</p>';

$lang['install.website.yours']            = 'Your website';
$lang['install.website.host']             = 'Website url';
$lang['install.website.host.clue']        = 'e.g. https://www.phpboost.com';
$lang['install.website.path']             = 'PHPBoost path';
$lang['install.website.path.clue']        = 'The path where PHPBoost is located relative to the domain name, e.g. /PHPBoost.';
$lang['install.website.name']             = 'Website name';
$lang['install.website.slogan']           = 'Site slogan';
$lang['install.website.description']      = 'Website description';
$lang['install.website.description.clue'] = '<span style="font-weight: bold;">Optional:</span> Useful for search engine optimization';
$lang['install.website.timezone']         = 'System timezone';
$lang['install.website.timezone.clue']    = 'The default value is the server one. You will be able to change this value later in the administration panel.';
$lang['install.website.host.required']    = 'You must enter your website\'s url!';
$lang['install.website.name.required']    = 'You must enter your website\'s name!';
$lang['install.website.host.warning']     = 'The website address you entered doesn\'t match your server address. Are you sure you want to keep to proceed?';
$lang['install.website.path.warning']     = 'The website path you entered doesn\'t correspond to path powered by the server, are you sure you want to keep the path you entered?';
$lang['install.website.captcha.config']   = 'Captcha configuration';

// Admin account
$lang['install.admin.title'] = 'Admin Account';
$lang['install.admin.creation'] = 'Administrator account creation';
$lang['install.admin.creation.description'] = '
	<p>This account gives you access to administration panel in which you can setup your website.</p>
	<p>You will be able to grant administrator rights to other members later. Here you just create the first administrator account, without which you can\'t manage your website.</p>
';

$lang['install.admin.login.length'] = 'Your username is too short (at least 3 characters)';
$lang['install.admin.password'] = 'Administrator password';
$lang['install.admin.password.clue'] = 'Minimum :number characters';
$lang['install.admin.password.repeat'] = 'Confirm administrator password';
$lang['install.admin.password.required'] = ' You must enter a password!';
$lang['install.admin.password.length'] = 'Your password is too short (at least :number characters)';
$lang['install.admin.email'] = 'Contact e-mail address';
$lang['install.admin.email.clue'] = 'Must be valid to receive unlock administration code.';
$lang['install.admin.email.required'] = 'You must enter an e-mail address!';
$lang['install.admin.email.invalid'] = 'The email address you entered is invalid.';
$lang['install.admin.connect.after.install'] = 'Log me on at the end of the installation';
$lang['install.admin.autoconnect'] = 'Log me on automatically each visit';

// Services
$lang['install.admin.created.email.object'] = 'PHPBoost: message to be preserved';
$lang['install.admin.created.email.unlock.code'] = 'Dear %s,

Thank you for powering your website with PHPBoost software, we hope you will be satisfied. If you have any problems ask your questions on the PHPBoost support forum : https://www.phpboost.com/forum/

Here is your login and password (don\'t lose them, you will need them to setup your website):

Login: %s
If you loose your password, you can generate a new one from this link %s

Best regards,
The PHPBoost Team.';

// End of installation
$lang['install.congratulations'] = 'Congratulations!';
$lang['install.finish.title']    = 'End of installation';
$lang['install.finish.message'] = '
	<fieldset>
		<legend>PHPBoost is now installed and ready to run!</legend>
		<div class="fielset-inset">
			<p class="message-helper bgc success">The installation of PHPBoost has been powered successfully. The PHPBoost Team thanks you for using its software and is proud to count you among its users.</p>
			<p>Keep yourself informed about the evolution of PHPBoost by visiting our website, <a href="https://www.phpboost.com">www.phpboost.com</a>. You will be warned in the administration panel when updates are available. We strongly recommend to keep your website up to date so you can take advantage of the latest features and correct any flaws or errors.</p>
			<p class="message-helper bgc warning">For security reasons we also recommand you to delete the <b>install</b> folder and all its content. Hackers could manage to run the installation script and you could lose data! An option will be offered once connected to the site to perform this deletion.</p>
			<p>Don\'t forget to consult the <a href="https://www.phpboost.com/wiki/">documentation</a> which will help you for using PHPBoost and the <a href="https://www.phpboost.com/faq/"><abbr aria-label="Frequently Asked Questions">FAQ</abbr></a>.</p>
			<p>If you have any problem please go to the <a href="https://www.phpboost.com/forum/">support forum of PHPBoost</a>.</p>
		</div>
	</fieldset>
	<fieldset>
		<legend>Thanks</legend>
		<div class="fielset-inset">
			<h2>Members</h2>
			<p>Thanks to all the members of the community who cheer us on daily and contribute to the software quality by reporting bugs and suggestion improvements, which allows to lead to a stable and powerful CMS.</p>
			<p>Thanks to the members of our teams and particulary to <strong>mipel</strong> for communication team, <strong>mipel</strong>, <strong>olivierb</strong> and <strong>xela</strong> for the documentation writing, <strong>babsolune</strong> and <strong>xela</strong> for the development help, <strong>ElenWii</strong> and <strong>babsolune</strong> for the graphics, <strong>mipel</strong> and <strong>olivierb</strong> for the moderation of the community and <strong>janus57</strong> for support in development and community help on the forum.</p>
			<h2>Other projects</h2>
			<p>PHPBoost uses different tools allowing it to enlarge its features panel enough implying the development time rising. Most of these tools under GNU/GPL license come from <a href="https://github.com/">Github</a>.</p>
			<ul>
				<li><a href="https://notepad-plus-plus.org">Notepad++</a>, <a href="https://atom.io/">Atom</a>, <a href="https://fr.netbeans.org/">NetBeans</a> and <a href="https://www.sublimetext.com">Sublime Text</a>: Very powerful text editors used for the whole development, thanks a lot!</li>
				<li><a href="https://github.com/chamilo/pclzip">PCLZIP</a> by <a href="https://www.phpconcept.net/">PHPConcept</a>: PHP library which manage work with zip files.</li>
				<li><a href="https://github.com/daanforever/phpmathpublisher">PHPMathPublisher</a>: Functions which permit us to interpret LaTeX language and export it to pictures viewable by a web browser.</li>
				<li><a href="https://www.tiny.cloud/">TinyMCE</a>: TinyMCE is a <abbr aria-label="What You See Is What You Get">WYSIWYG</abbr> editor which allows users to see their text formatting in real time.</li>
				<li><a href="https://github.com/GeSHi/geshi-1.0">GeSHi</a>: Generic Syntax Highlighters used to highlight the source code of many programming languages.</li>
				<li><a href="https://jquery.com">jQuery</a>: Javascript and <abbr aria-label="Asynchronous Javascript And XML">AJAX</abbr> Framework</li>
				<li><a href="https://fontawesome.com/?from=io">Font Awesome</a>: icons librairy</li>
			</ul>
		</div>
	</fieldset>
	<fieldset>
		<legend>Make a donation</legend>
		<div class="fielset-inset">
			If you want to support PHPBoost financially you can donate via paypal :

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
				<li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, developer retired</li>
				<li><strong>Julien BRISWALTER</strong> <em>(alias j1.seth)</em>, developer</li>
			</ul>
		</div>
	</fieldset>
';
$lang['install.site.index']  = 'Go to the website';
$lang['install.admin.index'] = 'Go to the administration panel';
?>
