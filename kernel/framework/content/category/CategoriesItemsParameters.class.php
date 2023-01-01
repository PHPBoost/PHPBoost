<?php
/**
 * This class allows you to inquire the table that stores the items and the database field that contains the ID of the category in which it is located
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 4.0 - 2013 02 28
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class CategoriesItemsParameters
{
	private $table_name_contains_items;
	private $field_name = self::DEFAULT_FIELD_NAME;

	const DEFAULT_FIELD_NAME = 'id_category';

	public function set_table_name_contains_items($table_name)
	{
		$this->table_name_contains_items = $table_name;
	}

	public function get_table_name_contains_items()
	{
		return $this->table_name_contains_items;
	}

	public function set_field_name_id_category($field_name)
	{
		$this->field_name = $field_name;
	}

	public function get_field_name_id_category()
	{
		return $this->field_name;
	}
}
?>
