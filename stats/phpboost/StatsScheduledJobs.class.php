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
		try {
			$result = PersistenceContext::get_querier()->insert(StatsSetup::$stats_table, array(
				'stats_year' => $yesterday->get_year(Timezone::SERVER_TIMEZONE),
				'stats_month' => $yesterday->get_month(Timezone::SERVER_TIMEZONE),
				'stats_day' => $yesterday->get_day(Timezone::SERVER_TIMEZONE),
				'nbr' => 0, 
				'pages' => 0, 
				'pages_detail' => ''
			));

			//We retrieve the id we just come to create
			$last_stats = $result->get_last_inserted_id();
		} catch (MySQLQuerierException $e) {
			$last_stats = PersistenceContext::get_querier()->get_column_value(StatsSetup::$stats_table, 'id', 'WHERE stats_year = :stats_year AND stats_month = :stats_month AND stats_day = :stats_day', array(
				'stats_year' => $yesterday->get_year(Timezone::SERVER_TIMEZONE),
				'stats_month' => $yesterday->get_month(Timezone::SERVER_TIMEZONE),
				'stats_day' => $yesterday->get_day(Timezone::SERVER_TIMEZONE)
			));
		}

		PersistenceContext::get_querier()->inject("UPDATE " . StatsSetup::$stats_referer_table . " SET yesterday_visit = today_visit, today_visit = 0, nbr_day = nbr_day + 1");
		
		//We delete the referer entries older than one week
		PersistenceContext::get_querier()->delete(StatsSetup::$stats_referer_table, 'WHERE last_update < :last_update', array('last_update' => time() - 604800));

		//We retrieve the number of pages seen until now
		$pages_displayed = StatsSaver::retrieve_stats('pages');

		//We delete the file containing the displayed pages
		$pages_file = new File(PATH_TO_ROOT . '/stats/cache/pages.txt');
		$pages_file->delete();

		//How much visitors were there today?
		$total_visit = PersistenceContext::get_querier()->get_column_value(DB_TABLE_VISIT_COUNTER, 'total', 'WHERE id = 1');
		
		//We update the stats table: the number of visits today
		PersistenceContext::get_querier()->update(StatsSetup::$stats_table, 
			array('nbr' => $total_visit, 'pages' => array_sum($pages_displayed), 'pages_detail' => serialize($pages_displayed)), 
		'WHERE id=:id', array('id' => $last_stats));
	}
	
	public function on_changepage()
	{
		StatsSaver::update_pages_displayed();
		StatsSaver::compute_referer();
	}
	
	public function on_new_session($new_visitor, $is_robot)
	{
		if ($new_visitor && !$is_robot)
			StatsSaver::compute_users();
			
		if ($is_robot)
			StatsSaver::register_bot();
	}
}
?>
