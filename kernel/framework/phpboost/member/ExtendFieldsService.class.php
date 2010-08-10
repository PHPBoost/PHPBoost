<?php
/*##################################################
 *                               ExtendFieldsService.class.php
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


class ExtendFieldsService
{
	public static function get_array_type_fields()
	{
		$array_field_type = array(
			1 => 'VARCHAR(255) NOT NULL DEFAULT \'\'', 
			2 => 'TEXT NOT NULL', 
			3 => 'TEXT NOT NULL', 
			4 => 'TEXT NOT NULL', 
			5 => 'TEXT NOT NULL', 
			6 => 'TEXT NOT NULL'
		);
		
		return $array_field_type;
	}
	
	public static rewrite_field($field)
	{
		$field = strtolower($field);
		$field = Url::encode_rewrite($field);
		$field = str_replace('-', '_', $field);
		return 'f_' . $field;
	}
	
	
	public function get_array_regex()
	{
		$array_regex = array(
			1 => '`^[0-9]+$`',
			2 => '`^[a-z]+$`',
			3 => '`^[a-z0-9]+$`',
			4 => '`^[a-z0-9._-]+@(?:[a-z0-9_-]{2,}\.)+[a-z]{2,4}$`i',
			5 => '`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`i'
		);
	}
}
?>