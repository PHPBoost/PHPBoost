<?php
/**
 * @package     Ajax
 * @subpackage  Controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 26
 * @since       PHPBoost 2.0 - 2008 08 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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

$request = AppContext::get_request();

$change_status = $request->get_getint('change_status', 0);
$id_to_delete = $request->get_getint('delete', 0);

if ($change_status > 0)
{
	$alert = new AdministratorAlert();

	//If the loading has been successful
	if (($alert = AdministratorAlertService::find_by_id($change_status)) != null)
	{
		//We switch the status
		$new_status = $alert->get_status() != AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED ? AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED : AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD;

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
