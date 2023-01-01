<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 1.6 - 2007 01 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$db_querier = PersistenceContext::get_querier();

$request = AppContext::get_request();

$stats_referer = $request->get_getint('stats_referer', 0);
$stats_keyword = $request->get_getint('stats_keyword', 0);

if (!empty($stats_referer))
{
	$idurl = $request->get_getint('id', 0);
	$url = '';

	try {
		$url = $db_querier->get_column_value(StatsSetup::$stats_referer_table, 'url', 'WHERE id = :id', array('id' => $idurl));
	} catch (RowNotFoundException $e) {}

	if ($url)
	{
		$result = $db_querier->select("SELECT url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
			FROM " . StatsSetup::$stats_referer_table . "
			WHERE url = :url AND type = 0
			ORDER BY total_visit DESC", array(
				'url' => $url
		));
		while ($row = $result->fetch())
		{
			$trend_parameters = StatsDisplayService::get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);

			$view = new FileTemplate('stats/stats_tables.tpl');

			$view->put_all(array(
				'C_REFERER'    => true,
				'FULL_URL'     => $row['url'] . $row['relative_url'],
				'RELATIVE_URL' => $row['url'] . $row['relative_url'],
				'TOTAL_VISIT'  => $row['total_visit'],
				'AVERAGE'      => $trend_parameters['average'],
				'LAST_UPDATE'  => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
				'C_PICTURE'    => !empty($trend_parameters['picture']),
				'PICTURE'      => $trend_parameters['picture'],
				'SIGN'         => $trend_parameters['sign'],
				'TREND'        => $trend_parameters['trend'],
			));

			echo $view->display();
		}
		$result->dispose();
	}
}
elseif (!empty($stats_keyword))
{
	$idkeyword = $request->get_getint('id', 0);
	$keyword = '';

	try {
		$keyword = $db_querier->get_column_value(StatsSetup::$stats_referer_table, 'relative_url', 'WHERE id = :id', array('id' => $idkeyword));
	} catch (RowNotFoundException $e) {}

	if ($keyword)
	{
		$result = $db_querier->select("SELECT url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
			FROM " . StatsSetup::$stats_referer_table. "
			WHERE relative_url = :url AND type = 1
			ORDER BY total_visit DESC", array(
				'url' => $keyword
		));
		while ($row = $result->fetch())
		{
			$trend_parameters = StatsDisplayService::get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);

			$view = new FileTemplate('stats/stats_tables.tpl');

			$view->put_all(array(
				'FULL_URL'    => $row['url'],
				'TOTAL_VISIT' => $row['total_visit'],
				'AVERAGE'     => $trend_parameters['average'],
				'LAST_UPDATE' => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
				'C_PICTURE'   => !empty($trend_parameters['picture']),
				'PICTURE'     => $trend_parameters['picture'],
				'SIGN'        => $trend_parameters['sign'],
				'TREND'       => $trend_parameters['trend'],
			));

			echo $view->display();
		}
		$result->dispose();
	}
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
