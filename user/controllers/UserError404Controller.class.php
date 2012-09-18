<?php
/*##################################################
 *                       UserError404Controller.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserError404Controller extends UserErrorController
{
	public function __construct()
	{
		$error = LangLoader::get_message('error', 'errors');
		$unexist_page = LangLoader::get_message('e_unexist_page', 'errors');
		$message = '<strong>' . $error . ' 404</strong>' . '<br /><br />' . $unexist_page;
		parent::__construct($error. ' 404', $message, self::WARNING);
	}
	
	public function execute(HTTPRequestCustom $request)
	{
		AdminError404Service::register_404();
		return parent::execute($request);
	}
}
?>