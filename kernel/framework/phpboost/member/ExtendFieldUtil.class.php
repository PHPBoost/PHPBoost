<?php
/*##################################################
 *                               ExtendFieldUtil.class.php
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


class ExtendFieldUtil
{
	public static function get_field_type($field_type)
	{
		if (is_numeric($field_type))
		{
			$array_field_type = array(
				1 => 'VARCHAR(255) NOT NULL DEFAULT \'\'', 
				2 => 'TEXT NOT NULL', 
				3 => 'TEXT NOT NULL', 
				4 => 'TEXT NOT NULL', 
				5 => 'TEXT NOT NULL', 
				6 => 'TEXT NOT NULL'
			);
			
			return $array_field_type[$field_type];
		}
	}
	
	public static function rewrite_field($field)
	{
		$field = strtolower($field);
		$field = Url::encode_rewrite($field);
		$field = str_replace('-', '_', $field);
		return 'f_' . $field;
	}
	
	public static function get_regex($regex_type)
	{
		if (is_numeric($regex_type))
		{
			$array_regex = array(
				1 => '`^[0-9]+$`',
				2 => '`^[a-z]+$`',
				3 => '`^[a-z0-9]+$`',
				4 => '`^[a-z0-9._-]+@(?:[a-z0-9_-]{2,}\.)+[a-z]{2,4}$`i',
				5 => '`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`i'
			);
			
			return $array_regex[$regex_type];
		}
	}
	
	public static function add_field_name($field_name, $field)
	{
		$change_type = ExtendFieldUtil::get_field_type($field);
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " ADD " . $field_name. " " .$change_type, __LINE__, __FILE__);
	}
	
	public static function change_field_name($precedent_field_id, $new_field, $field)
	{
		$previous_name = PersistenceContext::get_sql()->query("SELECT field_name FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $precedent_field_id . "'", __LINE__, __FILE__);
		$change_type = ExtendFieldUtil::get_field_type($field);
		
		PersistenceContext::get_sql()->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " CHANGE " . $previous_name . " " . $new_field . " " . $change_type, __LINE__, __FILE__);
	}
}
?>