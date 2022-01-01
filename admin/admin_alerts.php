<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 2.0 - 2008 08 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['admin.alerts'] . ' - ' . $lang['admin.administration']);
require_once('../admin/admin_header.php');

$view = new FileTemplate('admin/admin_alerts.tpl');
$view->add_lang($lang);

define('NUM_ALERTS_PER_PAGE', 20);

// Management of sorting criteria
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

// Get alerts list
$alerts_list = AdministratorAlertService::get_all_alerts($criteria, $order, ($page - 1) * NUM_ALERTS_PER_PAGE, NUM_ALERTS_PER_PAGE);
foreach ($alerts_list as $alert)
{
	$icon = '';

	switch ($alert->get_priority())
	{
		case AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY:
			$icon = 'fa fa-exclamation-triangle';
			$priority_css_class = 'alert-very-high-priority';
			break;
		case AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY:
			$icon = 'fa fa-exclamation-triangle';
			$priority_css_class = 'alert-high-priority';
			break;
		case AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY:
			$priority_css_class = 'alert-medium-priority';
			break;
		case AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY:
			$priority_css_class = 'alert-low-priority';
			break;
		default:
			$priority_css_class = 'alert-very-low-priority';
			break;
	}

	$creation_date = $alert->get_creation_date();

	$view->assign_block_vars('alerts', array(
		'C_ICON'             => !empty($icon),
		'C_PROCESSED'        => $alert->get_status() == AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED,
		'FIXING_URL'         => Url::to_rel($alert->get_fixing_url()),
		'NAME'               => $alert->get_entitled(),
		'PRIORITY'           => $alert->get_priority_name(),
		'PRIORITY_CSS_CLASS' => $priority_css_class,
		'ICON'               => $icon,
		'DATE'               => $creation_date->format(Date::FORMAT_DAY_MONTH_YEAR_TEXT),
		'ID'                 => $alert->get_id(),
		'STATUS'             => $alert->get_status()
	));
}

$view->put_all(array(
	'C_EXISTING_ALERTS' => ((bool)count($alerts_list)),
	'C_PAGINATION'      => $pagination->has_several_pages(),

	'PAGINATION' => $pagination->display(),

	'U_ORDER_ENTITLED_ASC'       => url('admin_alerts.php?p=' . $page . '&amp;criteria=entitled&amp;order=asc'),
	'U_ORDER_ENTITLED_DESC'      => url('admin_alerts.php?p=' . $page . '&amp;criteria=entitled&amp;order=desc'),
	'U_ORDER_CREATION_DATE_ASC'  => url('admin_alerts.php?p=' . $page . '&amp;criteria=creation_date&amp;order=asc'),
	'U_ORDER_CREATION_DATE_DESC' => url('admin_alerts.php?p=' . $page . '&amp;criteria=creation_date&amp;order=desc'),
	'U_ORDER_PRIORITY_ASC'       => url('admin_alerts.php?p=' . $page . '&amp;criteria=priority&amp;order=asc'),
	'U_ORDER_PRIORITY_DESC'      => url('admin_alerts.php?p=' . $page . '&amp;criteria=priority&amp;order=desc'),
	'U_ORDER_STATUS_ASC'         => url('admin_alerts.php?p=' . $page . '&amp;criteria=current_status&amp;order=asc'),
	'U_ORDER_STATUS_DESC'        => url('admin_alerts.php?p=' . $page . '&amp;criteria=current_status&amp;order=desc'),
));

$view->display();

require_once('../admin/admin_footer.php');
?>
