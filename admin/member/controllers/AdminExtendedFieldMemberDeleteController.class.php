<?php
/*##################################################
 *                       AdminExtendedFieldMemberDeleteController.class.php
 *                            -------------------
 *   begin                : December 17, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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

class AdminExtendedFieldMemberDeleteController extends AdminController
{
	private $lang;
	
	public function execute(HTTPRequest $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$extended_field = new ExtendedField();
		$extended_field->set_id($request->get_getint('id'));
		$exist_field = ExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field);
		if ($exist_field)
		{
			ExtendedFieldsService::delete($extended_field);
			$this->redirect();
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-extended-fields-common');
	}
	
	private function redirect()
	{
		$controller = new UserErrorController($this->lang['field.success'], $this->lang['extended-fields-sucess-delete'], UserErrorController::SUCCESS);
		$controller->set_correction_link($this->lang['extended-field'], PATH_TO_ROOT . '/admin/member/index.php?url=/extended-fields/list/');
		DispatchManager::redirect($controller);
	}
}

?>