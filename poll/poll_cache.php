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
	global $sql;
	
	global $sql;
	
	$code = 'global $CONFIG_POLL;' . "\n";
		
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_POLL = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'poll'", __LINE__, __FILE__));
	$CONFIG_POLL = is_array($CONFIG_POLL) ? $CONFIG_POLL : array();
	foreach($CONFIG_POLL as $key => $value)
		$code .= '$CONFIG_POLL[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	
	$_array_poll = '$_array_poll = array(';
	$_question_poll = '';
	$_poll_type = '0';
	$_total_vote = '0';
	
	$poll = $sql->query_array('poll', 'id', 'question', 'votes', 'answers', 'type', "WHERE id = '" . $CONFIG_POLL['poll_mini'] . "' AND archive = 0 AND visible = 1", __LINE__, __FILE__);
	if( !empty($poll['id']) ) //Sondage existant.
	{	
		$array_answer = explode('|', $poll['answers']);
		$array_vote = explode('|', $poll['votes']);
		
		$_total_vote = array_sum($array_vote);
		$_total_vote = ($_total_vote == 0) ? 1 : $_total_vote; //Empêche la division par 0.
		
		$_question_poll = str_replace('\'', '\\\'', $poll['question']);
		
		$array_poll = array_combine($array_answer, $array_vote);
		foreach($array_poll as $answer => $nbrvote)
		{
			$_array_poll .= var_export($answer, true) . ' => ' . var_export(arrondi(($nbrvote * 100 / $_total_vote), 1), true) . ', ' . "\n";
		}		
		$_poll_type = $poll['type'];
	}
	$_array_poll .= ');' . "\n";
	
	return $code .
	"\n" . 
	'global $_mini_poll, $_poll_type, $_question_poll, $_total_vote, $_array_poll;' . 
	"\n\n" . $_array_poll . 
	"\r\n" . '$_poll_type = ' . var_export($_poll_type, true) . ';' .
	"\r\n" . '$_total_vote = ' . var_export($_total_vote, true) . ';' .
	"\r\n" . '$_question_poll = ' . var_export($_question_poll, true) . ';';
}

?>