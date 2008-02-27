<?php
/*##################################################
 *                                faq_cache.php
 *                            -------------------
 *   begin                : November 11, 2007
 *   copyright          : (C) 200è Sautel Benoit
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
	global $Sql;
	//Configuration
	$config = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'faq'", __LINE__, __FILE__));
	$root_config = $config['root'];
	unset($config['root']);
	$string = 'global $FAQ_CONFIG, $FAQ_CATS;' . "\n\n";
	$string .= '$FAQ_CONFIG = ' . var_export($config, true) . ';' . "\n\n";
	
	//List of categories and their own properties
	$string .= '$FAQ_CATS = array();' . "\n\n";
	$string .= '$FAQ_CATS[0] = ' . var_export($root_config, true) . ';' . "\n";
	$result = $Sql->Query_while("SELECT id, id_parent, c_order, auth, name, visible, display_mode, image, description
	FROM ".PREFIX."faq_cats
	ORDER BY id_parent, c_order", __LINE__, __FILE__);
	
	while ($row = $Sql->Sql_fetch_assoc($result))
	{
		$string .= '$FAQ_CATS[' . $row['id'] . '] = ' . 
			var_export(array(
				'id_parent' => $row['id_parent'],
				'order' => $row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'display_mode' => $row['display_mode'],
				'num_questions' => $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."faq WHERE idcat = '" . $row['id'] . "'", __LINE__, __FILE__),
				'image' => $row['image'],
				'description' => $row['description'],
				'auth' => unserialize($row['auth'])
				),
			true)
			. ';' . "\n";
	}
	return $string;
}

?>