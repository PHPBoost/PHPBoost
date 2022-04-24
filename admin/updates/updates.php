<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 24
 * @since       PHPBoost 1.6 - 2008 07 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

require_once(PATH_TO_ROOT . '/admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['admin.updates'] . ' - ' . $lang['admin.administration']);
require_once(PATH_TO_ROOT . '/admin/admin_header.php');

$check_updates = retrieve(GET, 'check', false);
$update_type = retrieve(GET, 'type', '');
if (!in_array($update_type, array('', 'kernel', 'module', 'template')))
{
	$update_type = '';
}

if ($check_updates === true)
{
	AppContext::get_session()->csrf_get_protect();
	new Updates();
	AppContext::get_response()->redirect('updates.php' . (!empty($update_type) ? '?type=' . $update_type : ''));
}
$view = new FileTemplate('admin/updates/updates.tpl');
$view->add_lang($lang);
$updates_availables = 0;

if (ServerConfiguration::get_phpversion() > Updates::PHP_MIN_VERSION_UPDATES)
{
	$update_alerts = AdministratorAlertService::find_by_criteria(null, 'updates');
	$updates = array();
	foreach ($update_alerts as $update_alert)
	{
		// Builds the asked updates (kernel updates, module updates, theme updates or all of them)
		$update = TextHelper::unserialize($update_alert->get_alert_properties());
		if (($update instanceof Application) && ($update_type == '' || $update->get_type() == $update_type))
		{
			if ($update->check_compatibility() && $update_alert->get_status() != AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED)
			{
				$updates[] = $update;
			}
			else
			{
				// Like the update is incompatible (or has been applied)
				// We set the alert status to processed
				$update_alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED);
				AdministratorAlertService::save_alert($update_alert);
			}
		}
	}

	foreach ($updates as $update)
	{
		switch ($update->get_priority())
		{
			case AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY:
				$priority = 'admin.priority.very.high';
				$priority_css_class = 'error';
				break;
			case AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY:
				$priority = 'admin.priority.high';
				$priority_css_class = 'warning';
				break;
			case AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY:
				$priority = 'admin.priority.medium';
				$priority_css_class = 'success';
				break;
			case AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY:
				$priority = 'admin.priority.low';
				$priority_css_class = 'question';
				break;
			default:
				$priority = 'admin.priority.very.low';
				$priority_css_class = 'notice';
				break;
		}

		$short_description = $update->get_description();
		$maxlength = 300;
		$length = TextHelper::strlen($short_description) > $maxlength ?  $maxlength + TextHelper::strpos(TextHelper::substr($short_description, $maxlength), ' ') : 0;
		$length = $length > ($maxlength * 1.1) ? $maxlength : $length;

		$view->assign_block_vars('apps', array(
			'TYPE'               => $update->get_type(),
			'NAME'               => $update->get_name(),
			'VERSION'            => $update->get_version(),
			'SHORT_DESCRIPTION'  => ($length > 0 ? TextHelper::substr($short_description, 0, $length) . '...' : $short_description),
			'IDENTIFIER'         => $update->get_identifier(),
			'PRIORITY_CSS_CLASS' => $priority_css_class,

			'L_PRIORITY'           => $lang[$priority],
		));

		$updates_availables++;
	}
}
else
{
	$view->put_all(array(
		'C_INCOMPATIBLE_PHP_VERSION' => true,
		'L_INCOMPATIBLE_PHP_VERSION' => sprintf($lang['admin.update.php.version'], Updates::PHP_MIN_VERSION_UPDATES),
	));
}

$server_configuration = new ServerConfiguration();
$view->put_all(array(
	'C_AUTOMATIC_UPDATE_CHECK_AVAILABLE' => ((function_exists('simplexml_load_file') && $server_configuration->has_allow_url_fopen()) || (function_exists('simplexml_load_string') && $server_configuration->has_curl_library())),
	'C_UPDATES'                          => $updates_availables,
	'U_CHECK'                            => 'updates.php?check=1' . (!empty($update_type) ? '&amp;type=' . $update_type : '') . '&amp;token=' . AppContext::get_session()->get_token(),
));

$view->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
