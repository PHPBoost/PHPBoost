<?php
/**
 * @package     Content
 * @subpackage  Category\controllers
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 28
 * @since       PHPBoost 4.0 - 2013 02 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractCategoriesManagementController extends ModuleController
{
	protected $lang;
	protected $view;

	protected static $categories_manager;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->update_positions($request);

		$this->build_view();

		return $this->generate_response($this->view);
	}

	private function init()
	{
		$class_name = get_called_class();
		self::$categories_manager = $class_name::get_categories_manager();

		$this->lang = LangLoader::get_all_langs(self::$module_id);
		$this->view = new FileTemplate('__default__/framework/content/categories/manage.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$categories_cache = self::$categories_manager->get_categories_cache()->get_class();
		$categories = $categories_cache::load()->get_categories();

		$number_categories = count($categories);

		$this->view->put_all(array(
			'C_NO_CATEGORY'        => $number_categories <= 1, // Root category is not considered as a category
			'C_SEVERAL_CATEGORIES' => $number_categories > 2, // Root category is not displayed, but taken into account in the calculation

			'FIELDSET_TITLE' => $this->get_title(),
			'MODULE_ID' 	 => Environment::get_running_module_name(),
		));
		$this->build_children_view($this->view, $categories, Category::ROOT_CATEGORY);
	}

	private function build_children_view($template, $categories, $id_parent)
	{
		foreach ($categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_parent && $id != Category::ROOT_CATEGORY)
			{
				$description = '';
				if (method_exists($category, 'get_description'))
				{
					$description = FormatingHelper::second_parse($category->get_description());
					$description = TextHelper::strlen($description) > 250 ? TextHelper::cut_string(@strip_tags($description, '<br><br/>'), 250) . '...' : $description;
				}

				$color = '';
				if (method_exists($category, 'get_color'))
				{
					$color = $category->get_color();
				}

				$category_view = new FileTemplate('__default__/framework/content/categories/category.tpl');
				$category_view->add_lang($this->lang);
				$category_view->put_all(array(
					'C_DESCRIPTION'            => !empty($description),
					'C_COLOR'                  => !empty($color),
					'C_ALLOWED_TO_HAVE_CHILDS' => $category->is_allowed_to_have_childs(),
					'C_SPECIAL_AUTHORIZATIONS' => $category->has_special_authorizations(),

					'ID'                          => $id,
					'NAME'                        => $category->get_name(),
					'DESCRIPTION'                 => $description,
					'COLOR'                       => $color,
					'DELETE_CONFIRMATION_MESSAGE' => StringVars::replace_vars($this->get_delete_confirmation_message(), array('name' => $category->get_name())),

					'U_DISPLAY' => $this->get_display_category_url($category)->rel(),
					'U_EDIT'    => $this->get_edit_category_url($category)->rel(),
					'U_DELETE'  => $this->get_delete_category_url($category)->rel(),
				));

				$this->build_children_view($category_view, $categories, $id);

				$template->assign_block_vars('children', array('child' => $category_view->render()));
			}
		}
	}

	private function update_positions(HTTPRequestCustom $request)
	{
		if ($request->get_postvalue('submit', false))
		{
			$categories = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
			$categories_cache = self::$categories_manager->get_categories_cache();

			foreach ($categories as $position => $tree)
			{
				$id = $tree->id;
				$category = $categories_cache->get_category($id);

				self::$categories_manager->update_position($category, Category::ROOT_CATEGORY, ($position + 1));

				$this->update_children_positions($tree->children[0], $category->get_id());
			}

			$categories_cache::invalidate();

			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.position.update'], MessageHelper::SUCCESS, 5));
		}
	}

	private function update_children_positions($categories, $id_parent)
	{
		if (!empty($categories))
		{
			foreach ($categories as $position => $tree)
			{
				if (is_int($position))
				{
					$id = $tree->id;
					$category = self::$categories_manager->get_categories_cache()->get_category($id);

					self::$categories_manager->update_position($category, $id_parent, ($position + 1));

					$this->update_children_positions($tree->children[0], $category->get_id());
				}
			}
		}
	}

	/**
	 * @return string Page title
	 */
	protected function get_title()
	{
		return $this->lang['category.categories.management'];
	}

	/**
	 * @return string Delete category confirmation message
	 */
	protected function get_delete_confirmation_message()
	{
		return $this->lang['category.delete.confirmation'];
	}

	/**
	 * @param View $view
	 * @return Response
	 */
	protected function generate_response(View $view)
	{
		$response = new SiteDisplayResponse($view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_title(), $this->get_module_home_page_title());
		$graphical_environment->get_seo_meta_data()->set_canonical_url($this->get_categories_management_url());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->get_module_home_page_title(), $this->get_module_home_page_url());

		$breadcrumb->add($this->get_title(), $this->get_categories_management_url());

		return $response;
	}

	/**
	 * @return CategoriesManager
	 */
	protected static function get_categories_manager()
	{
		return CategoriesService::get_categories_manager();
	}

	/**
	 * @param int $category Category
	 * @return Url
	 */
	abstract protected function get_display_category_url(Category $category);

	/**
	 * @param int $category Category
	 * @return Url
	 */
	abstract protected function get_edit_category_url(Category $category);

	/**
	 * @param int $category Category
	 * @return Url
	 */
	abstract protected function get_delete_category_url(Category $category);

	/**
	 * @return Url
	 */
	abstract protected function get_categories_management_url();

	/**
	 * @return Url
	 */
	abstract protected function get_module_home_page_url();

	/**
	 * @return string module home page title
	 */
	abstract protected function get_module_home_page_title();

	/**
	 * @return boolean Authorization to manage categories
	 */
	abstract protected function check_authorizations();
}
?>
