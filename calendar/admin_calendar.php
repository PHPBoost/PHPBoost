<?php
/*##################################################
 *                               admin_calendar.php
 *                            -------------------
 *   begin                : March 13, 2007
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
load_module_lang('calendar'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('calendar_constants.php');
##########################admin_calendar.tpl###########################
if (!empty($_POST['valid']) )
{
	$calendar_config = CalendarConfig::load();
	
	$calendar_config->set_authorization(Authorizations::build_auth_array_from_form(AUTH_CALENDAR_READ, AUTH_CALENDAR_WRITE, AUTH_CALENDAR_MODO));
	
	CalendarConfig::save();

	AppContext::get_response()->redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template = new FileTemplate('calendar/admin_calendar_config.tpl');

	$calendar_config = CalendarConfig::load();

	$Template->assign_vars(array(
		'L_REQUIRE' => $LANG['require'],	
		'L_CALENDAR' => $LANG['title_calendar'],
		'L_CALENDAR_CONFIG' => $LANG['calendar_config'],
		'L_AUTH_WRITE' => $LANG['rank_post'],
		'L_AUTH_READ' => $LANG['rank_read'],
		'L_AUTH_MODO' => $LANG['rank_modo'],
		'L_UPDATE' => $LANG['update'],
		'L_ERASE' => $LANG['erase'],
		'AUTH_READ' => Authorizations::generate_select(AUTH_CALENDAR_READ,$calendar_config->get_authorization()),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_CALENDAR_WRITE,$calendar_config->get_authorization()),
		'AUTH_MODO' => Authorizations::generate_select(AUTH_CALENDAR_MODO,$calendar_config->get_authorization()),
		));

	$Template->display();
}

require_once('../admin/admin_footer.php');

?>