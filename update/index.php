<?php
/*##################################################
 *                           index.php
 *                            -------------------
 *   begin                : February 27, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

if (version_compare(phpversion(), '5.1.2', '<') == -1)
{
	die('<h1>Impossible to update PHPBoost</h1><p>At least PHP 5.1.2 is needed but your current PHP version is ' . phpversion() . '</p>');
}

define('PATH_TO_ROOT', '..');
require_once PATH_TO_ROOT . '/update/environment/UpdateEnvironment.class.php';
UpdateEnvironment::load_imports();
UpdateEnvironment::init();

$permissions = PHPBoostFoldersPermissions::get_permissions();
if (!$permissions['/cache']->is_writable() || !$permissions['/cache/tpl']->is_writable())
{
	die('Cache folder is not writable (/cache)');
}

$url_controller_mappers = array(
	new UrlControllerMapper('UpdateIntroductionController', '`^(?:/introduction)?/?$`'),
	new UrlControllerMapper('UpdateServerConfigController', '`^/server/?$`'),
	new UrlControllerMapper('UpdateDBConfigController', '`^/database/?$`'),
	new UrlControllerMapper('UpdateDBConfigCheckController', '`^/database/check/?$`'),
	new UrlControllerMapper('UpdateVersionExecuteController', '`^/execute/?$`'),
	new UrlControllerMapper('UpdateConfigWebsiteController', '`^/config/?$`'),
	new UrlControllerMapper('UpdateFinishController', '`^/finish/?$`')
);
DispatchManager::dispatch($url_controller_mappers);

?>