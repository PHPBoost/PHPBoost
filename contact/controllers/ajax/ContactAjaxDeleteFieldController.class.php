<?php
/*##################################################
 *                          ContactAjaxDeleteFieldController.class.php
 *                            -------------------
 *   begin                : August 8, 2013
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

class ContactAjaxDeleteFieldController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		
		$code = -1;
		if (!empty($id))
		{
			$config = ContactConfig::load();
			$fields = $config->get_fields();
			if (isset($fields[$id]))
			{
				$field = new ContactField();
				$field->set_properties($fields[$id]);
				
				if ($field->is_deletable())
				{
					unset($fields[$id]);
					$new_fields_list = array();
					
					$position = 1;
					foreach ($fields as $key => $f)
					{
						$new_fields_list[$position] = $f;
						$position++;
					}
					
					$config->set_fields($new_fields_list);
					
					ContactConfig::save();
					$code = $id;
				}
			}
		}
		
		return new JSONResponse(array('code' => $code));
	}
}
?>
