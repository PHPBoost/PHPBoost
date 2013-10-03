<?php
/*##################################################
 *                      BugtrackerDeleteFilterController.class.php
 *                            -------------------
 *   begin                : November 09, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class BugtrackerDeleteFilterController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$id = $request->get_int('id', 0);
		$page = $request->get_int('page', 1);
		$back_page = $request->get_value('back_page', '');
		$back_filter = $request->get_value('back_filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		//Delete filter
		BugtrackerService::delete_filter("WHERE id=:id", array('id' => $id));
		
		switch ($back_page)
		{
			case 'solved' :
				$redirect = BugtrackerUrlBuilder::solved($page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
				break;
			default :
				$redirect = BugtrackerUrlBuilder::unsolved($page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : ''))->absolute();
				break;
		}
		
		AppContext::get_response()->redirect($redirect);
	}
}
?>
