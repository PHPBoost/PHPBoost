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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$template = new Template('admin/admin_alerts.tpl');

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/administrator_alert_service.class.php');

$alerts_list = AdministratorAlertService::get_all_alerts();

$template->Assign_vars(array(
	'C_EXISTING_ALERTS' => ((bool)count($alerts_list)),
	'L_ADMIN_ALERTS' => $LANG['administrator_alerts'],
	'L_TYPE' => $LANG['type'],
	'L_DATE' => $LANG['date'],
	'L_PRIORITY' => $LANG['priority'],
	'L_ADMINISTRATOR_ALERTS_LIST' => $LANG['administrator_alerts_list'],
	'L_ACTIONS' => $LANG['administrator_alerts_action'],
	'L_NO_ALERT' => $LANG['no_administrator_alert'],
	'L_CONFIRM_DELETE_ALERT' => $LANG['confirm_delete_administrator_alert']
));

define('NUM_ALERTS_PER_PAGE', 20);

//On va chercher la liste des alertes en attente, on afficher les 5 dernières
foreach(AdministratorAlertService::get_all_alerts('creation_date', 'desc', 0, 5) as $alert)
{
	$img_type = '';
	
	switch($alert->get_priority())
	{
		case PRIORITY_VERY_LOW:
			$color = 'FFFFFF';
			break;
		case PRIORITY_LOW:
			$color = 'ECDBB7';
			break;
		case PRIORITY_MEDIUM:
			$color = 'F5D5C6';
			break;
		case PRIORITY_HIGH:
			$img_type = 'important.png';
			$color = 'FFD5D1';
			break;
		case PRIORITY_VERY_HIGH:
			$img_type = 'errors_mini.png';
			$color = 'F3A29B';
			break;
		default:
		$color = 'FFFFFF';
	}
	
	$creation_date = $alert->get_creation_date();
	
	$template->Assign_block_vars('alerts', array(
		'C_PROCESSED' => $alert->get_status() == CONTRIBUTION_STATUS_PROCESSED,
		'URL' => $alert->get_fixing_url(),
		'NAME' => $alert->get_entitled(),
		'PRIORITY' => $alert->get_priority_name(),
		'STYLE' => 'background:#' . $color . ';',
		'IMG' => !empty($img_type) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/admin/' . $img_type . '" alt="" class="valign_middle" />' : '',
		'DATE' => $creation_date->format(DATE_FORMAT),
		'ID' => $alert->get_id(),
		'STATUS' => $alert->get_status()
	));
}
	
$template->parse();

require_once('../admin/admin_footer.php');

?>