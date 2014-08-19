<?php
/*##################################################
 *                          member_xmlhttprequest.php
 *                            -------------------
 *   begin                : January, 25 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
/**
* @package ajax
*
*/

define('PATH_TO_ROOT', '../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

include_once(PATH_TO_ROOT . '/stats/stats_functions.php');

$sql_querier = PersistenceContext::get_sql();

if (!empty($_GET['stats_referer'])) //Recherche d'un membre pour envoyer le mp.
{
	$idurl = !empty($_GET['id']) ? NumberHelper::numeric($_GET['id']) : '';
	$url = $sql_querier->query("SELECT url FROM " . StatsSetup::$stats_referer_table . " WHERE id = '" . $idurl . "'");

	$result = $sql_querier->query_while("SELECT url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
	FROM " . PREFIX . "stats_referer
	WHERE url = '" . addslashes($url) . "' AND type = 0
	ORDER BY total_visit DESC");
	while ($row = $sql_querier->fetch_assoc($result))
	{
		$trend_parameters = get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);
		
		echo '<table>
			<tbody>
				<tr>
					<td class="no-separator">
						<a href="' . $row['url'] . $row['relative_url'] . '">' . $row['relative_url'] . '</a>
					</td>
					<td class="no-separator" style="width:60px;">
						' . $row['total_visit'] . '
					</td>
					<td class="no-separator" style="width:60px;">
						' . $trend_parameters['average'] . '
					</td>
					<td class="no-separator" style="width:96px;">
						' . gmdate_format('date_format_short', $row['last_update']) . '
					</td>
					<td class="no-separator" style="width:95px;">
						' . ($trend_parameters['picture'] ? '<i class="fa fa-trend-' . $trend_parameters['picture'] . '"></i> ' : '') . '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)
					</td>
				</tr>
			</tbody>
		</table>';
	}
	$result->dispose();
}
elseif (!empty($_GET['stats_keyword'])) //Recherche d'un membre pour envoyer le mp.
{
	$idkeyword = !empty($_GET['id']) ? NumberHelper::numeric($_GET['id']) : '';
	$keyword = $sql_querier->query("SELECT relative_url FROM " . StatsSetup::$stats_referer_table . " WHERE id = '" . $idkeyword . "'");

	$result = $sql_querier->query_while("SELECT url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
	FROM " . PREFIX . "stats_referer
	WHERE relative_url = '" . addslashes($keyword) . "' AND type = 1
	ORDER BY total_visit DESC");
	while ($row = $sql_querier->fetch_assoc($result))
	{
		$trend_parameters = get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);
		
		echo '<table>
			<tbody>
				<tr>
					<td class="no-separator">
						' . $row['url'] . '
					</td>
					<td class="no-separator" style="width:70px;">
						' . $row['total_visit'] . '
					</td>
					<td class="no-separator" style="width:60px;">
						' . $trend_parameters['average'] . '
					</td>
					<td class="no-separator" style="width:96px;">
						' . gmdate_format('date_format_short', $row['last_update']) . '
					</td>
					<td class="no-separator" style="width:95px;">
						' . ($trend_parameters['picture'] ? '<i class="fa fa-trend-' . $trend_parameters['picture'] . '"></i> ' : '') . '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)
					</td>
				</tr>
			</tbody>
		</table>';
	}
	$result->dispose();
}

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>