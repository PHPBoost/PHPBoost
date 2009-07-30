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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

define('PATH_TO_ROOT', '../../..');
define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.

include_once(PATH_TO_ROOT . '/kernel/begin.php');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

if (!empty($_GET['stats_referer'])) //Recherche d'un membre pour envoyer le mp.
{
    $idurl = !empty($_GET['id']) ? numeric($_GET['id']) : '';
    $url = $Sql->query("SELECT url FROM " . DB_TABLE_STATS_REFERER . " WHERE id = '" . $idurl . "'", __LINE__, __FILE__);

    $result = $Sql->query_while("SELECT url, relative_url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
	FROM " . PREFIX . "stats_referer
	WHERE url = '" . addslashes($url) . "' AND type = 0
	ORDER BY total_visit DESC", __LINE__, __FILE__);
    while ($row = $Sql->fetch_assoc($result))
    {
        $average = ($row['total_visit'] / $row['nbr_day']);
        if ($row['yesterday_visit'] > $average)
        {
            $trend_img = 'up.png';
            $sign = '+';
            $trend = number_round((($row['yesterday_visit'] * 100) / $average), 1) - 100;
        }
        elseif ($row['yesterday_visit'] < $average)
        {
            $trend_img = 'down.png';
            $sign = '-';
            $trend = 100 - number_round((($row['yesterday_visit'] * 100) / $average), 1);
        }
        else
        {
            $trend_img = 'right.png';
            $sign = '+';
            $trend = 0;
        }

        echo '<table style="width:100%;border:none;border-collapse:collapse;">
			<tr>
				<td style="text-align:center;">		
					<a href="' . $row['url'] . $row['relative_url'] . '">' . $row['relative_url'] . '</a>					
				</td>
				<td style="width:112px;text-align:center;">
					' . $row['total_visit'] . '
				</td>
				<td style="width:112px;text-align:center;">
					' . number_round($average, 1) . '
				</td>
				<td style="width:102px;text-align:center;">
					' . gmdate_format('date_format_short', $row['last_update']) . '
				</td>
				<td style="width:105px;">
					<img src="../templates/' . get_utheme() . '/images/admin/' . $trend_img . '" alt="" class="valign_middle" /> (' . $sign . $trend . '%)
				</td>
			</tr>
		</table>';
    }
    $Sql->query_close($result);

    $Sql->close(); //Fermeture de mysql*/
}
elseif (!empty($_GET['stats_keyword'])) //Recherche d'un membre pour envoyer le mp.
{
    $idkeyword = !empty($_GET['id']) ? numeric($_GET['id']) : '';
    $keyword = $Sql->query("SELECT relative_url FROM " . DB_TABLE_STATS_REFERER . " WHERE id = '" . $idkeyword . "'", __LINE__, __FILE__);

    $result = $Sql->query_while("SELECT url, total_visit, today_visit, yesterday_visit, nbr_day, last_update
	FROM " . PREFIX . "stats_referer
	WHERE relative_url = '" . addslashes($keyword) . "' AND type = 1
	ORDER BY total_visit DESC", __LINE__, __FILE__);
    while ($row = $Sql->fetch_assoc($result))
    {
        $average = ($row['total_visit'] / $row['nbr_day']);
        if ($row['yesterday_visit'] > $average)
        {
            $trend_img = 'up.png';
            $sign = '+';
            $trend = number_round((($row['yesterday_visit'] * 100) / $average), 1) - 100;
        }
        elseif ($row['yesterday_visit'] < $average)
        {
            $trend_img = 'down.png';
            $sign = '-';
            $trend = 100 - number_round((($row['yesterday_visit'] * 100) / $average), 1);
        }
        else
        {
            $trend_img = 'right.png';
            $sign = '+';
            $trend = 0;
        }

        echo '<table style="width:100%;border:none;border-collapse:collapse;">
			<tr>
				<td style="text-align:center;">		
					' . ucfirst($row['url']) . '					
				</td>
				<td style="width:112px;text-align:center;">
					' . $row['total_visit'] . '
				</td>
				<td style="width:112px;text-align:center;">
					' . number_round($average, 1) . '
				</td>
				<td style="width:102px;text-align:center;">
					' . gmdate_format('date_format_short', $row['last_update']) . '
				</td>
				<td style="width:105px;">
					<img src="../templates/' . get_utheme() . '/images/admin/' . $trend_img . '" alt="" class="valign_middle" /> (' . $sign . $trend . '%)
				</td>
			</tr>
		</table>';
    }
    $Sql->query_close($result);

    $Sql->close(); //Fermeture de mysql*/
}

?>