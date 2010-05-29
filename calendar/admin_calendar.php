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
require_once('calendar_constante.php');
##########################admin_calendar.tpl###########################
if (!empty($_POST['valid']) )
{
	$CONFIG_CALENDAR = array(
		'calendar_auth' => Authorizations::build_auth_array_from_form(AUTH_CALENDAR_READ)
	);

	PersistenceContext::get_sql()->query("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG_CALENDAR)) . "' WHERE name = 'calendar'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$Cache->Generate_module_file('calendar');
	
	AppContext::get_response()->redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template = new FileTemplate('calendar/admin_calendar_config.tpl');

	$Cache->load('calendar');
	
	$Template->assign_vars(array(
		'L_REQUIRE' => $LANG['require'],	
		'L_CALENDAR' => $LANG['title_calendar'],
		'L_CALENDAR_CONFIG' => $LANG['calendar_config'],
		'L_RANK' => $LANG['rank_post'],
		'L_UPDATE' => $LANG['update'],
		'L_ERASE' => $LANG['erase'],
		'AUTH_READ' => Authorizations::generate_select(AUTH_CALENDAR_READ,$CONFIG_CALENDAR['calendar_auth']),
	));

	$Template->display();
}

require_once('../admin/admin_footer.php');

?>