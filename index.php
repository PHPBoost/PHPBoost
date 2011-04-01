<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright            : (C) 2007 Régis Viarre
 *   email                : crowkait@phpboost.com
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

define('PATH_TO_ROOT', '.');

require_once PATH_TO_ROOT . '/kernel/framework/core/environment/Environment.class.php';

try
{
	Environment::load_imports();
}
catch (IOException $ex)
{
	if (!file_exists(PATH_TO_ROOT . '/kernel/db/config.php'))
	{
		header('Location:install/index.php');
		exit;
	}
	else
	{
		Debug::fatal($ex);
	}
}

/* DEPRECATED VARS */
$Cache = new Cache();
/* END DEPRECATED */

Environment::init();

//Sinon, c'est que tout a bien marché, on renvoie sur la page de démarrage
$start_page = Environment::get_home_page();

if ($start_page != HOST . DIR . '/index.php' && $start_page != './index.php') //Empêche une boucle de redirection.
{
	AppContext::get_response()->redirect($start_page);
}
else
{
	AppContext::get_response()->redirect(DispatchManager::get_url('/member', '/member')->absolute());
}

?>
