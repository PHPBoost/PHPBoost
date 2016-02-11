<?php
/*##################################################
 *                           admin-server-common.php
 *                            -------------------
 *   begin                : July 8, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
#                     English                       #
 ####################################################
 
$lang['advises'] = 'Advises';
$lang['advises.modules_management'] = '<a href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Disable or uninstall modules</a> you don\'t you to free ressources on the website.';
$lang['advises.check_modules_authorizations'] = 'Check the authorizations of all your modules and menus before opening your website to avoit guest or unauthorized users accessing protected areas.';
$lang['advises.disable_debug_mode'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Disable debug mode</a> to hide errors to users (the errors are loggued in the <a href="' . AdminErrorsUrlBuilder::logged_errors()->rel() . '">Loggued errors</a>).';
$lang['advises.enable_url_rewriting'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable URL rewriting</a> to have more readable urls (usefull for SEO).';
$lang['advises.enable_output_gz'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable Output pages compression</a> to gain performance.';
$lang['advises.enable_apcu_cache'] = '<a href="' . AdminCacheUrlBuilder::configuration()->rel() . '">Enable APCu cache</a> to allow loading the cache in RAM on the server and not on the hard-drive and win a performance advantage.';
$lang['advises.upgrade_php_version'] = 'Upgrade your PHP version in 5.6 (last stable release) if your host allows it.';
$lang['advises.save_database'] = 'Save your database frequently.';
$lang['advises.optimize_database_tables'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Enable auto tables optimization</a> or optimize occasionally your tables in the module <strong>Database</strong> (if it is installed) or in your database management tool to recover the wasted base space.';
$lang['advises.password_security'] = 'Increase strength and length of passwords in <a href="' . AdminMembersUrlBuilder::configuration()->rel() . '">members configuration</a> to strengthen security.';
?>
