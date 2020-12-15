<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 08 30
 * @since       PHPBoost 3.0 - 2010 05 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/
class PagesSearchable extends DefaultSearchable
{
	public function __construct()
	{
		$module_id = 'pages';
		parent::__construct($module_id);
		$this->read_authorization = CategoriesAuthorizationsService::check_authorizations()->read();

		$this->table_name = PagesSetup::$pages_table;

		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $module_id);

		$this->use_keywords = true;

		$this->field_title = 'title';
		$this->field_rewrited_title = 'rewrited_title';
		$this->field_content = 'content';

		$this->has_summary = false;
		$this->field_summary = '';

		$this->field_published = 'published';

		$this->has_validation_period = true;
		$this->field_validation_publishing_start_date = 'publishing_start_date';
		$this->field_validation_publishing_end_date = 'publishing_end_date';
	}
}
?>
