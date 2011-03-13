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
	$config_online = array();
	$config_online['online_displayed'] = retrieve(POST, 'online_displayed', 4);
	$config_online['display_order_online'] = retrieve(POST, 'display_order_online', 's.level, s.session_time DESC');

	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_online)) . "' WHERE name = 'online'", __LINE__, __FILE__);

	###### Régénération du cache des online #######
	$Cache->Generate_module_file('online');

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
//Sinon on rempli le formulaire
else
{
	$Template->set_filenames(array(
		'admin_online'=> 'online/admin_online.tpl'
	));

	$Cache->load('online');

	$Template->put_all(array(
		'NBR_ONLINE_DISPLAYED' => !empty($CONFIG_ONLINE['online_displayed']) ? $CONFIG_ONLINE['online_displayed'] : 4,
		'L_ONLINE_CONFIG' => $LANG['online_config'],
		'L_NBR_ONLINE_DISPLAYED' => $LANG['nbr_online_displayed'],
		'L_DISPLAY_ORDER' => $LANG['display_order_online'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));

	$array_order_online = array(
		'u.level DESC' => $LANG['ranks'],
		's.expiry DESC' => $LANG['last_update'],
		'u.level DESC, s.expiry DESC' => $LANG['ranks'] . ' ' . $LANG['and'] . ' ' . $LANG['last_update']
	);
	foreach ($array_order_online as $key => $value)
	{
		$selected = ($CONFIG_ONLINE['display_order_online'] == $key) ? 'selected="selected"' : '' ;
		$Template->assign_block_vars('display_order', array(
			'ORDER' => '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>'
		));
	}

	$Template->pparse('admin_online'); // traitement du modele
}

require_once('../admin/admin_footer.php');

?>