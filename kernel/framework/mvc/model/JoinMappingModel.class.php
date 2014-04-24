<?php
/*##################################################
 *                           join_mapping_model.class.php
 *                            -------------------
 *   begin                : October 5, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class JoinMappingModel
{
	private $table_name;
	private $fk_db_field_name;
	private $primary_key;
	private $fields;

	public function __construct($table_name, $fk_db_field_name, MappingModelField $primary_key,
	$fields)
	{
		$this->table_name = $table_name;

		$this->fk_db_field_name = $fk_db_field_name;
		$this->primary_key = $primary_key;
		$this->fields = $fields;

		if (empty($this->fields))
		{
			throw new MappingModelException($this->classname, 'fields list can not be empty');
		}
	}

	/**
	 * @return string
	 */
	public function get_table_name()
	{
		return $this->table_name;
	}

	/**
	 * @return string
	 */
	public function get_fk_db_field_name()
	{
		return $this->fk_db_field_name;
	}

	/**
	 * @return MappingModelField
	 */
	public function get_primary_key()
	{
		return $this->primary_key;
	}

	/**
	 * @return MappingModelField[]
	 */
	public function get_fields()
	{
		return $this->fields;
	}
}
?>