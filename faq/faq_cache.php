<?php
/*##################################################
 *                                faq_cache.php
 *                            -------------------
 *   begin                : November 11, 2007
 *   copyright          : (C) 2007 Viarre Régis
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



function generate_module_file_faq()
{
	global $sql;
	//Configuration
	$config = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'faq'", __LINE__, __FILE__));
	$config['root']['auth'] = unserialize(stripslashes($config['root']['auth']));
	$root_config = $config['root'];
	unset($config['root']);
	$string = 'global $FAQ_CONFIG, $FAQ_CATS;' . "\n\n";
	$string .= ' $FAQ_CONFIG = ' . var_export($config, true) . ';' . "\n\n";
	
	//List of categories and their own properties
	$string .= '$FAQ_CATS = array();' . "\n\n";
	$string .= '$FAQ_CATS[0] = ' . var_export($root_config, true) . ';' . "\n";
	$result = $sql->query_while("SELECT id, id_left, id_right, level, auth, name, visible, display_mode, description
	FROM ".PREFIX."faq_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $sql->sql_fetch_assoc($result))
	{
		$string .= '$FAQ_CATS[' . $row['id'] . '] = ' . 
			var_export(array(
				'id_left' => $row['id_left'],
				'id_right' => $row['id_right'],
				'level' => $row['level'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => $row['visible'],
				'display_mode' => $row['display_mode'],
				'num_questions' => $sql->query("SELECT COUNT(*) FROM ".PREFIX."faq WHERE idcat = '" . $row['id'] . "'", __LINE__, __FILE__),
				'description' => $row['description'],
				'auth' => unserialize($row['auth'])
				),
			true)
			. ';' . "\n";
	}
	return $string;
}

?>