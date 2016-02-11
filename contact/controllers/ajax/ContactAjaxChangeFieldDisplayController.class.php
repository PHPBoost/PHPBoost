<?php
/*##################################################
 *                          ContactAjaxChangeFieldDisplayController.class.php
 *                            -------------------
 *   begin                : March 3, 2015
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

class ContactAjaxChangeFieldDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		
		$display = -1;
		if ($id !== 0)
		{
			$config = ContactConfig::load();
			$fields = $config->get_fields();
			if ($fields[$id]['displayed'])
				$display = $fields[$id]['displayed'] = 0;
			else
				$display = $fields[$id]['displayed'] = 1;
			$config->set_fields($fields);
			
			ContactConfig::save();
		}
		
		return new JSONResponse(array('id' => $id, 'display' => $display));
	}
}
?>
