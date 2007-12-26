<?php
/*##################################################
 *                               poll_mini.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

$cache->load_file('poll'); //Mini poll en cache => $_question_poll, $_total_vote, $_array_poll.
if( $CONFIG_POLL['poll_mini'] > 0 && SCRIPT !== DIR . '/poll/poll.php' )
{
	//Chargement de la langue du module.
	@include_once('../poll/lang/' . $CONFIG['lang'] . '/poll_' . $CONFIG['lang'] . '.php');
	#####################Résultats######################
	//Si le cookie existe, on redirige vers les resulats, sinon on prend en compte le vote (vérification par ip plus tard).
	$array_cookie = isset($_COOKIE[$CONFIG_POLL['poll_cookie']]) ? explode('/', $_COOKIE[$CONFIG_POLL['poll_cookie']]) : array();
	if( in_array($CONFIG_POLL['poll_mini'], $array_cookie) )
	{
		$template->set_filenames(array(
			'poll_mini' => '../templates/' . $CONFIG['theme'] . '/poll/poll_mini.tpl'
		));
				
		$template->assign_vars(array(
			'THEME' => $CONFIG['theme'],
			'MODULE_DATA_PATH' => $template->module_data_path('poll'),
			'L_MINI_POLL' => $LANG['mini_poll'],
			'L_VOTE' => ($_total_vote > 1) ? $LANG['poll_vote_s'] : $LANG['poll_vote']
		));
		
		$template->assign_block_vars('result', array(
			'QUESTION' => $_question_poll,
			'VOTES' => $_total_vote,
		));
		
		foreach($_array_poll as $answer => $width)
		{
			$template->assign_block_vars('result.answers', array(
				'ANSWERS' => $answer,
				'WIDTH' => arrondi($width, 0), 
				'PERCENT' => $width
			));			
		}
		
		$template->pparse('poll_mini'); 
	}
	else
	{
		#####################Questions######################
		$template->set_filenames(array(
			'poll_mini' => '../templates/' . $CONFIG['theme'] . '/poll/poll_mini.tpl'
		));
				
		$template->assign_vars(array(			
			'L_MINI_POLL' => $LANG['mini_poll'],
			'L_VOTE' => $LANG['poll_vote'],
			'L_POLL_RESULT' => $LANG['poll_result'],
			'U_POLL_RESULT' => transid('.php?id=' . $CONFIG_POLL['poll_mini'] . '&amp;r=1', '-' . $CONFIG_POLL['poll_mini'] . '-1.php')
		));	
		
		$template->assign_block_vars('question', array(
			'ID' => transid('.php?id=' . $CONFIG_POLL['poll_mini'], '-' . $CONFIG_POLL['poll_mini'] . '.php'),
			'QUESTION' => $_question_poll			
		));		
			
		$z = 0;
		if( $_poll_type == '1' )
		{			
			foreach($_array_poll as $answer => $width)
			{						
				$template->assign_block_vars('question.radio', array(
					'NAME' => $z,
					'TYPE' => 'radio',
					'ANSWERS' => $answer
				));
				$z++;
			}
		}	
		elseif( $_poll_type == '0' ) 
		{
			foreach($_array_poll as $answer => $width)
			{						
				$template->assign_block_vars('question.checkbox', array(
					'NAME' => $z,
					'TYPE' => 'checkbox',
					'ANSWERS' => $answer
				));
				$z++;	
			}
		}
		
		$template->pparse('poll_mini');
	}
}

?>