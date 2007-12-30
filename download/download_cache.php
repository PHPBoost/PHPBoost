<?php
/*##################################################
 *                                download_cache.php
 *                            -------------------
 *   begin                : December 05, 2006
 *   copyright          : (C) 2006 Viarre Régis
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
function generate_module_file_download()
{
	global $sql;
	
	$code = 'global $CAT_DOWNLOAD;' . "\n" . 'global $CONFIG_DOWNLOAD;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_DOWNLOAD = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'download'", __LINE__, __FILE__));
	$CONFIG_DOWNLOAD = is_array($CONFIG_DOWNLOAD) ? $CONFIG_DOWNLOAD : array();
	foreach($CONFIG_DOWNLOAD as $key => $value)
		$code .= '$CONFIG_DOWNLOAD[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	
	$code .= "\n";
	
	$result = $sql->query_while("SELECT id, name, secure
	FROM ".PREFIX."download_cat
	WHERE aprob = 1", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{		
		$code .= '$CAT_DOWNLOAD[\'' . $row['id'] . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n";
		$code .= '$CAT_DOWNLOAD[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
	}
	
	return $code;
}

?>