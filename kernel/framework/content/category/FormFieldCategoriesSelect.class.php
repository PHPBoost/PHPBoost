<?php
/*##################################################
 *                             FormFieldCategoriesSelect.class.php
 *                            -------------------
 *   begin                : September 26, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class FormFieldCategoriesSelect extends FormFieldSimpleSelectChoice
{
	private $categories_cache;
	private $search_category_children_options;
	private $options = array();
	
    /**
     * @desc Constructs a FormFieldCategoriesSelect.
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