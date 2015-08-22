<?php
/*##################################################
 *                       AdminExtendedFieldsMemberListController.class.php
 *                            -------------------
 *   begin                : December 17, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

class AdminExtendedFieldsMemberListController extends AdminController
{
	private $lang;
	
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->update_fields($request);
		
		$extended_field = ExtendedFieldsCache::load()->get_extended_fields();
		
		$fields_number = 0;
		foreach ($extended_field as $id => $row)
		{
			if ($row['name'] !== 'last_view_forum')
			{
				$this->view->assign_block_vars('list_extended_fields', array(
					'C_REQUIRED' => $row['required'],
					'C_DISPLAY' => $row['display'],
					'C_FREEZE' => $row['freeze'],
					'ID' => $row['id'],
					'NAME' => $row['name'],
					'U_EDIT' => AdminExtendedFieldsUrlBuilder::edit($row['id'])->rel()
				));
				$fields_number++;
			}
		}
		
		$this->view->put_all(array(
			'C_FIELDS' => $fields_number,
			'C_MORE_THAN_ONE_FIELD' => $fields_number > 1
		));
		
		return new AdminExtendedFieldsDisplayResponse($this->view, $this->lang['extended-fields-management']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-user-common');
		$this->view = new FileTemplate('admin/member/AdminExtendedFieldsMemberlistController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function update_fields($request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			ExtendedFieldsCache::invalidate();
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.position.update', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
	}
	
	private function update_position($request)
	{
		$fields_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($fields_list as $position => $tree)
		{
			PersistenceContext::get_querier()->inject(
				"UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET 
				position = :position
				WHERE id = :id"
				, array(
					'position' => $position,
					'id' => $tree->id,
			));
		}
	}
}
?>