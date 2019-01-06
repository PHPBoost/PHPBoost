<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 30
 * @since   	PHPBoost 4.1 - 2015 07 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['advises'] = 'Advises';
$lang['advises.modules_management'] = '<a title="Disable or uninstall modules" href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Disable or uninstall modules</a> you don\'t you to free ressources on the website.';
$lang['advises.check_modules_authorizations'] = 'Check the authorizations of all your modules and menus before opening your website to avoit guest or unauthorized users accessing protected areas.';
$lang['advises.disable_debug_mode'] = '<a title="Disable debug mode" href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Disable debug mode</a> to hide errors to users (the errors are loggued in the <a href="' . AdminErrorsUrlBuilder::logged_errors()->rel() . '">Loggued errors</a>).';
$lang['advises.disable_maintenance'] = '<a title="Disable maintenance" href="' . AdminMaintainUrlBuilder::maintain()->rel() . '">Disable maintenance</a> to allow the users to display your website.';
$lang['advises.enable_url_rewriting'] = '<a title="Enable URL rewriting" href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable URL rewriting</a> to have more readable urls (usefull for SEO).';
$lang['advises.enable_output_gz'] = '<a title="Enable Output pages compression" href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable Output pages compression</a> to gain performance.';
$lang['advises.enable_apcu_cache'] = '<a title="Enable APCu cache" href="' . AdminCacheUrlBuilder::configuration()->rel() . '">Enable APCu cache</a> to allow loading the cache in RAM on the server and not on the hard-drive and win a performance advantage.';
$lang['advises.upgrade_php_version'] = 'Upgrade your PHP version in 5.6 (last stable release) if your host allows it.';
$lang['advises.save_database'] = 'Save your database frequently.';
$lang['advises.optimize_database_tables'] = '<a title="Enable auto tables optimization" href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable auto tables optimization</a> or optimize occasionally your tables in the module <strong>Database</strong> (if it is installed) or in your database management tool to recover the wasted base space.';
$lang['advises.password_security'] = 'Increase strength and length of passwords in <a title="Increase strength and length of passwords" href="' . AdminMembersUrlBuilder::configuration()->rel() . '">members configuration</a> to strengthen security.';
?>
