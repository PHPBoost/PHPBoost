<?php
/**
* admin_panel.php
*
* @author		alain091	
* @copyright	(c) 2009 Alain Gandon
* @license		GPL
*
*/

require_once('../admin/admin_begin.php');
require_once('../panel/panel_begin.php');
require_once('../kernel/modules.inc.php');
require_once('../admin/admin_header.php');

if( !empty($_POST['valid'])  )
{
	$config_panel = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'panel'", __LINE__, __FILE__));
	$config_panel = empty($config_panel) ? array() : $config_panel;
	
	$location 		= retrieve(POST, 'panel_location', 0, TINTEGER);
	$panel_module 	= _clean_data(retrieve(POST, 'panel_module', '', TNONE));
	$tmp 			= explode( '-', $panel_module);
	if (empty($tmp[0])) $tmp[0] = 'feed';
	if (empty($tmp[1])) $tmp[1] = 'news';
	$panel 				= array();
	$panel['module'] 	= $tmp[1];
	$panel['type'] 		= $tmp[0];
	$panel['cat'] 		= retrieve(POST, 'panel_cat', 0, TINTEGER);
	$panel['limit_max'] = retrieve(POST, 'panel_limit_max', 0, TINTEGER);
	
	$config_panel[$location][] = $panel;
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . $Sql->escape(serialize($config_panel)) . "' WHERE name = 'panel'", __LINE__, __FILE__);

	//Régénération du cache
	$Cache->Generate_module_file('panel');
	
	redirect(HOST . SCRIPT);	
}
elseif( !empty($_GET['delete']) )
{
	$tmp 	= retrieve(GET, 'delete', '');
	$value 	= explode('-',$tmp);
	
	$Cache->load('panel');
	
	if (!empty($CONFIG_PANEL)) {
		$key 	= intval($value[0]);
		$module = $value[1];
		if (!empty($CONFIG_PANEL[$key])) foreach ($CONFIG_PANEL[$key] as $k => $v) {
			if ($v['module'] == $module) {
				unset($CONFIG_PANEL[$key][$k]);
			}
		}
	}
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . $Sql->escape(serialize($CONFIG_PANEL)) . "' WHERE name = 'panel'", __LINE__, __FILE__);

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
					'LOCATION' 		=> $key,
					'NAME' 			=> $v['type'] . '->' . $v['module'],
					'ID'			=> $v['module'],
					'U_DELETE_IMG'	=> '../templates/base/images/' . get_ulang() . '/delete.png'
				));
			}
		}
	}
	
	import('modules/modules_discovery_service');
	$discovery = new ModulesDiscoveryService();
	$modules_get_feed_data = $discovery->get_available_modules('get_feed_data_struct');
	$modules_get_home_page = $discovery->get_available_modules('get_home_page');
	
	$modules_feed = $modules_get_feed_data;
	$modules_home = $modules_get_home_page;
	
	$table_cats = "\n";
	foreach($modules_feed as $module) {
		$the_id 	= 'feed-' . $module->id;
		$the_name 	= 'feed -> '.$module->name;
		
		$Template->assign_block_vars('options_module', array(
			'ID' => $the_id,
			'NAME' => $the_name
			));
		
		if ($module->has_functionnality('get_cat')) {
			$cats = $module->functionnality('get_cat');
		} else {
			$cats = array();
		}
		$count = count($cats);
		$table_cats .= "table_cats[\"" . $the_id . "\"] = new Array(".$count.");\n";
		$i = 0;
		foreach($cats as $key => $value) {
			$table_cats .= 'table_cats["' . $the_id . "\"][".$i."] = new Cat(" . $key . ",\"" . $value . "\");\n";
			$i++;
		}
	}
	
	foreach($modules_home as $module) {
		$the_id 	= 'home-' . $module->id;
		$the_name 	= 'home -> '.$module->name;
		
		$Template->assign_block_vars('options_module', array(
			'ID' 	=> $the_id,
			'NAME' 	=> $the_name
			));
	}
	
	foreach($locations as $k => $v) {
		$Template->assign_block_vars('options_location', array(
			'ID' 	=> $k,
			'NAME' 	=> Lang::get('panel_'.$v)
			));
	}
	
	$Template->assign_vars(array(
		'L_PANEL' 			=> Lang::get('title_panel'),
		'L_PANEL_CONFIG' 	=> Lang::get('panel_config'),
		'L_PANEL_LEGEND' 	=> Lang::get('panel_legend'),
		'L_UPDATE' 			=> Lang::get('update'),
		'L_RESET' 			=> Lang::get('reset'),
		'TABLE_CATS' 		=> $table_cats,
		'LIMIT' 			=> 10,
		'L_NONE'			=> Lang::get('panel_none'),
		'L_ALL'				=> lang::get('panel_all'),
		'L_TOP'				=> Lang::get('panel_top'),
		'L_ABOVE_LEFT'		=> Lang::get('panel_aboveleft'),
		'L_ABOVE_RIGHT'		=> Lang::get('panel_aboveright'),
		'L_CENTER'			=> Lang::get('panel_center'),
		'L_BELOW_LEFT'		=> Lang::get('panel_belowleft'),
		'L_BELOW_RIGHT'		=> Lang::get('panel_belowright'),
		'L_BOTTOM'			=> Lang::get('panel_bottom'),
		'L_MODULE'			=> Lang::get('panel_module'),
		'L_LOCATION'		=> Lang::get('panel_location'),
		'L_CATEGORY'		=> Lang::get('panel_category'),
		'L_LIMIT'			=> Lang::get('panel_limit')	
		));
	
	$Template->pparse('admin_panel');
}

require_once('../admin/admin_footer.php');
