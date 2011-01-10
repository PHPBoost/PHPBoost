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
		$this->init();
	
		$extended_field = ExtendedFieldsCache::load()->get_extended_fields();

		$min_cat = PersistenceContext::get_sql()->query("SELECT MIN(position) FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " WHERE display = 1");
		$max_cat = PersistenceContext::get_sql()->query("SELECT MAX(position) FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " WHERE display = 1");

		foreach ($extended_field as $id => $row)
		{
			if ($row['display'] == 1)
			{
				$top_link = $min_cat != $row['position'] ? '<a href="'. DispatchManager::get_url('/admin/member', '/extended-fields/position/'.$row['id'].'/top/')->absolute() .'" title="">
					<img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/admin/up.png" alt="" title="" /></a>' : '';
				$bottom_link = $max_cat != $row['position'] ? '<a href="'. DispatchManager::get_url('/admin/member', '/extended-fields/position/'.$row['id'].'/bottom/')->absolute() .'" title="">
					<img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/admin/down.png" alt="" title="" /></a>' : '';
			
				$this->view->assign_block_vars('list_extended_fields', array(
					'ID' => $row['id'],
					'NAME' => $row['name'],
					'L_REQUIRED' => $row['required'] ? $this->lang['field.yes'] : $this->lang['field.no'],
					'L_DISPLAY' => $row['display'] ? $this->lang['field.yes'] : $this->lang['field.no'],
					'TOP' => $top_link,
					'BOTTOM' => $bottom_link,
					'DELETE_LINK' => DispatchManager::get_url('/admin/member', '/extended-fields/'.$row['id'].'/delete/?token=' . AppContext::get_session()->get_token())->absolute(),
					'EDIT_LINK' => DispatchManager::get_url('/admin/member', '/extended-fields/'.$row['id'].'/edit/')->absolute(),
				));
			}
		}
		
		$this->view->put_all(array(
			'L_MANAGEMENT_EXTENDED_FIELDS' => $this->lang['extended-fields-management'],
			'L_NAME' => $this->lang['field.name'],
			'L_POSITION' => $this->lang['field.position'],
			'L_REQUIRED' => $this->lang['field.required'],
			'L_DISPLAY' => LangLoader::get_message('display', 'main'),
			'L_ALERT_DELETE_FIELD' => $this->lang['field.delete_field']
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
}

?>