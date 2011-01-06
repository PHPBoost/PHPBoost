<?php
/*##################################################
 *                      	 ExtendedFieldCache.class.php
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
class ExtendedFieldsCache implements CacheData
{
	private $extended_fields = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->extended_fields = array();
		$db_connection = PersistenceContext::get_sql();
		
		$result = $db_connection->query_while("SELECT id, position, name , field_name , description, field_type, possible_values, default_values, required, display, regex, auth 
			FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . "
			ORDER BY position", __LINE__, __FILE__);
		
		while ($row = $db_connection->fetch_assoc($result))
		{
			$auth = unserialize($row['auth']);
			
			$this->extended_fields[$row['id']] = array(
				'id' => $row['id'],
				'position' => !empty($row['position']) ? $row['position'] : '',
				'name' => !empty($row['name']) ? $row['name'] : '',
				'field_name' => !empty($row['field_name']) ? $row['field_name'] : '',
				'description' => !empty($row['description']) ? $row['description'] : '',
				'field_type' => !empty($row['field_type']) ? $row['field_type'] : '',
				'possible_values' => !empty($row['possible_values']) ? $row['possible_values'] : '',
				'default_values' => !empty($row['default_values']) ? $row['default_values'] : '',
				'required' => !empty($row['required']) ? $row['required'] : 0,
				'display' => !empty($row['display']) ? $row['display'] : 0,
				'regex' => !empty($row['regex']) ? $row['regex'] : '',
				'auth' => !empty($auth) ? $auth : array(),

			);
		}
		
		$db_connection->query_close($result);
	}

	public function get_extended_fields()
	{
		return $this->extended_fields;
	}
	
	public function get_exist_fields()
	{
		return (count($this->extended_fields) > 0) ? true : false;
	}

	public function get_extended_field($id)
	{
		if (isset($this->extended_fields[$id]))
		{
			return $this->extended_fields[$id];
		}
		return null;
	}
	
	/**
	 * Loads and returns the extended_fields cached data.
	 * @return ExtendedFieldsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'extended-fields');
	}
	
	/**
	 * Invalidates the current extended_fields cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'extended-fields');
	}
}
