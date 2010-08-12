<?php
/*##################################################
 *                               ExtendFieldAdmin.class.php
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


class ExtendFieldAdmin
{
	public static function insert_or_update_field($id = '')
	{
		$extend_field_admin_service = new ExtendFieldAdminService();
		if (!empty($id))
		{
			$extend_field_admin_service->update($id);
		}
		else
		{
			$extend_field_admin_service->add();
		}
		return $extend_field_admin_service->get_error();
	}
	
	public static function delete_field($id)
	{
		$extend_field_admin_service = new ExtendFieldAdminService();
		$extend_field_admin_service->delete($id);
		
		return $extend_field_admin_service->get_error();
	}
}
?>