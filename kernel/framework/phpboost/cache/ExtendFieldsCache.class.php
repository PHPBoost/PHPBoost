<?php
/*##################################################
 *                      	 ExtendFieldCache.class.php
 *                            -------------------
 *   begin                : August 10, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class ExtendFieldsCache implements CacheData
{
	private $extend_fields = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->extend_fields = array();
		$db_connection = PersistenceContext::get_sql();
		
		$result = $db_connection->query_while("SELECT id, class, name , field_name , contents, field, possible_values, default_values, required, display 
			FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "
			ORDER BY class", __LINE__, __FILE__);
		
		while ($row = $db_connection->fetch_assoc($result))
		{
			$this->extend_fields[$row['id']] = array(
				'id' => $row['id'],
				'class' => !empty($row['class']) ? $row['class'] : '',
				'name' => !empty($row['name']) ? $row['name'] : '',
				'field_name' => !empty($row['field_name']) ? $row['field_name'] : '',
				'contents' => !empty($row['contents']) ? $row['contents'] : '',
				'field' => !empty($row['field']) ? $row['field'] : '',
				'possible_values' => !empty($row['possible_values']) ? $row['possible_values'] : '',
				'default_values' => !empty($row['default_values']) ? $row['default_values'] : '',
				'required' => !empty($row['required']) ? $row['required'] : 0,
				'display' => !empty($row['display']) ? $row['display'] : 0,
				'regex' => !empty($row['regex']) ? $row['regex'] : '',

			);
		}
		
		$db_connection->query_close($result);
	}

	public function get_extend_fields()
	{
		return $this->extend_fields;
	}
	
	public function get_exist_field()
	{
		return (count($this->extend_fields) > 0) ? true : false;
	}

	public function get_extend_field($id)
	{
		if (isset($this->extend_fields[$id]))
		{
			return $this->extend_fields[$id];
		}
		return null;
	}
	
	/**
	 * Loads and returns the extend_fields cached data.
	 * @return ExtendFieldsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'extend-fields');
	}
	
	/**
	 * Invalidates the current extend_fields cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'extend-fields');
	}
}