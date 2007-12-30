<?php
/*##################################################
 *                                online_cache.php
 *                            -------------------
 *   begin                : July 03, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
###################################################*/

if( defined('PHP_BOOST') !== true) exit;

//Configuration du livre d'or
function generate_module_file_online()
{
	global $sql;
	
	$online_config = 'global $CONFIG_ONLINE;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_ONLINE = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'online'", __LINE__, __FILE__));
	$CONFIG_ONLINE = is_array($CONFIG_ONLINE) ? $CONFIG_ONLINE : array();
	foreach($CONFIG_ONLINE as $key => $value)
		$online_config .= '$CONFIG_ONLINE[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	
	return $online_config;
}

?>