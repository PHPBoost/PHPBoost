<?php
/*##################################################
 *                               admin_online.php
 *                            -------------------
 *   begin                : July 03, 2007
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
load_module_lang('online'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['valid']))
{
	$online_config = OnlineConfig::load();
	$online_config->set_display_order(retrieve(POST, 'display_order_online', 's.level, s.session_time DESC'));
	$online_config->set_number_member_displayed(retrieve(POST, 'online_displayed', 4));
	OnlineConfig::save();
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_online'=> 'online/admin_online.tpl'
	));
	
	$online_config = OnlineConfig::load();
	
	$Template->put_all(array(
		'NBR_ONLINE_DISPLAYED' => $online_config->get_number_member_displayed(),
		'L_ONLINE_CONFIG' => $LANG['online_config'],
		'L_NBR_ONLINE_DISPLAYED' => $LANG['nbr_online_displayed'],
		'L_DISPLAY_ORDER' => $LANG['display_order_online'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$array_order_online = array(
		OnlineConfig::LEVEL_DISPLAY_ORDER => $LANG['ranks'], 
		OnlineConfig::SESSION_TIME_DISPLAY_ORDER => $LANG['last_update'], 
		OnlineConfig::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER => $LANG['ranks'] . ' ' . $LANG['and'] . ' ' . $LANG['last_update']
	);
	foreach ($array_order_online as $key => $value)
	{
		$selected = ($online_config->get_display_order() == $key) ? 'selected="selected"' : '' ;
		$Template->assign_block_vars('display_order', array(
			'ORDER' => '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>'
		));
	}

	$Template->pparse('admin_online'); // traitement du modele	
}

require_once('../admin/admin_footer.php');

?>