<?php
/*##################################################
 *                       AdminContactFieldsListController.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminContactFieldsListController extends AdminModuleController
{
	private $lang;
	private $view;
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->update_fields($request);
		
		$fields_number = 0;
		foreach ($this->config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);
			
			$this->view->assign_block_vars('fields_list', array(
				'C_DELETE' => $field->is_deletable(),
				'C_READONLY' => $field->is_readonly(),
				'C_DISPLAY' => $field->is_displayed(),
				'C_REQUIRED' => $field->is_required(),
				'ID' => $id,
				'NAME' => $field->get_name(),
				'U_EDIT' => ContactUrlBuilder::edit_field($id)->rel()
			));
			$fields_number++;
		}
		
		$this->view->put_all(array(
			'C_FIELDS' => $fields_number,
			'C_MORE_THAN_ONE_FIELD' => $fields_number > 1
		));
		
		return new AdminContactDisplayResponse($this->view, LangLoader::get_message('admin.fields.manage.page_title', 'common', 'contact'));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-user-common');
		$this->view = new FileTemplate('contact/AdminContactFieldsListController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = ContactConfig::load();
	}
	
	private function update_fields(HTTPRequestCustom $request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.position.update', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
	}
	
	private function update_position(HTTPRequestCustom $request)
	{
		$fields = $this->config->get_fields();
		$sorted_fields = array();
		
		$fields_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($fields_list as $position => $tree)
		{
			$sorted_fields[$position + 1] = $fields[$tree->id];
		}
		$this->config->set_fields($sorted_fields);
		
		ContactConfig::save();
	}
}
?>
