<?php
/*##################################################
 *                       AdminExtendedFieldsMemberListController.class.php
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

class AdminExtendedFieldsMemberListController extends AdminController
{
	private $lang;
	
	private $view;

	public function execute(HTTPRequest $request)
	{
		$this->update_fields($request);
		
		$this->init();

		$extended_field = ExtendedFieldsCache::load()->get_extended_fields();

		foreach ($extended_field as $id => $row)
		{
			$this->view->assign_block_vars('list_extended_fields', array(
				'ID' => $row['id'],
				'NAME' => $row['name'],
				'L_REQUIRED' => $row['required'] ? $this->lang['field.yes'] : $this->lang['field.no'],
				'EDIT_LINK' => DispatchManager::get_url('/admin/member', '/extended-fields/'.$row['id'].'/edit/')->absolute(),
				'DISPLAY' => $row['display'],
				'FREEZE' => $row['freeze']
			));
		}
		
		$this->view->put_all(array(
			'L_MANAGEMENT_EXTENDED_FIELDS' => $this->lang['extended-fields-management'],
			'L_NAME' => $this->lang['field.name'],
			'L_POSITION' => $this->lang['field.position'],
			'L_REQUIRED' => $this->lang['field.required'],
			'L_DISPLAY' => LangLoader::get_message('display', 'main'),
			'L_ALERT_DELETE_FIELD' => $this->lang['field.delete_field'],
			'L_AUTH_READ_PROFILE' => $this->lang['field.read_authorizations'],
			'L_AUTH_READ_EDIT_AND_ADD' => $this->lang['field.actions_authorizations'],
			'L_VALID' => LangLoader::get_message('update', 'main')
		));

		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-extended-fields-common');
		$this->view = new FileTemplate('admin/member/AdminExtendedFieldsMemberlistController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['extended-field']);
		$response->add_link($this->lang['extended-fields-management'], DispatchManager::get_url('/admin/member/index.php', '/extended-fields/list'), '/templates/' . get_utheme() . '/images/admin/extendfield.png');
		$response->add_link($this->lang['extended-field-add'], DispatchManager::get_url('/admin/member/index.php', '/extended-fields/add'), '/templates/' . get_utheme() . '/images/admin/extendfield.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['extended-fields-management']);
		return $response;
	}
	
	private function update_fields($request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
		}
		$this->change_display($request);
	}
	
	private function change_display($request)
	{
		$id = $request->get_value('id', 0);
		$display = $request->get_bool('display', true);
		if ($id !== 0)
		{
			PersistenceContext::get_querier()->inject(
				"UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET 
				display = :display
				WHERE id = :id"
				, array(
					'display' => (int)$display,
					'id' => $id,
			));
			ExtendedFieldsCache::invalidate();
		}
	}
	
	private function update_position($request)
	{
		$value = '&' . $request->get_value('position', array());
		$array = @explode('&lists[]=', $value);
		foreach($array as $position => $id)
		{
			if ($position > 0)
			{
				PersistenceContext::get_querier()->inject(
					"UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET 
					position = :position
					WHERE id = :id"
					, array(
						'position' => $position,
						'id' => $id,
				));
			}
		}
		ExtendedFieldsCache::invalidate();
	}
}

?>