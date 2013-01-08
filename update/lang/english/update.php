<?php
/*##################################################
 *                                update.php
 *                            -------------------
 *   begin                : August 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
#                      French                      #
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
	'phpboost.rights' => '',

//Introduction
	'step.welcome.title' => 'Preamble',
	'step.welcome.message' => 'Welcome to PHPBoost update wizard',
    'step.introduction.explanation' => '<p>Thank you to have trusted PHPBoost to build your website.<br /><br />
To install PHPBoost you need to have some informations about your hosting which would be provided by your hoster. The installation is absolutely automatic, il should take only a few minutes. Click on the right arrow to start the installation process.<br /><br />
Cordially, the PHPBoost Team.</p>',

//Configuration du serveur
	'step.server.title' => 'Looking up server configuration...',
	'step.server.explanation' => '<p>Before starting update, we need to establish the compatibility between PHPBoost and your server. If you have any problems, feel free to ask your questions in our <a href="http://www.phpboost.org/forum/index.php">Support Forums</a>.</p>
<div class="notice">It is very important that each required fields are checked, otherwise you might have trouble using the software.</div>',
	'php.version' => 'PHP version',
	'php.version.check' => 'PHP higher than :min_php_version',
	'php.version.check.explanation' => '<span style="font-weight:bold;color:red;">Required:</span> To run PHPBoost, your server must run PHP :min_php_version or higher. Below that, you might have issues with some modules.',
	'php.extensions' => 'Extensions',
	'php.extensions.check' => '<span style="font-weight:bold;">Optional :</span> The activation of these extensions will provide additional features but it is not essential to its operation.',
	'php.extensions.check.gdLibrary' => 'GD library',
	'php.extensions.check.gdLibrary.explanation' => 'Library used to generate pictures such as Robot Protection, Statistics Graphics and much more.',
	'server.urlRewriting' => 'URL Rewriting',
	'server.urlRewriting.explanation' => 'Not only does it rewrite URLs, but it helps a lot with search engine robots.',
	'folders.chmod' => 'Directories permissions',
	'folders.chmod.check' => '<span style="font-weight:bold;color:red;">Required:</span> PHPBoost needs to change permissions of several directories to make them writable. If your hosting company allows it, it will be done automatically. However, you might need to do it by yourself to make the installation work. If you don\'t know how to change permissions of a directory, you can find help in our <a href="http://www.phpboost.net/wiki/change-the-chmod-of-a-directory" title="PHPBoost documentation : chmod">PHPBoost documentation</a> or on your host website.',
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
	'dbms.paramters' => 'DBMS connection parameters',
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
	'step.execute.title' => 'Execute update',
	'step.execute.message' => 'Site update',
	'step.execute.explanation' => 'This step will convert your site PHPBoost 3.0 to 4.0 PHPBoost.
	<br /><br />
	Warning this step is irreversible, as a precaution please backup your data first !',
	
	'finish.message' => '<fieldset>
                            <legend>PHPBoost is now updated and ready to run !</legend>
                            <p class="success">The update of PHPBoost has been powered successfully. The PHPBoost Team thanks you for using its software and is proud to count you among its users.</p>
                            <p>Keep yourself informed about the evolution of PHPBoost by visiting our website, <a href="http://www.phpboost.org">www.phpboost.org</a>. You will be warned in the administration panel when updates are available. We strongly recommend to keep your website up to date so you can take advantage of the latest features and correct any flaws or errors.</p>
                            <p class="warning">For security reasons we also recommand you to delete the installation folder and all its contents, hackers could manage to run the installation script and you could lose data !</p>
                            <p>Don\'t forget the <a href="http://www.phpboost.org/wiki/">documentation</a> which will help you for using PHPBoost and the <a href="http://www.phpboost.org/faq/faq.php"><acronym title="Frequently Asked Questions">FAQ</acronym></a>.</p>
                            <p>If you have any problem please go to the <a href="http://www.phpboost.org/forum/">support forum of PHPBoost</a>.</p>
                        </fieldset>
                        <fieldset>
                            <legend>Thanks</legend>
                            <h2>Members</h2>
                            <p>Thanks to all the members of the community who cheer us on daily and contribute to the software quality by reporting bugs and suggestion improvements, which allows to lead to a stable and powerful version 4.0.</p>
                            <p>Thanks to the members of our teams and particulary to <strong>soupaloignon</strong> for communication team, <strong>Ptithom</strong>, <strong>aiglobulles</strong>, <strong>55 Escape</strong> and <strong>Micman</strong> for the documentation writing, <strong>Schyzo</strong>, <strong>elenwe</strong> and <strong>alyha</strong> for the graphics, <strong>DaaX</strong>, <strong>Alain91</strong> and <strong>julienseth78</strong> for the modules development and <strong>benflovideo</strong> for the moderation of the community.</p>
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
                                <li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, founder of PHPBoost project and developer retired</li>
                                <li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, developer retired</li>
                                <li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, developer retired</li>
                                <li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, developer</li>
                            </ul>
                        </fieldset>',
	'site.index' => 'Go to the website',
	'admin.index' => 'Go to the administration panel'
);
?>
