<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 08 05
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      English                     #
####################################################

// General variables
$lang['update.title']                  = 'PHPBoost Update';
$lang['update.steps.list']             = 'Steps list';
$lang['update.step.list.introduction'] = 'Preamble';
$lang['update.step.list.license']      = 'License';
$lang['update.step.list.server']       = 'Server Configuration';
$lang['update.step.list.database']     = 'Database settings';
$lang['update.step.list.website']      = 'General settings';
$lang['update.step.list.execute']      = 'Update';
$lang['update.step.list.end']          = 'End of update';
$lang['update.language.change']        = 'Change language';
$lang['update.change']                 = 'Change';

$lang['update.step.previous'] = 'Previous step';
$lang['update.step.next']     = 'Next step';

$lang['update.phpboost.link']   = 'Link to PHPBoost CMS official website';
$lang['update.phpboost.rights'] = '';
$lang['update.phpboost.logo']   = 'PHPBoost logo';

// Introduction
$lang['update.step.introduction.title']   = 'Update of PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' to PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' ' . Environment::get_phpboost_name_version();
$lang['update.step.introduction.message'] = 'Welcome to update wizard';
$lang['update.step.introduction.clue']    = '
    <p>Thank you to have trusted PHPBoost to build your website.</p>
    <p>To install PHPBoost you need to have some informations about your hosting which would be provided by your hoster. The installation is absolutely automatic, il should take only a few minutes. Click on the right arrow to start the installation process.</p>
';
$lang['update.step.introduction.maintenance_notice'] = '<div class="message-helper bgc notice">Your site will automatically be placed in maintenance. Consider disabling maintenance when you have verified that everything works properly.</div>';
$lang['update.step.introduction.team_signature']     = '<p>Best regards, the PHPBoost team.</p>';

// Server configuration
$lang['update.step.server.title']       = 'Looking up server configuration...';
$lang['update.step.server.description'] = '
        <p>Before starting update, we need to establish the compatibility between PHPBoost and your server. If you have any problems, feel free to ask your questions in our <a href="https://www.phpboost.com/forum/">Support Forums</a>.</p>
        <div class="message-helper bgc notice">It is very important that each required fields are checked, otherwise you might have trouble using the software.</div>
';
$lang['update.php.version']            = 'PHP version';
$lang['update.php.version.check']      = 'PHP higher than :min_php_version';
$lang['update.php.version.check.clue'] = '<span style="font-weight: bold;color: red;">Required:</span> To run PHPBoost, your server must run PHP :min_php_version or higher. Below that, you might have issues with some modules.';

$lang['update.php.extensions']                      = 'Extensions';
$lang['update.php.extensions.check']                = 'The activation of these extensions will provide additional features and are essential to the proper functioning of PHPBoost.';
$lang['update.php.extensions.check.gd']             = 'GD extension';
$lang['update.php.extensions.check.gd.clue']        = 'Extension used to generate pictures such as Robot Protection, Statistics Graphics and much more.';
$lang['update.php.extensions.check.curl']           = 'Curl extension';
$lang['update.php.extensions.check.curl.clue']      = 'Extension used to get distant elements. Mandatory to enable external authentication for instance.';
$lang['update.php.extensions.check.zip']            = 'Zip extension';
$lang['update.php.extensions.check.zip.clue']       = 'Extension used to manage compressed files.';
$lang['update.php.extensions.check.mbstring']       = 'MBstring extension';
$lang['update.php.extensions.check.mbstring.clue']  = 'Extension used for UTF-8 characters management. Mandatory to have a working website.';
$lang['update.php.extensions.check.mbstring.error'] = 'PHP <b>mbstring</b> extension is not activated. Please activate it or contact your web hosting company before proceeding with the installation.';

$lang['update.server.url.rewriting']      = 'URL Rewriting';
$lang['update.server.url.rewriting.clue'] = 'Optional<br />Not only does it rewrite URLs, but it helps a lot with search engine robots.';

$lang['update.folders.chmod']         = 'Directories permissions';
$lang['update.folders.chmod.check']   = '<span style="font-weight: bold;color: red;">Required:</span> PHPBoost needs to change permissions of several directories to make them writable. If your hosting company allows it, it will be done automatically. However, you might need to do it by yourself to make the installation work. If you don\'t know how to change permissions of a directory, you can find help on your host website.';
$lang['update.folders.chmod.refresh'] = 'Double-check directories permissions';
$lang['update.folders.chmod.error']   = 'Some directories seem to be missing and/or not writable. You need to create the missing directories or make the directories writable.';
$lang['update.folder.exists']         = 'Found';
$lang['update.folder.non.existent']   = 'Not found';
$lang['update.folder.is.writable']    = 'Writable';
$lang['update.folder.not.writable']   = 'Not writable';

