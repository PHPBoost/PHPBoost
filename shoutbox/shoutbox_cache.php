<?php
/*##################################################
 *                                shoutbox_cache.php
 *                            -------------------
 *   begin                : March 12, 2007
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

//Configuration des news
function generate_module_file_shoutbox()
{
	global $sql;
	
	$shoutbox_config = 'global $CONFIG_SHOUTBOX;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_SHOUTBOX = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'shoutbox'", __LINE__, __FILE__));
	$CONFIG_SHOUTBOX = is_array($CONFIG_SHOUTBOX) ? $CONFIG_SHOUTBOX : array();
	foreach($CONFIG_SHOUTBOX as $key => $value)
		if( $key == 'shoutbox_forbidden_tags' )
			$shoutbox_config .= '$CONFIG_SHOUTBOX[\'shoutbox_forbidden_tags\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
		else
			$shoutbox_config .= '$CONFIG_SHOUTBOX[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	
	return $shoutbox_config;
}

?>