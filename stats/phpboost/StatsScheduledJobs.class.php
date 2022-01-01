<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 30
 * @since       PHPBoost 3.0 - 2013 01 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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

			// We retrieve the id we just created
			$last_stats = $result->get_last_inserted_id();
		} catch (MySQLQuerierException $e) {
			$last_stats = PersistenceContext::get_querier()->get_column_value(StatsSetup::$stats_table, 'id', 'WHERE stats_year = :stats_year AND stats_month = :stats_month AND stats_day = :stats_day', array(
				'stats_year' => $yesterday->get_year(Timezone::SERVER_TIMEZONE),
				'stats_month' => $yesterday->get_month(Timezone::SERVER_TIMEZONE),
				'stats_day' => $yesterday->get_day(Timezone::SERVER_TIMEZONE)
			));
		}

		PersistenceContext::get_querier()->inject("UPDATE " . StatsSetup::$stats_referer_table . " SET yesterday_visit = today_visit, today_visit = 0, nbr_day = nbr_day + 1");

		// We delete the referer entries older than one week
		PersistenceContext::get_querier()->delete(StatsSetup::$stats_referer_table, 'WHERE last_update < :last_update', array('last_update' => time() - 604800));

		// We retrieve the number of page views until now
		$pages_displayed = StatsSaver::retrieve_stats('pages');

		// We delete the file containing the displayed pages
		$pages_file = new File(PATH_TO_ROOT . '/stats/cache/pages.txt');
		$pages_file->delete();

		// How many visitors were there today?
		$total_visit = PersistenceContext::get_querier()->get_column_value(DB_TABLE_VISIT_COUNTER, 'total', 'WHERE id = 1');

		// We update the stats table: the today's number of visits
		PersistenceContext::get_querier()->update(StatsSetup::$stats_table,
			array('nbr' => $total_visit, 'pages' => array_sum($pages_displayed), 'pages_detail' => TextHelper::serialize($pages_displayed)),
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
