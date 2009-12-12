<?php
/*##################################################
 *                          PHPBoostError.class.php
 *                            -------------------
 *   begin                : December 9, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


class PHPBoostErrors
{
	public static function module_not_installed()
	{
        $lang = LangLoader::get('errors');
		$controller = new UserErrorController(
		$lang['e_uninstalled_module'],
		$lang['e_uninstalled_module'],
		UserErrorController::NOTICE);
		return $controller;
	}

	public static function member_banned()
	{
		// TODO
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController('TODO', 'TODO');
        return $controller;
	}

	public static function unexisting_member()
	{
		// TODO
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController('TODO', 'TODO');
        return $controller;
	}

	public static function member_not_enabled()
	{
		// TODO
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController('TODO', 'TODO');
        return $controller;
	}

	public static function flood()
	{
		// TODO
        $lang = LangLoader::get('errors');
		$controller = new UserErrorController('TODO', 'TODO');
        return $controller;
	}
}