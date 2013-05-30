<?php
/*##################################################
 *                             AbstractDeleteCategoryController.class.php
 *                            -------------------
 *   begin                : February 06, 2013
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
abstract class AbstractDeleteCategoryController extends AdminModuleController
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		try {
			$category = $this->get_category();
		} catch (CategoryNotFoundException $e) {
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), $this->lang['errors.unexisting']);
			DispatchManager::redirect($controller);
		}

		$childrens = $this->get_category_childrens($category);
		if (empty($childrens) && !$this->get_category_items_exists($category))
		{
			$this->get_categories_manager()->delete($this->get_category()->get_id());
			AppContext::get_response()->redirect($this->get_categories_management_url());
		}
	
		$this->build_form();
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->form->get_value('delete_category_and_content'))
			{
				$this->get_categories_manager()->delete($this->get_category()->get_id());
				foreach ($childrens as $id => $category)
				{
					$this->get_categories_manager()->delete($id);
				}
			}
			else
			{
				$id_parent = $this->form->get_value('move_in_other_cat')->get_raw_value();
				$childrens = $this->get_category_childrens($category, false);
				foreach ($childrens as $id => $category)
				{
					$this->get_categories_manager()->move_into_another($category, $id_parent);
				}
				$this->get_categories_manager()->delete($this->get_category()->get_id());
			}
			AppContext::get_response()->redirect($this->get_categories_management_url());
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('categories-common');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('delete_category', $this->lang['delete.category']);
		$fieldset->set_description($this->lang['delete.description']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('delete_category_and_content', $this->lang['delete.category_and_content'], FormFieldCheckbox::UNCHECKED, array('events' => array('click' => '
		if (HTMLForms.getField("delete_category_and_content").getValue()) {
			HTMLForms.getField("move_in_other_cat").disable();
		} else { 
			HTMLForms.getField("move_in_other_cat").enable();
		}')
		)));
		
		
		$options = new SearchCategoryChildrensOptions();
		$options->add_category_in_excluded_categories($this->get_category()->get_id());
		$fieldset->add_field($this->get_categories_manager()->get_select_categories_form_field('move_in_other_cat', $this->lang['delete.move_in_other_cat'], $this->get_category()->get_id_parent(), $options));
		
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function get_category_childrens(Category $category, $enable_recursive_exploration = true)
	{
		$options = new SearchCategoryChildrensOptions();
		$options->add_category_in_excluded_categories($category->get_id());
		$options->set_enable_recursive_exploration($enable_recursive_exploration);
		return $this->get_categories_manager()->get_childrens($category->get_id(), $options);
	}
	
	private function get_category_items_exists(Category $category)
	{
		return PersistenceContext::get_querier()->row_exists(
		$this->categories_items_parameters->get_table_name_contains_items(), 
		'WHERE '.$this->categories_items_parameters->get_field_name_id_category().'=:id_category', 
		array('id_category' => $category->get_id()
		)); 
	}
	
	private function get_category()
	{
		$id_category = $this->get_id_category();
		if (!empty($id_category) && $this->get_categories_manager()->get_categories_cache()->category_exists($id_category))
		{
			return $this->get_categories_manager()->get_categories_cache()->get_category($id_category);
		}
		throw new CategoryNotFoundException($id_category);
	}
	
	/**
	 * @return string id category for edit it
	 */
	abstract protected function get_id_category();
	
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
	 * @return Url
	 */
	abstract protected function get_categories_management_url();
}
?>