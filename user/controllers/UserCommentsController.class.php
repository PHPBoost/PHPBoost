<?php
/*##################################################
 *                       UserCommentsController.class.php
 *                            -------------------
 *   begin                : February 20, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class UserCommentsController extends AbstractController
{
	private $user;
	private $tpl;
	private $lang;
	
	public function execute(HTTPRequest $request)
	{
		$this->init();
		try {
			$this->user = UserService::get_user('WHERE user_id=:user_id', array('user_id' => $request->get_getint('user_id', 0)));
		} catch (Exception $e) {
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		$this->build_view();
		return $this->build_response();
	}
	
	private function init()
	{
		$this->tpl = new FileTemplate('user/UserCommentsController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_view()
	{
		
	}
	
	private function build_response()
	{
		
	}
}
?>