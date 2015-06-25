<?php
/*##################################################
 *                       UserMessagesController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$user_id = $request->get_getint('user_id', 0);

		if (empty($user_id))
		{
			AppContext::get_response()->redirect(UserUrlBuilder::home());
		}
		
		try {
			$this->user = UserService::get_user($user_id);
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}
		
		$this->build_form();
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->user = AppContext::get_current_user();
		$this->tpl = new FileTemplate('user/UserMessagesController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form()
	{
		$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
		foreach ($modules as $module)
		{
			$img = $module->get_messages_list_link_img();
			$this->tpl->assign_block_vars('available_modules_msg', array(
				'NAME_USER_MSG' => $module->get_messages_list_link_name(),
				'IMG_USER_MSG' => $img,
				'C_IMG_USER_MSG' => !empty($img),
				'U_LINK_USER_MSG' => $module->get_messages_list_url($this->user->get_id())
			));
		}
		
		$this->tpl->put_all(array(
			'L_MESSAGES' => $this->lang['messages'],
		));
	}

	private function build_response(View $view)
	{
		$title = $this->lang['messages'];
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($title);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($title, UserUrlBuilder::messages($this->user->get_id())->rel());
		
		return $response;
	}
}
?>