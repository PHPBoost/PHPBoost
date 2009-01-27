<?php
/**
* admin_panel.php
*
* @author alain091	
* @copyright (c) 2009 alain091
* @license GPL
*
*/

require_once('../admin/admin_begin.php');
load_module_lang('panel'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$locations = array (10 => 'top', 20 => 'aboveleft', 30 => 'aboveright', 40 => 'center', 50 => 'belowleft', 60 => 'belowright', 70 => 'bottom');

if( !empty($_POST['valid'])  )
{
	$config_panel = sunserialize($Sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'panel'", __LINE__, __FILE__));
	$config_panel = empty($config_panel) ? array() : $config_panel;
	
	$location = retrieve(POST, 'panel_location', 0, TINTEGER);
	$panel_module = retrieve(POST, 'panel_module', '', TNONE);
	$tmp = explode( '-', $panel_module);
	if (empty($tmp[0])) $tmp[0] = 'feed';
	if (empty($tmp[1])) $tmp[1] = 'news';	
	$panel['module'] = $tmp[1];
	$panel['type'] = $tmp[0];
	$panel['cat'] = retrieve(POST, 'panel_cat', 0, TINTEGER);
	$panel['limit_max'] = retrieve(POST, 'panel_limit_max', 0, TINTEGER);
	
	$config_panel[$location][] = $panel;
	
	$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . $Sql->escape(serialize($config_panel)) . "' WHERE name = 'panel'", __LINE__, __FILE__);

	//Régénération du cache
	$Cache->Generate_module_file('panel');
	
	redirect(HOST . SCRIPT);	
}
elseif( !empty($_GET['delete']) )
{
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
		$the_id = 'feed-' . $module->id;
		$the_name = 'feed -> '.$module->name;
		
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
		$the_id = 'home-' . $module->id;
		$the_name = 'home -> '.$module->name;
		
		$Template->assign_block_vars('options_module', array(
			'ID' => $the_id,
			'NAME' => $the_name
			));
	}
	
	foreach($locations as $k => $v) {
		$Template->assign_block_vars('options_location', array(
			'ID' => $k,
			'NAME' => $v
		));
	}
	
	$Template->assign_vars(array(
		'L_PANEL_LEGEND' => 'Configurer un bloc',
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'TABLE_CATS' => $table_cats,
		'LIMIT' => 10
	));
	
	$Template->pparse('admin_panel');
}

require_once('../admin/admin_footer.php');

?>