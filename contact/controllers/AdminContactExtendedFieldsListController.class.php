<?php
/*##################################################
 *                       AdminContactExtendedFieldsListController.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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

class AdminContactExtendedFieldsListController extends AdminController
{
	private $lang;
	
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->update_fields($request);
		
		$this->init();

		$extended_field = ContactExtendedFieldsCache::load()->get_extended_fields();

		foreach ($extended_field as $id => $row)
		{
			$this->view->assign_block_vars('list_extended_fields', array(
				'ID' => $row['id'],
				'NAME' => $row['name'],
				'L_REQUIRED' => $row['required'] ? $this->lang['field.yes'] : $this->lang['field.no'],
				'EDIT_LINK' => ContactUrlBuilder::edit_field($id)->absolute(),
				'DISPLAY' => $row['display'],
				'FREEZE' => $row['freeze']
			));
		}
		
		$main_lang = LangLoader::get('main');
		$this->view->put_all(array(
			'DELETE_LINK' => ContactUrlBuilder::delete_field()->absolute(),
			'L_MANAGEMENT_EXTENDED_FIELDS' => LangLoader::get_message('contact_config.fields.manage', 'contact_common', 'contact'),
			'L_NAME' => $this->lang['field.name'],
			'L_POSITION' => $this->lang['field.position'],
			'L_REQUIRED' => $this->lang['field.required'],
			'L_DISPLAY' => $main_lang['display'],
			'L_ALERT_DELETE_FIELD' => $this->lang['field.delete_field'],
			'L_VALID' => $main_lang['update'],
			'L_UPDATE' => $main_lang['update'],
			'L_DELETE' => $main_lang['delete'],
			'L_PROCESSED_OR_NOT' => $main_lang['display']
		));

		return new AdminContactDisplayResponse($this->view, LangLoader::get_message('contact_config.fields.manage', 'contact_common', 'contact'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-extended-fields-common');
		$this->view = new FileTemplate('contact/AdminContactExtendedFieldsListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function update_fields($request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
		}
		$this->change_display($request);
		ContactExtendedFieldsCache::invalidate();
	}
	
	private function change_display($request)
	{
		$id = $request->get_value('id', 0);
		$display = $request->get_bool('display', true);
		if ($id !== 0)
		{
			PersistenceContext::get_querier()->inject(
				"UPDATE " . ContactSetup::$contact_extended_fields_table . " SET 
				display = :display
				WHERE id = :id"
				, array(
					'display' => (int)$display,
					'id' => $id,
			));
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
					"UPDATE " . ContactSetup::$contact_extended_fields_table . " SET 
					position = :position
					WHERE id = :id"
					, array(
						'position' => $position,
						'id' => $id,
				));
			}
		}
	}
}
?>