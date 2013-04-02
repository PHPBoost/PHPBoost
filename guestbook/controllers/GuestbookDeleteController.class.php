<?php
/*##################################################
 *                      GuestbookDeleteController.class.php
 *                            -------------------
 *   begin                : November 30, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

/**
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 * @desc Delete controller of the guestbook module
 */
class GuestbookDeleteController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_post_protect();
		
		//Authorization check
		if (!AppContext::get_current_user()->check_auth(GuestbookConfig::load()->get_authorizations(), GuestbookConfig::GUESTBOOK_MODO_AUTH_BIT))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		$id = $request->get_int('id', null);
		$page = $request->get_int('page', 1);
		
		if (!empty($id))
		{
			GuestbookService::delete("WHERE id=:id", array('id' => $id));
			
			AppContext::get_response()->redirect(GuestbookUrlBuilder::home($page)->absolute());
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('guestbook.error.e_unexist_message', 'guestbook_common', 'guestbook'));
			DispatchManager::redirect($controller);
		}
	}
}
?>
