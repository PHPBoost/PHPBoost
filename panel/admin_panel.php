<?php
/**
 *                               admin_panel.php
 *                            -------------------
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 **/

require_once('../admin/admin_begin.php');
load_module_lang('panel'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$locations = array (10 => 'top', 20 => 'aboveleft', 30 => 'aboveright', 40 => 'center', 50 => 'belowleft', 60 => 'belowright', 70 => 'bottom');

if( !empty($_POST['valid'])  )
{
	$config_panel = sunserialize($Sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'panel'", __LINE__, __FILE__));
	$config_panel = empty($config_panel) ? array() : $config_panel;
	
	$location = retrieve(POST, 'panel_location', 0);
	$panel['module'] = retrieve(POST, 'panel_module', '');
	$panel['type'] = retrieve(POST, 'panel_type', 0);
	$panel['cat'] = retrieve(POST, 'panel_cat', 0);
	$panel['limit_max'] = retrieve(POST, 'panel_limit_max', 0);
	
	$config_panel[$location][] = $panel;
	
	$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_panel)) . "' WHERE name = 'panel'", __LINE__, __FILE__);

	//Régénération du cache
	$Cache->Generate_module_file('panel');
	
	redirect(HOST . SCRIPT);	
}
elseif( !empty($_GET['delete']) ) {
	$tmp = retrieve(GET, 'delete', '');
	$value = explode('-',$tmp);
	
	$Cache->load('panel');
	
	if (!empty($CONFIG_PANEL)) {
		$key = intval($value[0]);
		$module = $value[1];
		if (!empty($CONFIG_PANEL[$key])) foreach ($CONFIG_PANEL[$key] as $k => $v) {
			if ($v['module'] == $module) {
				unset($CONFIG_PANEL[$key][$k]);
			}
		}
	}
	
	$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG_PANEL)) . "' WHERE name = 'panel'", __LINE__, __FILE__);

	//Régénération du cache
	$Cache->Generate_module_file('panel');
	
	redirect(HOST . SCRIPT);	
}
//Sinon on rempli le formulaire
else	
{		
	$Template->set_filenames(array(
		'admin_panel'=> 'panel/admin_panel.tpl'
	));
	
	$Cache->load('panel');

	if (!empty($CONFIG_PANEL)) {
		foreach ($locations as $key => $value) {
			if (!empty($CONFIG_PANEL[$key])) foreach ($CONFIG_PANEL[$key] as $k => $v) {
				$Template->assign_block_vars($value, array(
						'LOCATION' => $key,
						'NAME' => $v['module']
					));
			}
			$Template->assign_block_vars('options_location', array(
					'ID' => $key,
					'NAME' => $value
				));
		}
	}
	
	import('modules/modules_discovery_service');
	$discovery = new ModulesDiscoveryService();
	$modules_get_feed_data_struct = $discovery->get_available_modules('get_feed_data_struct');
	$modules_get_categories = $discovery->get_available_modules('get_categories');
	$modules_get_home_page = $discovery->get_available_modules('get_home_page');
	
	if (count($modules_get_categories) < count($modules_get_home_page)) {
		$modules_tmp1 = array_intersect($modules_get_categories, $modules_get_home_page);
	} else {
		$modules_tmp1 = array_intersect($modules_get_home_page, $modules_get_categories);
	}
	if (count($modules_tmp1) < count($modules_get_feed_data_struct)) {
		$modules_available = array_intersect($modules_tmp1, $modules_get_feed_data_struct);
	} else {
		$modules_available = array_intersect($modules_get_feed_data_struct, $modules_tmp1);
	}
	
	foreach($modules_available as $module) {
		$Template->assign_block_vars('options_module', array(
			'ID' => $module->id,
			'NAME' => $module->name
		));
	}

	$module_name = 'news';
	$module = $discovery->get_module($module_name);
	if ($module->has_functionnality('get_categories')) //Le module implémente bien la fonction.
		$cat_news = $module->functionnality('get_categories');
	elseif (!$no_alert_on_error)
		$Errorh->handler('Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_categories!', E_USER_ERROR, __LINE__, __FILE__);

	var_dump($cat_news); exit;
	
	$Template->assign_vars(array(
		'L_PANEL_LEGEND' => 'Configurer un bloc',
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->pparse('admin_panel');
}

require_once('../admin/admin_footer.php');

?>