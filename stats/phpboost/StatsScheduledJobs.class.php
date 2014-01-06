<?php
/*##################################################
 *                         StatsScheduledJobs.class.php
 *                            -------------------
 *   begin                : January 06, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class StatsScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changeday(Date $yesterday, Date $today)
	{
		$result = PersistenceContext::get_querier()->insert(StatsSetup::$stats_table, array(
			'stats_year' => $yesterday->get_year(TIMEZONE_SYSTEM),
			'stats_month' => $yesterday->get_month(TIMEZONE_SYSTEM),
			'stats_day' => $yesterday->get_day(TIMEZONE_SYSTEM),
			'nbr' => 0, 
			'pages' => 0, 
			'pages_detail' => ''
		));

		//We retrieve the id we just come to create
		$last_stats = $result->get_last_inserted_id();

		PersistenceContext::get_sql()->query_inject("UPDATE " . StatsSetup::$stats_referer_table .
			" SET yesterday_visit = today_visit, today_visit = 0, nbr_day = nbr_day + 1", __LINE__, __FILE__);
		
		//We delete the referer entries older than one week
		PersistenceContext::get_sql()->query_inject("DELETE FROM " . StatsSetup::$stats_referer_table .
		" WHERE last_update < '" . (time() - 604800) . "'", __LINE__, __FILE__);

		//We retrieve the number of pages seen until now
		$pages_displayed = StatsSaver::retrieve_stats('pages');

		//We delete the file containing the displayed pages
		$pages_file = new File(PATH_TO_ROOT . '/stats/cache/pages.txt');
		$pages_file->delete();

		//How much visitors were there today?
		$total_visit = PersistenceContext::get_sql()->query("SELECT total FROM " . DB_TABLE_VISIT_COUNTER .
			" WHERE id = 1", __LINE__, __FILE__);
		
		//We truncate the table containing the visitors of today
		PersistenceContext::get_sql()->query_inject("DELETE FROM " . DB_TABLE_VISIT_COUNTER . " WHERE id <> 1", __LINE__, __FILE__);
		
		//We update the last changeday date
		PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_VISIT_COUNTER .
			" SET time = '" . gmdate_format('Y-m-d', time(), TIMEZONE_SYSTEM) .
				"', total = 1 WHERE id = 1", __LINE__, __FILE__);
		
		//We insert this visitor as a today visitor
		PersistenceContext::get_sql()->query_inject("INSERT INTO " . DB_TABLE_VISIT_COUNTER .
			" (ip, time, total) VALUES('" . AppContext::get_current_user()->get_ip() . "', '" . gmdate_format('Y-m-d', time(),
		TIMEZONE_SYSTEM) . "', '0')", __LINE__, __FILE__);

		//We update the stats table: the number of visits today
		PersistenceContext::get_sql()->query_inject("UPDATE " . StatsSetup::$stats_table . " SET nbr = '" . $total_visit .
		"', pages = '" . array_sum($pages_displayed) . "', pages_detail = '" .
		addslashes(serialize($pages_displayed)) . "' WHERE id = '" . $last_stats . "'",
		__LINE__, __FILE__);
	}
}
?>
