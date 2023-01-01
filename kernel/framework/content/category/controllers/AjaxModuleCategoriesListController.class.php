<?php
/**
 * @package     Content
 * @subpackage  Category\controllers
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 28
 * @since       PHPBoost 6.0 - 2021 03 27
*/

class AjaxModuleCategoriesListController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$options = '';

		if ($requested_module_id = $request->get_string('module_id', ''))
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);

			foreach(FormFieldCategoriesSelect::generate_options('', $search_category_children_options, false, CategoriesService::get_categories_manager($requested_module_id)->get_categories_cache()) as $option)
			{
				$options .= '<option value="' . $option->get_raw_value() . '">' . $option->get_label() . '</option>';
			}
		}

		return new JSONResponse(array('options' => $options));
	}
}
?>
