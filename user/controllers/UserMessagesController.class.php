<?php
/*##################################################
 *                       UserMessagesController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

class UserMessagesController extends AbstractController
{
	private $lang;
	private $tpl;
	private $user;

	public function execute(HTTPRequest $request)
	{
		$this->init();

		$user_id = $request->get_getint('user_id', 0);
		if (empty($user_id))
		{
			AppContext::get_response()->redirect(UserUrlBuilder::users()->absolute());
		}
		else if (!UserService::user_exists_by_id($user_id))
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		$this->build_form($user_id);
		return $this->build_response($this->tpl, $user_id);
	}

	private function init()
	{
		$this->user = AppContext::get_current_user();
		$this->tpl = new FileTemplate('user/UserMessagesController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form($user_id)
	{
		$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
		foreach ($modules as $module)
		{
			$img = $module->get_messages_list_link_img();
			$this->tpl->assign_block_vars('available_modules_msg', array(
				'NAME_USER_MSG' => $module->get_messages_list_link_name(),
				'IMG_USER_MSG' => $img,
				'C_IMG_USER_MSG' => !empty($img),
				'U_LINK_USER_MSG' => $module->get_messages_list_url($user_id)
			));
		}
		
		$this->tpl->put_all(array(
			'L_MESSAGES' => $this->lang['messages'],
		));
	}

	private function build_response(View $view, $user_id)
	{
		$title = $this->lang['messages'];
		$response = new UserDisplayResponse();
		$response->set_page_title($title);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($title, UserUrlBuilder::messages($user_id)->absolute());
		return $response->display($view);
	}
}
?>