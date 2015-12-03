<?php
/*##################################################
 *                          AjaxUrlValidationController.class.php
 *                            -------------------
 *   begin                : November 27, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class AjaxUrlValidationController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$status = 200;
		
		$url_to_check = $request->get_value('url_to_check', '');
		if ($url_to_check)
		{
			$url = new Url($url_to_check);
			if (function_exists('get_headers') && ($file_headers = get_headers($url->relative(), true)) && isset($file_headers[0]))
			{
				if(preg_match('/^HTTP\/[12]\.[01] (\d\d\d)/', $file_headers[0], $matches))
					$status = (int)$matches[1];
			}
		}
		
		return new JSONResponse(array('status' => $status));
	}
}
?>
