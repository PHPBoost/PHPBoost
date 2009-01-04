<?php
/**
*                              panel.php
 *                            -------------------
 *   begin                : October 14, 2008
 *   copyright         : (C) 2008 Alain GANDON based on Guestbook
 *   email                : 
 *
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
**/

require_once('../kernel/begin.php'); 
require_once('../panel/panel_begin.php');
require_once('../kernel/header.php');

import('content/syndication/feed');

//Chargement du cache
$Cache->load('panel');

	$Template->set_filenames(array(
		'panel' => 'panel/panel.tpl'
	));

	$locations = array (10 => 'top', 20 => 'aboveleft', 30 => 'aboveright', 40 => 'center', 50 => 'belowleft', 60 => 'belowright', 70 => 'bottom');
	
	$Template->assign_vars(array(
		'THEME' => $CONFIG['theme']
		));
		
	if (!empty($CONFIG_PANEL)) {
		foreach ($locations as $id => $name) {
			if (!empty($CONFIG_PANEL[$id])) foreach ($CONFIG_PANEL[$id] as $k => $v) {
				if (!empty($MODULES[$v['module']])) {
					$get_feed_menu = get_feed_menu('/'.$v['module'].'/syndication.php');
					if ($v['module'] != 'news') {
						$get_content = Feed::get_parsed($v['module'], DEFAULT_FEED_NAME, 0);
					} else {
						global $Errorh;

						import('modules/modules_discovery_service');
						$modulesLoader = new ModulesDiscoveryService();
						$module_name = 'news';
						$module = $modulesLoader->get_module($module_name);
						if ($module->has_functionnality('get_home_page')) //Le module implémente bien la fonction.
							$tpl_news = $module->functionnality('get_home_page');
						elseif (!$no_alert_on_error)
							$Errorh->handler('Cache -> Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', E_USER_ERROR, __LINE__, __FILE__);
						$get_content = $tpl_news->parse('news');
					}
				} else {
					$get_feed_menu = '';
					$get_content = "module ".$v['module']." non installé";
				}
				$Template->assign_block_vars($name, array(
					'NAME' => $v['module'],
					'GET_FEED_MENU' => $get_feed_menu,
					'GET_CONTENT' => $get_content,
				));
			}
		}
	}	
		
	$Template->pparse('panel');

require_once('../kernel/footer.php'); 

?>