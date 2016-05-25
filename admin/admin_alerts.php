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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$template = new FileTemplate('admin/admin_alerts.tpl');

define('NUM_ALERTS_PER_PAGE', 20);

//Gestion des critères de tri
$criteria = retrieve(GET, 'criteria', 'current_status');
$order = retrieve(GET, 'order', 'asc');

if (!in_array($criteria, array('entitled', 'current_status', 'creation_date', 'priority')))
	$criteria = 'current_status';
$order = $order == 'desc' ? 'desc' : 'asc';

$page = AppContext::get_request()->get_getint('p', 1);
$pagination = new ModulePagination($page, AdministratorAlertService::get_number_alerts(), NUM_ALERTS_PER_PAGE);
$pagination->set_url(new Url('/admin/admin_alerts.php?p=%d&criteria=' . $criteria . '&order=' . $order));

if ($pagination->current_page_is_empty() && $page > 1)
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

//On va chercher la liste des alertes
$alerts_list = AdministratorAlertService::get_all_alerts($criteria, $order, ($page - 1) * NUM_ALERTS_PER_PAGE, NUM_ALERTS_PER_PAGE);
foreach ($alerts_list as $alert)
{
	$img_class = '';
	
	switch ($alert->get_priority())
	{
		case AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY:
			$color = 'FFFFFF';
			break;
		case AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY:
			$color = 'ECDBB7';
			break;
		case AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY:
			$color = 'F5D5C6';
			break;
		case AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY:
			$img_class = 'fa fa-warning';
			$color = 'FFD5D1';
			break;
		case AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY:
			$img_class = 'fa fa-warning';
			$color = 'F3A29B';
			break;
		default:
		$color = 'FFFFFF';
	}
	
	$creation_date = $alert->get_creation_date();
	
	$template->assign_block_vars('alerts', array(
		'C_PROCESSED' => $alert->get_status() == AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED,
		'FIXING_URL' => Url::to_rel($alert->get_fixing_url()),
		'NAME' => $alert->get_entitled(),
		'PRIORITY' => $alert->get_priority_name(),
		'STYLE' => 'background:#' . $color . ';',
		'IMG' => !empty($img_class) ? '<i class="' . $img_class . '"></i>' : '',
		'DATE' => $creation_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
		'ID' => $alert->get_id(),
		'STATUS' => $alert->get_status()
	));
}

$template->put_all(array(
	'C_EXISTING_ALERTS' => ((bool)count($alerts_list)),
	'C_PAGINATION' => $pagination->has_several_pages(),
	'PAGINATION' => $pagination->display(),
	'L_ADMIN_ALERTS' => $LANG['administrator_alerts'],
	'L_TYPE' => $LANG['type'],
	'L_DATE' => LangLoader::get_message('date', 'date-common'),
	'L_PRIORITY' => $LANG['priority'],
	'L_ADMINISTRATOR_ALERTS_LIST' => $LANG['administrator_alerts_list'],
	'L_ACTIONS' => $LANG['administrator_alerts_action'],
	'L_NO_ALERT' => $LANG['no_administrator_alert'],
	'L_CONFIRM_DELETE_ALERT' => $LANG['confirm_delete_administrator_alert'],
	'L_DELETE' => LangLoader::get_message('delete', 'common'),
	'L_FIX' => $LANG['admin_alert_fix'],
	'L_UNFIX' => $LANG['admin_alert_unfix'],
	'C_ORDER_ENTITLED_ASC' => $criteria == 'entitled' && $order == 'asc',
	'U_ORDER_ENTITLED_ASC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=entitled&amp;order=asc'),
	'C_ORDER_ENTITLED_DESC' => $criteria == 'entitled' && $order == 'desc',
	'U_ORDER_ENTITLED_DESC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=entitled&amp;order=desc'),
	'C_ORDER_CREATION_DATE_ASC' => $criteria == 'creation_date' && $order == 'asc',
	'U_ORDER_CREATION_DATE_ASC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=creation_date&amp;order=asc'),
	'C_ORDER_CREATION_DATE_DESC' => $criteria == 'creation_date' && $order == 'desc',
	'U_ORDER_CREATION_DATE_DESC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=creation_date&amp;order=desc'),
	'C_ORDER_PRIORITY_ASC' => $criteria == 'priority' && $order == 'asc',
	'U_ORDER_PRIORITY_ASC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=priority&amp;order=asc'),
	'C_ORDER_PRIORITY_DESC' => $criteria == 'priority' && $order == 'desc',
	'U_ORDER_PRIORITY_DESC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=priority&amp;order=desc'),
	'C_ORDER_STATUS_ASC' => $criteria == 'current_status' && $order == 'asc',
	'U_ORDER_STATUS_ASC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=current_status&amp;order=asc'),
	'C_ORDER_STATUS_DESC' => $criteria == 'current_status' && $order == 'desc',
	'U_ORDER_STATUS_DESC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=current_status&amp;order=desc')

));

$template->display();

require_once('../admin/admin_footer.php');
?>