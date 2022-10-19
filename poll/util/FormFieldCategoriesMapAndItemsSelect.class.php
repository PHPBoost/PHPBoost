<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 30
 * @since       PHPBoost 6.0 - 2020 05 14
*/

class FormFieldCategoriesMapAndItemsSelect extends FormFieldMultipleSelectChoice
{
	private $categories_cache;
	private $search_category_children_options;
	private $options = array();
	private $selected_options;

    public function __construct($id, $label, $value, SearchCategoryChildrensOptions $search_category_children_options, array $selected_options, array $field_options, CategoriesCache $categories_cache)
    {
		$this->categories_cache = $categories_cache;
		$this->search_category_children_options = $search_category_children_options;
		$this->selected_options = $selected_options;
        parent::__construct($id, $label, $selected_options, $this->generate_options($value), $field_options, $constraints = array());
    }

    private function generate_options($id_category)
	{
		$categories = $this->categories_cache->get_categories();
		$root_category = $categories[Category::ROOT_CATEGORY];

		if (($this->search_category_children_options->is_excluded_categories_recursive() && $this->search_category_children_options->category_is_excluded($root_category)) || !$this->search_category_children_options->check_authorizations($root_category))
		{
			return array();
		}

		if (!$this->search_category_children_options->category_is_excluded($root_category))
		{
			$this->options[] = new FormFieldSelectChoiceGroupOption('&#128194; ' . $root_category->get_name(), $this->get_items_from_category_form_field(Category::ROOT_CATEGORY)); 
		}

		return $this->build_children_map($id_category, $categories, Category::ROOT_CATEGORY);
	}

	private function build_children_map($id_category, $categories, $id_parent, $node = 1)
	{
		foreach ($categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_parent && $id != Category::ROOT_CATEGORY)
			{
				if ($this->search_category_children_options->check_authorizations($category) && !$this->search_category_children_options->category_is_excluded($category))
				{
					$this->options[] = new FormFieldSelectChoiceGroupOption(str_repeat('--', $node) . ' &#128194; ' . $category->get_name(), $this->get_items_from_category_form_field($category->get_id()));
				}
				
				if ($this->search_category_children_options->check_authorizations($category) && ($this->search_category_children_options->is_excluded_categories_recursive() ? !$this->search_category_children_options->category_is_excluded($category) : true) && $this->search_category_children_options->is_enabled_recursive_exploration())
					$this->build_children_map($id_category, $categories, $id, ($node+1));
			}
		}
		return $this->options;
	}
	
	protected function get_items_from_category_form_field(int $id_category)
	{
		$items = ItemsService::get_items_manager('poll')->get_items('WHERE id_category =:id_category', array('id_category' => $id_category));
	
		$form_field = array();
		foreach ($items as $item)
		{
			$selected = in_array($item->get_id(), $this->selected_options);
			$form_field[] = new FormFieldSelectChoiceOption('&#128202; ' . $item->get_title(), $item->get_id(), array('selected' => $selected));
		}
	
		return $form_field;
	}
}
?>
