<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 02 13
 * @since   	PHPBoost 1.6 - 2008 07 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '../..');

require_once(PATH_TO_ROOT . '/admin/admin_begin.php');
define('TITLE', $LANG['administration']);
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
$tpl = new FileTemplate('admin/updates/updates.tpl');
$updates_availables = 0;

if (ServerConfiguration::get_phpversion() > Updates::PHP_MIN_VERSION_UPDATES)
{
	$update_alerts = AdministratorAlertService::find_by_criteria(null, 'updates');
	$updates = array();
	foreach ($update_alerts as $update_alert)
	{
		// Builds the asked updates (kernel updates, module updates, theme updates or all of them)
		$update = TextHelper::unserialize($update_alert->get_properties());
		if (($update instanceof Application) && ($update_type == '' || $update->get_type() == $update_type))
		{
			if ($update->check_compatibility() && $update_alert->get_status() != Event::EVENT_STATUS_PROCESSED)
			{
				$updates[] = $update;
			}
			else
			{
				// Like the update is incompatible (or has been applied)
				// We set the alert status to processed
				$update_alert->set_status(Event::EVENT_STATUS_PROCESSED);
				AdministratorAlertService::save_alert($update_alert);
			}
		}
	}

	foreach ($updates as $update)
	{
		switch ($update->get_priority())
		{
			case AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY:
				$priority = 'priority_very_high';
				$priority_css_class = 'error';
				break;
			case AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY:
				$priority = 'priority_high';
				$priority_css_class = 'warning';
				break;
			case AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY:
				$priority = 'priority_medium';
				$priority_css_class = 'question';
				break;
			default:
				$priority = 'priority_low';
				$priority_css_class = 'success';
				break;
		}

		$short_description = $update->get_description();
		$maxlength = 300;
		$length = TextHelper::strlen($short_description) > $maxlength ?  $maxlength + TextHelper::strpos(TextHelper::substr($short_description, $maxlength), ' ') : 0;
		$length = $length > ($maxlength * 1.1) ? $maxlength : $length;

		$tpl->assign_block_vars('apps', array(
			'type' => $update->get_type(),
			'name' => $update->get_name(),
			'version' => $update->get_version(),
			'short_description' => ($length > 0 ? TextHelper::substr($short_description, 0, $length) . '...' : $short_description),
			'identifier' => $update->get_identifier(),
			'priority' => $LANG[$priority],
			'priority_css_class' => $priority_css_class,
			'download_url' => $update->get_download_url(),
			'update_url' => $update->get_update_url()
		));
	}

	if ($updates_availables = (count($updates) > 0))
	{
		$tpl->put_all(array(
			'L_UPDATES_ARE_AVAILABLE' => $LANG['updates_are_available'],
			'L_AVAILABLES_UPDATES' => $LANG['availables_updates'],
			'L_TYPE' => $LANG['type'],
			'L_DESCRIPTION' => $LANG['description'],
			'L_PRIORITY' => $LANG['priority'],
			'L_UPDATE_DOWNLOAD' => $LANG['app_update__download'],
			'L_NAME' => $LANG['name'],
			'L_VERSION' => $LANG['version'],
			'L_MORE_DETAILS' => $LANG['more_details'],
			'L_DETAILS' => $LANG['details'],
			'L_DOWNLOAD_PACK' => $LANG['app_update__download_pack'],
			'L_DOWNLOAD_THE_COMPLETE_PACK' => $LANG['download_the_complete_pack'],
			'L_UPDATE_PACK' => $LANG['app_update__update_pack'],
			'L_DOWNLOAD_THE_UPDATE_PACK' => $LANG['download_the_update_pack'],
			'C_ALL' => $update_type == ''
		));

	}
	else
		$tpl->put_all(array('L_NO_AVAILABLES_UPDATES' => $LANG['no_available_update']));
}
else
{
	$tpl->put_all(array(
		'L_INCOMPATIBLE_PHP_VERSION' => sprintf($LANG['incompatible_php_version'], Updates::PHP_MIN_VERSION_UPDATES),
		'C_INCOMPATIBLE_PHP_VERSION' => true,
	));
}

$tpl->put_all(array(
	'L_WEBSITE_UPDATES' => $LANG['website_updates'],
	'L_KERNEL' => $LANG['kernel'],
	'L_MODULES' => $LANG['modules'],
	'L_THEMES' => $LANG['themes'],
	'C_UPDATES' => $updates_availables,
	'U_CHECK' => 'updates.php?check=1' . (!empty($update_type) ? '&amp;type=' . $update_type : '') . '&amp;token=' . AppContext::get_session()->get_token(),
	'L_CHECK_FOR_UPDATES_NOW' => $LANG['check_for_updates_now']
));

$tpl->display();

require_once(PATH_TO_ROOT . '/admin/admin_footer.php');
?>
