<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 29
 * @since       PHPBoost 3.0 - 2012 11 09
*/

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
