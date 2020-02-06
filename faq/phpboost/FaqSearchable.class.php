<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 06
 * @since       PHPBoost 4.0 - 2014 09 02
*/

class FaqSearchable extends DefaultSearchable
{
	public function __construct()
	{
		parent::__construct('faq', "'#question', faq_table_name.id");
		
		$this->table_name = FaqSetup::$faq_table;
		
		$this->field_title = 'question';
		$this->field_content = 'answer';
		
		$this->field_published = 'approved';
	}
}
?>
