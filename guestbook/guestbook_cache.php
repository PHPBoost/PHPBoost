<?php
/*##################################################
 *                                guestbook_cache.php
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

if( defined('PHPBOOST') !== true) exit;

//Configuration du livre d'or
function generate_module_file_guestbook()
{
	global $Sql;
	
	$guestbook_config = 'global $CONFIG_GUESTBOOK;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_GUESTBOOK = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'guestbook'", __LINE__, __FILE__));
	$CONFIG_GUESTBOOK = is_array($CONFIG_GUESTBOOK) ? $CONFIG_GUESTBOOK : array();
	foreach($CONFIG_GUESTBOOK as $key => $value)
		if( $key == 'guestbook_forbidden_tags' )
			$guestbook_config .= '$CONFIG_GUESTBOOK[\'guestbook_forbidden_tags\'] = ' . var_export(unserialize($value), 1) . ';' . "\n";
		else
			$guestbook_config .= '$CONFIG_GUESTBOOK[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	
	return $guestbook_config;
}

?>