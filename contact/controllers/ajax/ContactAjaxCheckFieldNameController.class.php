<?php
/*##################################################
 *                          ContactAjaxCheckFieldNameController.class.php
 *                            -------------------
 *   begin                : August 4, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class ContactAjaxCheckFieldNameController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_value('id', 0);
		$name = $request->get_value('name', '');
		$field_name = ContactField::rewrite_field_name($name);
		
		$result = 0;
		if (!empty($id))
		{
			foreach (ContactConfig::load()->get_fields() as $key => $f)
			{
				if ($key != $id && $f['field_name'] == $field_name)
					$result = 1;
			}
		}
		else
		{
			foreach (ContactConfig::load()->get_fields() as $key => $f)
			{
				if ($f['field_name'] == $field_name)
					$result = 1;
			}
		}
		
		return new JSONResponse(array('result' => $result));
	}
}
?>
