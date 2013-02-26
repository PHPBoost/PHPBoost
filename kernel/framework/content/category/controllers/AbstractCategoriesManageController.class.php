<?php
/*##################################################
 *                             AbstractCategoriesManageController.class.php
 *                            -------------------
 *   begin                : February 11, 2013
 *   copyright            : (C) 2013 Kévin MASSY
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

/**
 * @package {@package}
 * @author Kévin MASSY
 * @desc
 */
abstract class AbstractCategoriesManageController extends AdminModuleController
{	
	protected $lang;
	protected $tpl;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response($this->tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('categories-common');
		$this->tpl = new FileTemplate('default/framework/content/categories/manage.tpl');
	}
	
	private function build_view()
	{
		$categories = $this->get_categories_manager()->get_categories_cache()->get_categories();
		
		$this->build_children_view($this->tpl, $categories, Category::ROOT_CATEGORY);
	}
	
	private function build_children_view($template, $categories, $id_parent)
	{
		foreach ($categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_parent && $id != Category::ROOT_CATEGORY)
			{		
				$category_view = new FileTemplate('default/framework/content/categories/category.tpl');
				$category_view->put_all(array(
					'U_EDIT' => $this->get_edit_category_url($id)->absolute(),
					'U_DELETE' => $this->get_delete_category_url($id)->absolute(),
					'ID' => $id,
					'NAME' => $category->get_name()
				));
				
				$this->build_children_view($category_view, $categories, $id);
					
				$template->assign_block_vars('childrens', array('child' => $category_view->render()));
			}
		}
	}
	
	/**
	 * @param View $view
	 * @return Response
	 */
	abstract protected function generate_response(View $view);
	
	/**
	 * @return CategoriesManager
	 */
	abstract protected function get_categories_manager();
	
	/**
	 * @param int $id category id to edit
	 * @return Url
	 */
	abstract protected function get_edit_category_url($id);
	
	/**
	 * @param int $id category id to delete
	 * @return Url
	 */
	abstract protected function get_delete_category_url($id);
}
?>