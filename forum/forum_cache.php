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

if( defined('PHPBOOST') !== true) exit;

//Configuration des news
function generate_module_file_forum()
{
	global $Sql;
		
	//Configuration du forum
	$forum_config = 'global $CONFIG_FORUM;' . "\n";
	
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_FORUM = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'forum'", __LINE__, __FILE__));
	$CONFIG_FORUM['auth'] = unserialize($CONFIG_FORUM['auth']);
		
	$forum_config .= '$CONFIG_FORUM = ' . var_export($CONFIG_FORUM, true) . ';' . "\n";
	
	//Liste des catégories du forum
	$i = 0;
	$forum_cats = 'global $CAT_FORUM;' . "\n";
	$result = $Sql->Query_while("SELECT id, id_left, id_right, level, name, status, aprob, auth, aprob
	FROM ".PREFIX."forum_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{	
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
	$Sql->Close($result);		
	
	return $forum_config . "\n" . $forum_cats;
}

?>