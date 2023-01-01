<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 6.0 - 2021 11 23
*/

class StatsGraphsController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view($request);

		return new SiteNodisplayResponse(new StringTemplate(''));
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$graph = $request->get_string('graph', '');
		$year = $request->get_int('year', '');
		$month = $request->get_int('month', '');
		$day = $request->get_int('day', '');
		
		if (preg_match('`graphs`iu', $request->get_current_url()) && preg_match('`visits`iu', $request->get_current_url()))
		{
			if (preg_match('`year`iu', $request->get_current_url()))
				StatsDisplayService::display_visits_year_graph($year);
			else
				StatsDisplayService::display_visits_month_graph($year, $month);
		}
		elseif (preg_match('`graphs`iu', $request->get_current_url()) && preg_match('`pages`iu', $request->get_current_url()))
		{
			if (preg_match('`year`iu', $request->get_current_url()))
				StatsDisplayService::display_pages_year_graph($year);
			elseif (preg_match('`month`iu', $request->get_current_url()))
				StatsDisplayService::display_pages_month_graph($year, $month);
		}
		else
		{
			switch ($graph)
			{
				case 'themes' :
					StatsDisplayService::display_themes_graph();
					break;
					
				case 'sex' :
					StatsDisplayService::display_sex_graph();
					break;
					
				case 'browsers' :
					StatsDisplayService::display_browsers_graph();
					break;
					
				case 'os' :
					StatsDisplayService::display_os_graph();
					break;
					
				case 'langs' :
					StatsDisplayService::display_langs_graph();
					break;
					
				case 'bots' :
					StatsDisplayService::display_bots_graph();
					break;
					
				default :
					StatsDisplayService::display_themes_graph();
					break;
			}
		}
	}
}
?>