//Base de données
$lang['update.step.database.config']      = 'Database settings';
$lang['update.db.parameters.config']      = 'Database connection parameters';
$lang['update.db.parameters.config.clue'] = '<p>This step will generate a configuration file that contains the database settings and database tables will also be created at the same time. If you don\'t know your database settings, you might be able to find them in your host panel or need to ask for them from your host support.</p>';
$lang['update.dbms.parameters']           = 'DBMS connection parameters';
$lang['update.dbms.host']                 = 'Database server hostname or DSN:';
$lang['update.dbms.host.clue']            = 'Database managing system server URL, often <em>localhost</em>';
$lang['update.dbms.port']                 = 'Database port';
$lang['update.dbms.port.clue']            = 'Database server port, <em>3306</em> most of the time.';
$lang['update.dbms.login']                = 'Database username';
$lang['update.dbms.login.clue']           = 'Provided by your host';
$lang['update.dbms.password']             = 'Database password';
$lang['update.dbms.password.clue']        = 'Provided by your host';

$lang['update.schema.properties']        = 'Database properties';
$lang['update.schema']                   = 'Database name';
$lang['update.schema.clue']              = 'Provided by your host. If that database doesn\'t exist, PHPBoost will try to create it.';
$lang['update.schema.table.prefix']      = 'Prefix for tables in database';
$lang['update.schema.table.prefix.clue'] = 'Default value is <em>phpboost_</em>. You will need to change this value if you want to install PHPBoost several times in the same database.';

$lang['update.db.config.check']        = 'Try to establish database connection!';
$lang['update.db.connection.success']  = 'The connection to the database has been established successfully. You can proceed to the next step.';
$lang['update.db.connection.error']    = 'Could not connect to the database. Please check the settings you have entered.';
$lang['update.db.creation.error']      = 'The database name you entered doesn\'t exist.';
$lang['update.db.unknown.error']       = 'An unknown error has occured.';
$lang['update.db.required.host']       = 'You must enter database hostname!';
$lang['update.db.required.port']       = 'You must enter database port!';
$lang['update.db.required.login']      = 'You must enter database username!';
$lang['update.db.required.schema']     = 'You must enter database name!';
$lang['update.db.unexisting.database'] = 'The database doesn\'t exists. Check parameters';
$lang['phpboost.not.installed']        = 'Unexisting installation';
$lang['phpboost.not.installed.clue']   = '
        <p>The database on which you want to update PHPBoost contains no installation.</p>
        <p> Please check that you have specified the correct prefix and good database.</p>
';

//Execute update
$lang['update.step.execute.title']              = 'Execute update';
$lang['update.step.execute.update.in.progress'] = 'Update in progress';
$lang['update.step.execute.message']            = 'Site update';

$lang['update.step.execute.clue'] = '
    This step will convert your site PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' to ' . UpdateServices::NEW_KERNEL_VERSION . ' PHPBoost.
    <br /><br />
    Be careful, this step is irreversible, as a precaution please backup your data first!
';
$lang['update.step.execute.incompatible.modules'] = '
    The following modules will be disabled because they are not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. ': :modules.
    <br />To reactivate them, download their compatible version from the <a href="https://www.phpboost.com/download">PHPBoost</a> website if it exists and go to the module update page of your site to update them (they will then be reactivated automatically).
';
$lang['update.step.execute.incompatible.module'] = '
    The module :modules will be disabled because it is not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. '.
    <br />To reactivate it, download its compatible version from the <a href="https://www.phpboost.com/download">PHPBoost</a> website if it exists and go to the module update page of your site to update it (it will then be reactivated automatically).
';
$lang['update.step.execute.incompatible.module.default'] = '
    <br /><br /><b>:old_default</b> module placed at site startup will be replaced by <b>:new_default</b> module. When you install the new compatible version of <b>:old_default</b> module, remember to reconfigure your site\'s start page in the general configuration.
';
$lang['update.step.execute.incompatible.themes'] = '
    The following themes will be disabled because they are not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. ': :themes.
    <br />To reactivate them, download their compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the themes on your site FTP in templates folder and then go to the themes management page of your site to reactivate them.
';
$lang['update.step.execute.incompatible.theme'] = '
    The theme :themes will be disabled because it is not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. '.
    <br /> To reactivate it, download its compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the theme on you site FTP in templates folder then go to the theme management page of your site to reactivate it.
';
$lang['update.step.execute.incompatible.theme.default'] = '
    <br /><br /><b>:old_default</b> default theme for the site will be replaced by <b>:new_default</b> theme. When you have installed the new compatible version of <b>:old_default</b> theme, remember to reconfigure your site\'s default theme in the general configuration (if that was the only theme then uninstall <b>:new_default</b> theme so that users of the site have this theme active).
';
$lang['update.step.execute.incompatible.langs'] = '
    The following langs will be disabled because they are not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. ': :langs.
    <br />To reactivate them, download their compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the langs on your site FTP in lang folder and then go to the langs management page of your site to reactivate them.
';
$lang['update.step.execute.incompatible.lang'] = '
    The lang :langs will be disabled because it is not compatible with PHPBoost '. UpdateServices::NEW_KERNEL_VERSION. '.
    <br /> To reactivate it, download its compatible version from <a href="https://www.phpboost.com/download">PHPBoost</a> if it exists, update the lang on you site FTP in lang folder then go to the lang management page of your site to reactivate it.
