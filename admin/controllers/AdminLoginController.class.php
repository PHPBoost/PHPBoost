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
		if ($request->has_postparameter('connect'))
		{
			$this->authenticate($request);
		}
		return $this->build_form($request);
	}

	private function authenticate(HTTPRequest $request)
	{
		$username = $request->get_string('login', '');
		$password = $request->get_string('password', '');
		$autoconnect = $request->get_bool('autoconnect', false);
		$authentication = new PHPBoostAuthentication($username, $password);
		if ($authentication->authenticate($autoconnect))
		{
			AppContext::get_response()->redirect('/admin');
		}
	}

	private function build_form(HTTPRequest $request)
	{
		$view = new FileTemplate('admin/AdminLoginController.tpl');
		$lang = LangLoader::get('admin-login');
		$view->put('POST_URL', DispatchManager::get_url('/admin/index.php', '/login')->absolute());
		$flood = $request->get_getint('flood', 5);
		if ($flood > 0)
		{
			$errormsg = ($flood > 0) ? StringVars::replace_vars($lang['flood_block'], array('remaining_tries' => 5 - $flood)) :
				$lang['flood_max'];
			$view->put('ERROR', $errormsg);
			$view->put('C_UNLOCK', $flood == 5);
		}
		return new AdminNodisplayResponse($view);
	}
}
?>