<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 09 26
*/

class FormFieldCategoriesSelect extends FormFieldSimpleSelectChoice
{
	private $categories_cache;
	private $search_category_children_options;
	private $options = array();

    /**
     * Constructs a FormFieldCategoriesSelect.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     */
    public function __construct($id, $label, $value = 0, SearchCategoryChildrensOptions $search_category_children_options, $field_options = array(), CategoriesCache $categories_cache)
    {
    	$this->categories_cache = $categories_cache;
    	$this->search_category_children_options = $search_category_children_options;
        parent::__construct($id, $label, $value, $this->generate_options($value), $field_options);
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
			$this->options[] = new FormFieldSelectChoiceOption($root_category->get_name(), $root_category->get_id());
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
					$this->options[] = new FormFieldSelectChoiceOption(str_repeat('--', $node) . ' ' . $category->get_name(), $id);

				if ($this->search_category_children_options->check_authorizations($category) && ($this->search_category_children_options->is_excluded_categories_recursive() ? !$this->search_category_children_options->category_is_excluded($category) : true) && $this->search_category_children_options->is_enabled_recursive_exploration())
					$this->build_children_map($id_category, $categories, $id, ($node+1));
			}
		}
		return $this->options;
	}
}
?>
