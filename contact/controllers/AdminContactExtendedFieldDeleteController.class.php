<?php
/*##################################################
 *                       AdminContactExtendedFieldDeleteController.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class AdminContactExtendedFieldDeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_post_protect();
		
		$id = $request->get_int('id', null);
		
		if ($id !== null)
		{
			$extended_field = new ExtendedField();
			$extended_field->set_id($id);
			$exist_field = ContactExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field);
			if ($exist_field)
			{
				ContactExtendedFieldsService::delete_by_id($id);
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}

?>