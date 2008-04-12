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
	global $Sql;
	
	$code = 'global $DOWNLOAD_CATS;' . "\n" . 'global $CONFIG_DOWNLOAD;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_DOWNLOAD = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'download'", __LINE__, __FILE__));
	$CONFIG_DOWNLOAD['global_auth'] = unserialize(stripslashes($CONFIG_DOWNLOAD['global_auth']));
	
	$code .= '$CONFIG_DOWNLOAD = ' . var_export($CONFIG_DOWNLOAD, true) . ';';
	
	$code .= "\n";
	
	//Liste des catégories et de leurs propriétés
	$code .= '$DOWNLOAD_CATS = array();' . "\n\n";
	$result = $Sql->Query_while("SELECT id, id_parent, c_order, auth, name, visible, icon, num_files, contents
	FROM ".PREFIX."download_cat
	ORDER BY id_parent, c_order", __LINE__, __FILE__);
	
	while ($row = $Sql->Sql_fetch_assoc($result))
	{
		$code .= '$DOWNLOAD_CATS[' . $row['id'] . '] = ' . 
			var_export(array(
				'id_parent' => $row['id_parent'],
				'order' => $row['c_order'],
				'name' => $row['name'],
				'contents' => $row['contents'],
				'visible' => (bool)$row['visible'],
				'icon' => $row['icon'],
				'description' => $row['contents'],
				'num_files' => $row['num_files'],
				'auth' => unserialize($row['auth'])
				),
			true)
			. ';' . "\n";
	}
	
	return $code;
}

?>