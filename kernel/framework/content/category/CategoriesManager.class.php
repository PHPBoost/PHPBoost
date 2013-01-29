<?php
/*##################################################
 *                             CategoriesManager.class.php
 *                            -------------------
 *   begin                : January 29, 2013
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
 * @desc
 */
class CategoriesManager
{
	/**
	 * @var string id module.
	 */
	private $id_module;
	
	/**
	 * @var string table name where are stocked the categories to manage.
	 */
	private $table_name;
	
	/**
	 * @var string name of the category class
	 */
	private $category_class;
	
	/**
	 * @var DBQuerier
	 */
	private $db_querier;
	
	const STANDARD_CATEGORY_CLASS = 'Category';
	const RICH_CATEGORY_CLASS = 'RichCategory';
	
	public function __construct($id_module, $table_name, $category_class = self::STANDARD_CATEGORY_CLASS)
	{
		$this->id_module = $id_module;
		$this->table_name = $table_name;
		$this->category_class = $category_class;
	
		$this->db_querier = PersistenceContext::get_querier();
	}
	
	public function add($id_parent, Category $category)
	{
		
	}
	
	public function update(Category $category)
	{
		
	}
	
	public function delete($id)
	{
		
	}
	
	public function change_visibility()
	{
		
	}
}
?>