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
	
	$guestbook_code = 'global $CONFIG_GUESTBOOK;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_GUESTBOOK = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'guestbook'", __LINE__, __FILE__));
	$CONFIG_GUESTBOOK = is_array($CONFIG_GUESTBOOK) ? $CONFIG_GUESTBOOK : array();
	foreach($CONFIG_GUESTBOOK as $key => $value)
		if( $key == 'guestbook_forbidden_tags' )
			$guestbook_code .= '$CONFIG_GUESTBOOK[\'guestbook_forbidden_tags\'] = ' . var_export(unserialize($value), 1) . ';' . "\n";
		else
			$guestbook_code .= '$CONFIG_GUESTBOOK[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	
	$guestbook_code .= "\n\n" . 'global $_guestbook_rand_msg;' . "\n";
	$guestbook_code .= "\n" . '$_guestbook_rand_msg = array();' . "\n";
	$result = $Sql->Query_while("SELECT g.id, g.login, g.user_id, g.timestamp, m.login as mlogin, g.contents
	FROM ".PREFIX."guestbook g
	LEFT JOIN ".PREFIX."member m ON m.user_id = g.user_id
	ORDER BY g.timestamp DESC 
	" . $Sql->Sql_limit(0, 10), __LINE__, __FILE__);	
	while ($row = $Sql->Sql_fetch_assoc($result))
	{
		$guestbook_code .= '$_guestbook_rand_msg[] = array(\'id\' => ' . var_export($row['id'], true) . ', \'contents\' => ' . var_export(substr_html(strip_tags($row['contents']), 0, 150), true) . ', \'user_id\' => ' . var_export($row['user_id'], true) . ', \'login\' => ' . var_export($row['login'], true) . ');' . "\n";
	}
	$Sql->Close($result);
	
	return $guestbook_code;
}

?>