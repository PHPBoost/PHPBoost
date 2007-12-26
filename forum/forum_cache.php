<?php
/*##################################################
 *                                forum_cache.php
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

//Configuration des news
function generate_module_file_forum()
{
	global $sql;
		
	//Configuration du forum
	$forum_config = 'global $CONFIG_FORUM;' . "\n";
	
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_FORUM = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'forum'", __LINE__, __FILE__));
	foreach($CONFIG_FORUM as $key => $value)
	{	
		if( $key == 'auth' )
			$forum_config .= '$CONFIG_FORUM[\'auth\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
		else
			$forum_config .= '$CONFIG_FORUM[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	}
	
	//Liste des catégories du forum
	$i = 0;
	$forum_select = 'global $CAT_FORUM;' . "\n" . 'function forum_list_cat($userdata){' . "\n" . 'global $groups, $CAT_FORUM;' . "\n" . '$select = \'\';' . "\n";
	$forum_cats = '';
	$result = $sql->query_while("SELECT id, id_left, id_right, level, name, status, aprob, auth, aprob
	FROM ".PREFIX."forum_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{	
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$forum_select .= '$select .= ($groups->check_auth($CAT_FORUM[' . $row['id'] . '][\'auth\'], 1)) ? \'<option value="' . $row['id'] . '">' . $margin . ' ' . str_replace('\'', '\\\'', $row['name']) . '</option>\' : \'\';' . "\n";

		if( empty($row['auth']) )
			$row['auth'] = serialize(array());
			
		$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
		$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
		$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
		$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
		$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'status\'] = ' . var_export($row['status'], true) . ';' . "\n";
		$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
		$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
	}
	$sql->close($result);	
	$forum_select .= ($i > 0) ? 'return $select . \'</optgroup>\';' . "\n" . '}' : 'return $select;' . "\n" . '}';
	
	return $forum_config . $forum_select . "\r\n" . $forum_cats;
}

?>