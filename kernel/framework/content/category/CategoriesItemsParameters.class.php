<?php
/**
 * This class allows you to inquire the table that stores the items and the database field that contains the ID of the category in which it is located
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 05
 * @since       PHPBoost 4.0 - 2013 02 28
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CategoriesItemsParameters
{
	private mixed $table_name_contains_items = null;
	private mixed $field_name = self::DEFAULT_FIELD_NAME;

	const DEFAULT_FIELD_NAME = 'id_category';

	public function set_table_name_contains_items(mixed $table_name): void
	{
		$this->table_name_contains_items = $table_name;
	}

	public function get_table_name_contains_items(): mixed
	{
		return $this->table_name_contains_items;
	}

	public function set_field_name_id_category(mixed $field_name): void
	{
		$this->field_name = $field_name;
	}

	public function get_field_name_id_category(): mixed
	{
		return $this->field_name;
	}
}
?>
