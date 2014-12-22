<?php
/*##################################################
 *                          CategoriesItemsParameters.class.php
 *                            -------------------
 *   begin                : February 18, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
 *
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

/**
 * @package {@package}
 * @author Kévin MASSY
 * @desc This class allows you to inquire the table that stores the items and the database field that contains the ID of the category in which it is located
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