<?php
/*##################################################
 *                               ExtendFieldMember.class.php
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


class ExtendFieldMember
{
	public static function display(Template $Template, $user_id = '')
	{
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
		
		$extend_field_exist = count($extend_fields_cache);
		if ($extend_field_exist > 0)
		{
			$Template->put_all(array(
				'C_MISCELLANEOUS' => true,
				'L_MISCELLANEOUS' => LangLoader::get_message('miscellaneous', 'main')
			));
			
			$display_extend_field = new DisplayExtendField();
			if(!empty($user_id))
			{
				$display_extend_field->display_for_member($Template, $user_id);
			}
			else
			{
				$display_extend_field->display_for_register($Template);
			}
		}
	}
}
?>