<?php
/*##################################################
 *                                pages_cache.php
 *                            -------------------
 *   begin                : August 08, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

function generate_module_file_pages()
{
	global $sql;
	
	//Catégories des pages
	$config = 'global $_PAGES_CATS;' . "\n";
	$config .= '$_PAGES_CATS = array();' . "\n";
	$result = $sql->query_while("SELECT c.id, c.id_parent, c.id_page, p.title, p.auth
	FROM ".PREFIX."pages_cats c
	LEFT JOIN ".PREFIX."pages p ON p.id = c.id_page
	ORDER BY p.title", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$config .= '$_PAGES_CATS[\'' . $row['id'] . '\'] = array(\'id_parent\' => ' . ( !empty($row['id_parent']) ? $row['id_parent'] : '0') . ', \'name\' => ' . var_export($row['title'], true) . ', \'auth\' => ' . var_export(unserialize(stripslashes($row['auth'])), true) . ');' . "\n";
	}

	//Configuration du module de pages
	$code = 'global $_PAGES_CONFIG;' . "\n" . '$_PAGES_CONFIG = array();' . "\n";
	$CONFIG_PAGES = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'pages'", __LINE__, __FILE__));
	$CONFIG_PAGES = is_array($CONFIG_PAGES) ? $CONFIG_PAGES : array();
	foreach($CONFIG_PAGES as $key => $value)
	{
		if( $key != 'auth' )
			$code .= '$_PAGES_CONFIG[\'' . $key . '\'] = \'' . var_export($value, true) . '\';' . "\n";
	}

	$code .=  '$_PAGES_CONFIG[\'auth\'] = ' . var_export(unserialize(stripslashes($CONFIG_PAGES['auth'])), true)  . ';' . "\n";
	$code .= "\n";
	
	return $config . "\n\r" . $code;
}

?>