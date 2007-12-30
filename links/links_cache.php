<?php
/*##################################################
 *                                links_cache.php
 *                            -------------------
 *   begin                : November 23, 2006
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

//Gestion des liens
function generate_module_file_links()
{
	global $sql;
	
	$code = 'global $_array_link;global $LANG;' . "\n" . '$_array_link = array(' . "\n";
	$result = $sql->query_while("SELECT name, url, activ, secure, sep 
	FROM ".PREFIX."links 
	WHERE activ = 1 
	ORDER BY class", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$code .= 'array(\'name\' => ' . var_export($row['name'], true) . ',' . "\r\n" .
		'\'url\' => ' . var_export($row['url'], true) . ',' . "\r\n" .
		'\'secure\' => ' . var_export($row['secure'], true) . ',' . "\r\n" .
		'\'sep\' => ' . var_export($row['sep'], true) . '),' . "\r\n";
	}
	$sql->close($result);
	$code .= ');';
	return $code;
}	
?>