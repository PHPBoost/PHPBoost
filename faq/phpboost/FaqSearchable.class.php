<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 08
 * @since       PHPBoost 4.0 - 2014 09 02
*/

class FaqSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'faq';
		parent::__construct($module_id);
		$this->read_authorization = CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, $module_id)->read();
		
		$this->table_name = FaqSetup::$faq_table;
		
		$this->cats_table_name = FaqSetup::$faq_cats_table;
		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, true, $module_id);
		
		$this->custom_link_end = "'#question', faq_table_name.id";
		
		$this->field_title = 'question';
		$this->field_contents = 'answer';
		
		$this->field_approbation_type = 'approved';
	}
}
?>
