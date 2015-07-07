<?php
/*##################################################
 *                          AjaxKeywordsAutoCompleteController.class.php
 *                            -------------------
 *   begin                : August 28, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class AjaxKeywordsAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$suggestions = array();
 
		try {
			$result = PersistenceContext::get_querier()->select("SELECT name, rewrited_name FROM " . DB_TABLE_KEYWORDS . " WHERE name LIKE '" . $request->get_value('value', '') . "%'");
			
			while($row = $result->fetch())
			{
				$suggestions[] = $row['name'];
			}
			$result->dispose();
		} catch (Exception $e) {
		}
		
		return new JSONResponse(array('suggestions' => $suggestions));
	}
}
?>