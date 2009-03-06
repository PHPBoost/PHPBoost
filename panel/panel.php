<?php
/**
 * panel.php
 *
 * @copyright	(C) 2008 Alain Gandon
 * @email		alain091@gmail.fr
 * @license		GPL
 *
 */

require_once('../kernel/begin.php');
require_once('../panel/panel_begin.php');
require_once('../panel/panel.inc.php');
define('ALTERNATIVE_CSS', serialize(array(
	'news' => 'news'
	)));
require_once('../kernel/header.php');

import('content/syndication/feed');

//Chargement du cache
$Cache->load('panel');

$Template->set_filenames(array(
	'panel' => 'panel/panel.tpl'
));

$Template->assign_vars(array(
	'THEME' => $CONFIG['theme']
	));

$get_feed_menu 	= '';
$get_content	= '';
if (!empty($CONFIG_PANEL)) {
	foreach ($locations as $id => $name) {
		if (!empty($CONFIG_PANEL[$id])) foreach ($CONFIG_PANEL[$id] as $k => $v) {
			if (!empty($MODULES[$v['module']])) {
				import('modules/modules_discovery_service');
				$modulesLoader 	= new ModulesDiscoveryService();
				$module_name 	= $v['module'];
				$module 		= $modulesLoader->get_module($module_name);
				$get_feed_menu 	= get_feed_menu('/syndication.php?m=' . $v['module']);
				$c_feed 		= FALSE;
				switch ($v['type']) {
					default:
					case 'feed':
						$get_content = Feed::get_parsed($module_name, DEFAULT_FEED_NAME, $v['cat'], FALSE, $v['limit_max'], 0);
						$c_feed = TRUE;
						break;
					case 'home':
						if ($module->has_functionnality('get_home_page')) {
							$get_content = $module->functionnality('get_home_page');
						} else {
							$get_content = 'Panel - Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!';
						}
						break;
				}
			} else {
				$get_content = "module ".$v['module']." non installé";
			}
			$Template->assign_block_vars($name, array(
				'NAME' 			=> $v['module'],
				'C_FEED' 		=> $c_feed,
				'GET_FEED_MENU' => $get_feed_menu,
				'GET_CONTENT' 	=> $get_content
				));
		}
	}
}

$Template->pparse('panel');

require_once('../kernel/footer.php');
