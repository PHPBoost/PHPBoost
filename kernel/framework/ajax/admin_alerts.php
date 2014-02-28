<?php
/*##################################################
 *                              admin_alerts.php
 *                            -------------------
 *   begin                : August 30, 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
require_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location();
require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

//On vérifie la validité du jeton
AppContext::get_session()->csrf_get_protect();

if (!AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
{
    exit;
}

$change_status = retrieve(GET, 'change_status', 0);
$id_to_delete = retrieve(GET, 'delete', 0);

if ($change_status > 0)
{
    $alert = new AdministratorAlert();

    //If the loading has been successful
    if (($alert = AdministratorAlertService::find_by_id($change_status)) != null)
    {
        //We switch the status
        $new_status = $alert->get_status() != Event::EVENT_STATUS_PROCESSED ? Event::EVENT_STATUS_PROCESSED : Event::EVENT_STATUS_UNREAD;

        $alert->set_status($new_status);

        AdministratorAlertService::save_alert($alert);

        echo '1';
    }
    //Error
    else
    {
        echo '0';
    }
}
elseif ($id_to_delete > 0)
{
    $alert = new AdministratorAlert();

    //If the loading has been successful
    if (($alert = AdministratorAlertService::find_by_id($id_to_delete)) != null)
    {
        AdministratorAlertService::delete_alert($alert);
        echo '1';
    }
    //Error
    else
    {
        echo '0';
    }
}

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>