<?php
/*##################################################
 *                               poll_cache.php
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

//Mini-polls
function generate_module_file_poll()
{
	global $Sql;
	
	$code = 'global $CONFIG_POLL;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_POLL = unserialize($Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'poll'", __LINE__, __FILE__));
	$CONFIG_POLL = is_array($CONFIG_POLL) ? $CONFIG_POLL : array();
	foreach($CONFIG_POLL as $key => $value)
		$code .= '$CONFIG_POLL[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";

	$_array_poll = '';
	if( is_array($CONFIG_POLL['poll_mini']) )
	{
		foreach($CONFIG_POLL['poll_mini'] as $key => $idpoll)
		{
			$poll = $Sql->Query_array('poll', 'id', 'question', 'votes', 'answers', 'type', "WHERE id = '" . $idpoll . "' AND archive = 0 AND visible = 1", __LINE__, __FILE__);
			if( !empty($poll['id']) ) //Sondage existant.
			{	
				$array_answer = explode('|', $poll['answers']);
				$array_vote = explode('|', $poll['votes']);
				
				$total_vote = array_sum($array_vote);
				$total_vote = ($total_vote == 0) ? 1 : $total_vote; //Empêche la division par 0.
				
				$array_votes = array_combine($array_answer, $array_vote);
				foreach($array_votes as $answer => $nbrvote)
					$array_votes[$answer] = number_round(($nbrvote * 100 / $total_vote), 1);
					
				$_array_poll .= $key . ' => array(\'id\' => ' . var_export($poll['id'], true) . ', \'question\' => ' . var_export($poll['question'], true) . ', \'votes\' => ' . var_export($array_votes, true) . ', \'total\' => ' . var_export($total_vote, true) . ', \'type\' => ' . var_export($poll['type'], true) . '),' . "\n";
			}
		}
	}
	if( !empty($_array_poll) )
		$code .= "\n" . 'global $_array_poll;' . "\n\n" . '$_array_poll = array(' . $_array_poll . ');';

	return $code;
}

?>