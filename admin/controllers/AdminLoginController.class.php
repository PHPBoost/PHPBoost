<?php
/*##################################################
 *                           AdminLoginController.class.php
 *                            -------------------
 *   begin                : December 14 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class AdminLoginController extends AbstractController
{
	public function execute(HTTPRequest $request)
	{
		$view = new FileTemplate('admin/AdminLoginController.tpl');
		$lang = LangLoader::get_class(__CLASS__);
		$view->add_lang($lang);

		$flood = $request->get_getint('flood', 5);
		if ($flood > 0)
		{
			$view->put_all(array(
				'ERROR' => (($flood > 0) ? StringVars::replace_vars($lang['flood_block'],
			array('remaining_tries' => 5 - $flood)) : $lang['flood_max']),
				'C_UNLOCK' => $flood == 5
			));
		}

		return new AdminNodisplayResponse($view);
	}
}
?>