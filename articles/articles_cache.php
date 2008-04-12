<?php
/*##################################################
 *                                articles_cache.php
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
function generate_module_file_articles()
{
	global $Sql;
	
	$config_articles = 'global $CAT_ARTICLES;' . "\n" . 'global $CONFIG_ARTICLES;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_ARTICLES = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'articles'", __LINE__, __FILE__));
	$CONFIG_ARTICLES = is_array($CONFIG_ARTICLES) ? $CONFIG_ARTICLES : array();
	foreach($CONFIG_ARTICLES as $key => $value)
	{
		if( $key == 'auth_root' )
			$config_articles .= '$CONFIG_ARTICLES[\'' . $key . '\'] = ' . var_export(unserialize($value), true) . ';' . "\n";
		else
			$config_articles .= '$CONFIG_ARTICLES[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	}
	$config_articles .= "\n";
	
	$cat_articles = 'global $CAT_ARTICLES;' . "\n";
	$result = $Sql->Query_while("SELECT id, id_left, id_right, level, name, aprob, auth
	FROM ".PREFIX."articles_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{		
		if( empty($row['auth']) )
			$row['auth'] = serialize(array());
			
		$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
		$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
		$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
		$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
		$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
		$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
	}
	$Sql->Close($result);
	
	return $config_articles . "\n" . $cat_articles;
}

?>