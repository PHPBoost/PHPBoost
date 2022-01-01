<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 19
 * @since       PHPBoost 5.2 - 2020 06 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PagesHomeController extends DefaultSeveralItemsController
{
	protected function build_view()
	{
		$categories = CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_categories();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, self::$module_id);
		$categories_elements_number = array(Category::ROOT_CATEGORY => self::get_items_manager()->count('WHERE id_category = :id_category', array('id_category' => Category::ROOT_CATEGORY)));

		foreach ($categories as $id => $category)
		{
			$id_parent = $category->get_id_parent();

			while ($id_parent != Category::ROOT_CATEGORY)
			{
				$parent_elements_number = (int)(isset($categories_elements_number[$id_parent]) ? $categories_elements_number[$id_parent] : $categories[$id_parent]->get_elements_number());
				$categories_elements_number[$id_parent] = $parent_elements_number - (int)$category->get_elements_number();
				$id_parent = $categories[$id_parent]->get_id_parent();
			}
		}

		$this->view->put_all(array(
			'C_ROOT_SEVERAL_ITEMS'   => $categories_elements_number[Category::ROOT_CATEGORY] > 1,
			'C_CONTROLS'             => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id(), self::$module_id)->moderation(),
			'C_CATEGORY_DESCRIPTION' => !empty($this->config->get_root_category_description()),
			'CATEGORY_DESCRIPTION'   => FormatingHelper::second_parse($this->config->get_root_category_description()),
			'U_ROOT_REORDER_ITEMS'   => ItemsUrlBuilder::specific_page('reorder', self::$module_id)->rel()
		));

		// Root category pages
		foreach (self::get_items_manager()->get_items($this->sql_condition, $this->sql_parameters) as $item)
		{
			$this->view->assign_block_vars('root_items', $item->get_template_vars());
		}

		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY && in_array($id, $authorized_categories))
			{
				$category_elements_number = isset($categories_elements_number[$id]) ? $categories_elements_number[$id] : $category->get_elements_number();
				$this->view->assign_block_vars('categories', array(
					'C_ITEMS'            => $category_elements_number > 0,
					'C_SEVERAL_ITEMS'    => $category_elements_number > 1,
					'ITEMS_NUMBER'       => $category->get_elements_number(),
					'CATEGORY_ID'        => $category->get_id(),
					'CATEGORY_SUB_ORDER' => $category->get_order(),
					'CATEGORY_PARENT_ID' => $category->get_id_parent(),
					'CATEGORY_NAME'      => $category->get_name(),
					'U_CATEGORY'         => ItemsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), self::$module_id)->rel(),
					'U_REORDER_ITEMS'    => ItemsUrlBuilder::specific_page('reorder', self::$module_id, array($category->get_id() . '-' . $category->get_rewrited_name()))->rel()
				));

				foreach (self::get_items_manager()->get_items($this->sql_condition, array('id_category' => $id)) as $item)
				{
					$this->view->assign_block_vars('categories.items', $item->get_template_vars());
				}
			}
		}
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('pages/PagesHomeController.tpl');
	}
}
?>
