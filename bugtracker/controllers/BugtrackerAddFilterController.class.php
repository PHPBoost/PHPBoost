<?php
/*##################################################
 *                      BugtrackerAddFilterController.class.php
 *                            -------------------
 *   begin                : November 09, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

class BugtrackerAddFilterController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		$page = $request->get_value('page', '');
		$filter = $request->get_value('filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		//Add filter
		BugtrackerService::add_filter(array(
			'user_id'		=> AppContext::get_current_user()->get_id(),
			'page'			=> $page,
			'filters'		=> $filter,
			'filters_ids'	=> $filter_id
		));
		
		AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : BugtrackerUrlBuilder::unsolved()), LangLoader::get_message('success.add.filter', 'common', 'bugtracker'));
	}
}
?>