';
$lang['update.step.execute.incompatible.lang.default'] = '
    <br /><br /><b>:old_default</b> default lang for the site will be replaced by <b>:new_default</b> lang. When you have installed the new compatible version of <b>:old_default</b> lang, remember to reconfigure your site\'s default lang in the general configuration (if that was the only lang then uninstall <b>:new_default</b> lang so that users of the site have this lang active).
';

//Finish update
$lang['update.tab.congrats'] = 'Congratulations';
$lang['update.tab.thanks']   = 'Thanks';
$lang['update.tab.projects'] = 'Projects';
$lang['update.tab.credits']  = 'Credits';

$lang['update.tab.content.congrats'] = '
        <div>
            <h2>PHPBoost is now updated and ready to run!</h2>
            <div class="fielset-inset">
                <p class="message-helper bgc success">The update of PHPBoost has been powered successfully. The PHPBoost Team thanks you for using its software and is proud to count you among its users.</p>
                <p class="message-helper bgc warning">Please download the <a href="' . GeneralConfig::load()->get_complete_site_url() . '/cache/update.log" download>log file</a> of your update, it could be required on PHPBoost forum if you ask for support.</p>
                <p>Keep yourself informed about the evolution of PHPBoost by visiting our website, <a href="https://www.phpboost.com">www.phpboost.com</a>. You will be warned in the administration panel when updates are available. We strongly recommend to keep your website up to date so you can take advantage of the latest features and correct any flaws or errors.</p>
                <p class="message-helper bgc warning">For security reasons we also recommand you to delete the <b>update</b> folder and all its contents, hackers could manage to run the update script and you could lose data! An option will be offered once connected to the site to perform this deletion.</p>
                <p>Don\'t forget the <a href="https://www.phpboost.com/wiki/">documentation</a> which will help you for using PHPBoost and the <a href="https://www.phpboost.com/faq/"><acronym aria-label="Frequently Asked Questions">FAQ</acronym></a>.</p>
                <p>If you have any problem please go to the <a href="https://www.phpboost.com/forum/">support forum of PHPBoost</a>.</p>
            </div>
        </div>
';

$lang['update.tab.content.thanks'] = '
        <div>
            <h2>Thanks</h2>
            <div class="fielset-inset">
                <h2>Members</h2>
                <p>Thanks to all the members of the community who cheer us on daily and contribute to the software quality by reporting bugs and suggestion improvements, which allows to lead to a stable and powerful CMS.</p>
                <p>Thanks to the members of our teams and particulary to <strong>mipel</strong> for communication team, <strong>mipel</strong>, <strong>olivierb</strong> and <strong>xela</strong> for the documentation writing, <strong>babsolune</strong> and <strong>xela</strong> for the development help, <strong>ElenWii</strong> and <strong>babsolune</strong> for the graphics, <strong>mipel</strong> and <strong>olivierb</strong> for the moderation of the community and <strong>janus57</strong> for support in development and community help on the forum.</p>
            </div>
        </div>
';

$lang['update.tab.content.projects'] = '
            <div>
                <h2>Other projects</h2>
                <div>
                    <p>PHPBoost uses different tools allowing it to enlarge its features panel enough implying the development time rising. Most of these tools under GNU/GPL license come from <a href="https://github.com/">Github</a>.</p>
                    <ul>
                        <li><a href="https://notepad-plus-plus.org">Notepad++</a>, <a href="https://atom.io/">Atom</a>, <a href="https://fr.netbeans.org/">NetBeans</a> and <a href="https://www.sublimetext.com">Sublime Text</a>: Very powerful text editors used for the whole development, thanks a lot!</li>
                        <li><a href="https://github.com/daanforever/phpmathpublisher">PHPMathPublisher</a>: Functions which permit us to interpret LaTeX language and export it to pictures viewable by a web browser.</li>
                        <li><a href="https://www.tiny.cloud/">TinyMCE</a>: TinyMCE is a <abbr aria-label="What You See Is What You Get">WYSIWYG</abbr> editor which allows users to see their text formatting in real time.</li>
                        <li><a href="https://github.com/PrismJS/prism">Prism</a>: Generic Syntax Highlighters used to highlight the source code of many programming languages.</li>
                        <li><a href="https://jquery.com">jQuery</a>: Javascript and <abbr aria-label="Asynchronous Javascript And XML">AJAX</abbr> Framework</li>
                        <li><a href="https://fontawesome.com/?from=io">Font Awesome</a>: icons librairy</li>
                    </ul>
                </div>
            </div>
';

$lang['update.tab.content.credits'] = '
    <div>
        <h2>Credits</h2>
        <div class="fielset-inset">
            <ul>
                <li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, founder of PHPBoost project and developer retired</li>
                <li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, developer retired</li>
                <li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, developer retired</li>
                <li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, developer retired</li>
                <li><strong>Julien BRISWALTER</strong> <em>(alias j1.seth)</em>, developer</li>
            </ul>
        </div>
    </div>
';

$lang['update.donate'] = '
    <div>
        <h2>Make a donation</h2>
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
    </div>
';

$lang['update.site.index']  = 'Go to the website';
$lang['update.admin.index'] = 'Go to the administration panel';
?>
