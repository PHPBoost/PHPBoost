<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 08 20
 * @since   	PHPBoost 4.1 - 2014 08 21
*/

class WebSearchable extends DefaultSearchable
{
	public function __construct()
	{
		parent::__construct('web');
		$this->read_authorization = WebAuthorizationsService::check_authorizations()->read();
		
		$this->table_name = WebSetup::$web_table;
		
		$this->cats_table_name = WebSetup::$web_cats_table;
		$this->authorized_categories = WebService::get_authorized_categories(Category::ROOT_CATEGORY);
		
		$this->use_keywords = true;
		
		$this->field_title = 'name';
		$this->field_rewrited_title = 'rewrited_name';
		
		$this->has_short_contents = true;
		
		$this->has_validation_period = true;
	}
}
?>
