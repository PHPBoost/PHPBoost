<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.3 - last update: 2019 08 20
 * @since   	PHPBoost 4.0 - 2013 03 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesSearchable extends DefaultSearchable
{
	public function __construct()
	{
		parent::__construct('articles');
		$this->read_authorization = ArticlesAuthorizationsService::check_authorizations()->read();
		
		$this->table_name = ArticlesSetup::$articles_table;
		
		$this->cats_table_name = ArticlesSetup::$articles_cats_table;
		$this->authorized_categories = ArticlesService::get_authorized_categories(Category::ROOT_CATEGORY);
		
		$this->use_keywords = true;
		
		$this->has_short_contents = true;
		$this->field_short_contents = 'articles_table_name.description';
		
		$this->field_approbation_type = 'published';
		
		$this->has_validation_period = true;
		$this->field_validation_start_date = 'publishing_start_date';
		$this->field_validation_end_date = 'publishing_end_date';
	}
}
?>
