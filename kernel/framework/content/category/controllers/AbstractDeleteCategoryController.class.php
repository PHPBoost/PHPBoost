<?php
/**
 * @package     Content
 * @subpackage  Category\controllers
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 4.0 - 2013 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractDeleteCategoryController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	protected $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;

	protected static $categories_manager;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		try {
			$category = $this->get_category();
		} catch (CategoryNotFoundException $e) {
			$controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($controller);
		}

		$children = $this->get_category_children($category);
		if (empty($children) && !$this->get_category_items_exists($category))
		{
			self::$categories_manager->delete($this->get_category()->get_id());
			$this->clear_cache();
			AppContext::get_response()->redirect($this->get_categories_management_url(), StringVars::replace_vars($this->get_success_message(), array('name' => $this->get_category()->get_name())));
		}

		$this->build_form();
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->form->get_value('delete_category_and_content'))
			{
				foreach ($children as $id => $category)
				{
					self::$categories_manager->delete($id);
				}
			}
			else
			{
				$id_parent = $this->form->get_value('move_in_other_cat')->get_raw_value();
				self::$categories_manager->move_items_into_another($category, $id_parent);

				foreach ($this->get_category_children($category, false) as $id => $category)
				{
					self::$categories_manager->move_into_another($category, $id_parent);
				}
			}

			self::$categories_manager->delete($this->get_category()->get_id());
			$categories_cache = self::$categories_manager->get_categories_cache()->get_class();
			$categories_cache::invalidate();
			$this->clear_cache();
			HooksService::execute_hook_action('delete_category', self::$module_id, $this->get_category()->get_properties());
			AppContext::get_response()->redirect($this->get_categories_management_url(), StringVars::replace_vars($this->get_success_message(), array('name' => $this->get_category()->get_name())));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->generate_response($tpl);
	}

	private function init()
	{
		$class_name = get_called_class();
		self::$categories_manager = $class_name::get_categories_manager();

		$this->lang = LangLoader::get_all_langs(self::$module_id);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->get_title());

		$fieldset = new FormFieldsetHTML('delete_category', $this->lang['form.parameters']);
		$fieldset->set_description($this->get_description());
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('delete_category_and_content', $this->lang['category.delete.all.content'], FormFieldCheckbox::UNCHECKED,
			array(
				'events' => array('click' => '
					if (HTMLForms.getField("delete_category_and_content").getValue()) {
						HTMLForms.getField("move_in_other_cat").disable();
					} else {
						HTMLForms.getField("move_in_other_cat").enable();
					}')
				)
			)
		);


		$options = new SearchCategoryChildrensOptions();
		$options->add_category_in_excluded_categories($this->get_category()->get_id());
		$fieldset->add_field(self::$categories_manager->get_select_categories_form_field('move_in_other_cat', $this->lang['category.move.to'], $this->get_category()->get_id_parent(), $options));


		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_category_children(Category $category, $enable_recursive_exploration = true)
	{
		$options = new SearchCategoryChildrensOptions();
		$options->add_category_in_excluded_categories($category->get_id());
		$options->set_enable_recursive_exploration($enable_recursive_exploration);
		return self::$categories_manager->get_children($category->get_id(), $options);
	}

	private function get_category_items_exists(Category $category)
	{
		return PersistenceContext::get_querier()->row_exists(
		self::$categories_manager->get_categories_items_parameters()->get_table_name_contains_items(),
		'WHERE '. self::$categories_manager->get_categories_items_parameters()->get_field_name_id_category().'=:id_category',
		array('id_category' => $category->get_id()
		));
	}

	private function get_category()
	{
		$id_category = $this->get_id_category();
		if (!empty($id_category) && self::$categories_manager->get_categories_cache()->category_exists($id_category))
		{
			return self::$categories_manager->get_categories_cache()->get_category($id_category);
		}
		throw new CategoryNotFoundException($id_category);
	}

	/**
	 * @return string Categories management page title
	 */
	protected function get_categories_management_title()
	{
		return $this->lang['category.categories.management'];
	}

	/**
	 * @return string Page title
	 */
	protected function get_title()
	{
		return $this->lang['category.delete'];
	}

	/**
	 * @return string delete description
	 */
	protected function get_description()
	{
		return $this->lang['category.delete.description'];
	}

	/**
	 * @return string delete success message
	 */
	protected function get_success_message()
	{
		return $this->lang['category.success.delete'];
	}

	/**
	 * @return Clear elements cache if any
	 */
	protected function clear_cache()
	{
		return true;
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
		$graphical_environment->get_seo_meta_data()->set_canonical_url($this->get_delete_category_url($this->get_category()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->get_module_home_page_title(), $this->get_module_home_page_url());

		$breadcrumb->add($this->get_categories_management_title(), $this->get_categories_management_url());
		$breadcrumb->add($this->get_title(), $this->get_delete_category_url($this->get_category()));

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
	 * @return string id of the category to edit / delete
	 */
	abstract protected function get_id_category();

	/**
	 * @return Url
	 */
	abstract protected function get_categories_management_url();

	/**
	 * @param int $category Category
	 * @return Url
	 */
	abstract protected function get_delete_category_url(Category $category);

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
