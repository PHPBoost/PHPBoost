<?php
/*##################################################
 *                               admin_maintain.php
 *                            -------------------
 *   begin                : Februar 07, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
	$maintenance_config = MaintenanceConfig::load();
	$maintain_check = AppContext::get_request()->get_postint('maintain_check', 0);
	switch ($maintain_check)
	{
		case 1:
			$maintain = AppContext::get_request()->get_postint('maintain', 0); //Désactivé par défaut.
			if ($maintain == -1)
			{
				$maintenance_config->enable_maintenance();
				$maintenance_config->set_unlimited_maintenance(true);
			}
			else if ($maintain > 0)
			{
				$date = new Date(DATE_TIMESTAMP, TIMEZONE_USER, time() + 5 + $maintain);
				$maintenance_config->enable_maintenance();
				$maintenance_config->set_unlimited_maintenance(false);
				$maintenance_config->set_end_date($date);
			}
			else
			{
				$maintenance_config->disable_maintenance();
			}
			break;
		case 2:
			$maintain = trim(AppContext::get_request()->get_poststring('end', ''));
			$maintain = strtotimestamp($maintain, $LANG['date_format_short']);
			$date = new Date(DATE_TIMESTAMP, TIMEZONE_USER, $maintain);
			$maintenance_config->enable_maintenance();
			$maintenance_config->set_unlimited_maintenance(false);
			$maintenance_config->set_end_date($date);
			break;
		default:
			$maintenance_config->disable_maintenance();
	}

	$maintenance_config->set_auth(Authorizations::build_auth_array_from_form(1));
	$maintenance_config->set_message(stripslashes(FormatingHelper::strparse(AppContext::get_request()->get_poststring('contents', ''))));
	$maintenance_config->set_display_duration((boolean)AppContext::get_request()->get_postint('display_delay', 0));
	$maintenance_config->set_display_duration_for_admin((boolean)AppContext::get_request()->get_postint('maintain_display_admin', 0));

	MaintenanceConfig::save();

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
else //Sinon on rempli le formulaire
{
	$template = new FileTemplate('admin/admin_maintain.tpl');

	$maintenance_config = MaintenanceConfig::load();

	//Durée de la maintenance.
	$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000, 21600, 25200, 28800, 57600);
	$array_delay = array($LANG['unspecified'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '10 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '3 ' . $LANG['hours'], '4 ' . $LANG['hours'], '5 ' . $LANG['hours'], '6 ' . $LANG['hours'], '7 ' . $LANG['hours'], '8 ' . $LANG['hours'], '16 ' . $LANG['hours']);

	$array_size = count($array_time) - 1;
	if (!$maintenance_config->is_unlimited_maintenance())
	{
		$key_delay = 0;
		$current_time = time();
		$timestamp_end = $maintenance_config->get_end_date()->get_timestamp(TIMEZONE_SYSTEM);
		for ($i = $array_size; $i >= 1; $i--)
		{
			if (($timestamp_end - $current_time) - $array_time[$i] < 0 && ($timestamp_end - $current_time) - $array_time[$i-1] > 0)
			{
				$key_delay = $i-1;
				break;
			}
		}
	}
	else
	$key_delay = 0;

	$delay_maintain_option = '';
	foreach ($array_time as $key => $time)
	{
		$selected = ($key_delay == $key) ? 'selected="selected"' : '' ;
		$delay_maintain_option .= '<option value="' . $time . '" ' . $selected . '>' . $array_delay[$key] . '</option>' . "\n";
	}

	$maintenance_terminates_after_tomorrow = $maintenance_config->get_end_date()->is_posterior_to(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, time() + 86400));
	$check_until = (!$maintenance_config->is_unlimited_maintenance() && $maintenance_terminates_after_tomorrow);
	$template->put_all(array(
		'KERNEL_EDITOR' => display_editor(),
		'DELAY_MAINTAIN_OPTION' => $delay_maintain_option,
		'AUTH_WEBSITE' => Authorizations::generate_select(1, $maintenance_config->get_auth()),
		'MAINTAIN_CONTENTS' => FormatingHelper::unparse($maintenance_config->get_message()),
		'DISPLAY_DELAY_ENABLED' => $maintenance_config->get_display_duration() ? 'checked="checked"' : '',
		'DISPLAY_DELAY_DISABLED' => !$maintenance_config->get_display_duration() ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_ENABLED' => $maintenance_config->get_display_duration_for_admin() ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_DISABLED' => !$maintenance_config->get_display_duration_for_admin() ? 'checked="checked"' : '',
		'MAINTAIN_CHECK_NO' => !$maintenance_config->is_maintenance_enabled() || !$maintenance_config->is_end_date_not_reached() ? ' checked="checked"' : '',
		'MAINTAIN_CHECK_DELAY' => $maintenance_config->is_maintenance_enabled() && $maintenance_config->is_unlimited_maintenance() || ($maintenance_config->is_end_date_not_reached() && !$maintenance_terminates_after_tomorrow) ? ' checked="checked"' : '',
		'MAINTAIN_CHECK_UNTIL' => $maintenance_config->is_maintenance_enabled() && $check_until ? ' checked="checked"' : '',
		'DATE_UNTIL' => $check_until ? gmdate_format('date_format_short', $maintenance_config->get_end_date()->get_timestamp(TIMEZONE_USER)) : '',
		'L_MAINTAIN' => $LANG['maintain'],
		'L_UNTIL' => $LANG['until'],
		'L_DURING' => $LANG['during'],
		'L_SET_MAINTAIN' => $LANG['maintain_for'],
		'L_MAINTAIN_DELAY' => $LANG['maintain_delay'],
		'L_MAINTAIN_DISPLAY_ADMIN' => $LANG['maintain_display_admin'],
		'L_AUTH_WEBSITE' => $LANG['maintain_auth'],
		'L_MAINTAIN_TEXT' => $LANG['maintain_text'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']		
	));

	$template->display();
}

require_once('../admin/admin_footer.php');

?>