<?php
/*##################################################
 *                              guestbook_interface.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if (defined('PHPBOOST') !== true) exit;

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

// Classe ForumInterface qui hérite de la classe ModuleInterface
class GuestbookInterface extends ModuleInterface
{
    ## Public Methods ##
    function GuestbookInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('guestbook');
    }
    
	//Récupération du cache.
	function get_cache()
	{
		$guestbook_code = 'global $CONFIG_GUESTBOOK;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_GUESTBOOK = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'guestbook'", __LINE__, __FILE__));
		$CONFIG_GUESTBOOK = is_array($CONFIG_GUESTBOOK) ? $CONFIG_GUESTBOOK : array();
		
		if (isset($CONFIG_GUESTBOOK['guestbook_forbidden_tags']))
			$CONFIG_GUESTBOOK['guestbook_forbidden_tags'] = unserialize($CONFIG_GUESTBOOK['guestbook_forbidden_tags']);
			
		$guestbook_code .= '$CONFIG_GUESTBOOK = ' . var_export($CONFIG_GUESTBOOK, true) . ';' . "\n";
		
		$guestbook_code .= "\n\n" . 'global $_guestbook_rand_msg;' . "\n";
		$guestbook_code .= "\n" . '$_guestbook_rand_msg = array();' . "\n";
		$result = $this->sql_querier->query_while("SELECT g.id, g.login, g.user_id, g.timestamp, m.login as mlogin, g.contents
		FROM " . PREFIX . "guestbook g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		ORDER BY g.timestamp DESC
		" . $this->sql_querier->limit(0, 10), __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$guestbook_code .= '$_guestbook_rand_msg[] = array(\'id\' => ' . var_export($row['id'], true) . ', \'contents\' => ' . var_export(nl2br(substr_html(strip_tags(second_parse($row['contents'])), 0, 150)), true) . ', \'user_id\' => ' . var_export($row['user_id'], true) . ', \'login\' => ' . var_export($row['login'], true) . ');' . "\n";
		}
		$this->sql_querier->query_close($result);
		
		return $guestbook_code;
	}

	//Actions journalière.
	function on_changeday()
	{
		global $Cache;
		
		$Cache->Generate_module_file('guestbook');
	}
}

?>