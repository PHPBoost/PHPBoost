<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 3.0 - 2011 09 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldCategoriesSelect extends FormFieldSimpleSelectChoice
{
	private static $options = array();

	/**
     * Constructs a FormFieldCategoriesSelect.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     */
    public function __construct($id, $label, $value, SearchCategoryChildrensOptions $search_category_children_options, $field_options, CategoriesCache $categories_cache, array $select_options = array())
    {
		parent::__construct($id, $label, $value, ($select_options ? $select_options : self::generate_options($value, $search_category_children_options, false, $categories_cache)), $field_options);
    }

    public static function generate_options($id_category, SearchCategoryChildrensOptions $search_category_children_options, $all_categories_option = false, $categories_cache = '')
	{
		$categories = ($categories_cache instanceof CategoriesCache ? $categories_cache->get_categories() : CategoriesService::get_categories_manager()->get_categories_cache()->get_categories());
		$root_category = $categories[Category::ROOT_CATEGORY];

		if (($search_category_children_options->is_excluded_categories_recursive() && $search_category_children_options->category_is_excluded($root_category)) || !$search_category_children_options->check_authorizations($root_category))
			return array();

		self::$options = array();
		if ($all_categories_option)
			self::$options[] = new FormFieldSelectChoiceOption(LangLoader::get_message('category.all.categories', 'category-lang'), 'all');

		if (!$search_category_children_options->category_is_excluded($root_category))
			self::$options[] = new FormFieldSelectChoiceOption($root_category->get_name(), $root_category->get_id());

		return self::build_children_map($id_category, $categories, Category::ROOT_CATEGORY, $search_category_children_options);
	}

	private static function build_children_map($id_category, $categories, $id_parent, SearchCategoryChildrensOptions $search_category_children_options, $node = 1)
	{
		foreach ($categories as $id => $category)
		{
			if ($category->get_id_parent() == $id_parent && $id != Category::ROOT_CATEGORY)
			{
				if ($search_category_children_options->check_authorizations($category) && !$search_category_children_options->category_is_excluded($category))
					self::$options[] = new FormFieldSelectChoiceOption(str_repeat('--', $node) . ' ' . $category->get_name(), $id);

				if ($search_category_children_options->check_authorizations($category) && ($search_category_children_options->is_excluded_categories_recursive() ? !$search_category_children_options->category_is_excluded($category) : true) && $search_category_children_options->is_enabled_recursive_exploration())
					self::build_children_map($id_category, $categories, $id, $search_category_children_options, ($node + 1));
			}
		}
		return self::$options;
	}
}
?>
