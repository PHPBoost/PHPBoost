<?php
/*##################################################
 *                               poll_mini.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre R�gis
 *   email                : crowkait@phpboost.com
 *
  *
###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

if (defined('PHPBOOST') !== true) exit;

function poll_mini($position, $block)
{
    global $Cache, $LANG, $CONFIG_POLL, $_array_poll;
    $Cache->load('poll'); //Mini sondages en cache => $_array_poll.
    if (!empty($CONFIG_POLL['poll_mini']) && $CONFIG_POLL['poll_mini'] != array() && strpos(SCRIPT, '/poll/poll.php') === false)
    {
    	//Chargement de la langue du module.
    	load_module_lang('poll');
    	$poll_mini = $_array_poll[array_rand($_array_poll)]; //Sondage aléatoire.
    	
    	$tpl = new Template('poll/poll_mini.tpl');
        
        MenuService::assign_positions_conditions($tpl, $block);
    		
    	#####################R�sultats######################
    	//Si le cookie existe, on redirige vers les resulats, sinon on prend en compte le vote (vérification par ip plus tard).
    	$array_cookie = isset($_COOKIE[$CONFIG_POLL['poll_cookie']]) ? explode('/', $_COOKIE[$CONFIG_POLL['poll_cookie']]) : array();
    	if (in_array($poll_mini['id'], $array_cookie))
    	{
    		$tpl->assign_vars(array(
    			'THEME' => get_utheme(),
    			'MODULE_DATA_PATH' => $tpl->get_module_data_path('poll'),
    			'L_MINI_POLL' => $LANG['mini_poll'],
    			'L_VOTE' => ($poll_mini['total'] > 1) ? $LANG['poll_vote_s'] : $LANG['poll_vote']
    		));
    		
    		$tpl->assign_block_vars('result', array(
    			'QUESTION' => $poll_mini['question'],
    			'VOTES' => $poll_mini['total'],
    		));
    		
    		foreach ($poll_mini['votes'] as $answer => $width)
    		{
    			$tpl->assign_block_vars('result.answers', array(
    				'ANSWERS' => $answer,
    				'WIDTH' => number_round($width, 0),
    				'PERCENT' => $width
    			));
    		}
    	}
    	else
    	{
    		#####################Questions######################
    		$tpl->assign_vars(array(
    			'L_MINI_POLL' => $LANG['mini_poll'],
    			'L_VOTE' => $LANG['poll_vote'],
    			'L_POLL_RESULT' => $LANG['poll_result'],
    			'U_POLL_RESULT' => url('.php?id=' . $poll_mini['id'] . '&amp;r=1', '-' . $poll_mini['id'] . '-1.php')
    		));
    		
    		global $Session;
    		$tpl->assign_block_vars('question', array(
    			'ID' => url('.php?id=' . $poll_mini['id'] . '&amp;token=' . $Session->get_token(), '-' . $poll_mini['id'] . '.php?token=' . $Session->get_token()),
    			'QUESTION' => $poll_mini['question']
    		));
    			
    		$z = 0;
    		if ($poll_mini['type'] == '1')
    		{
    			foreach ($poll_mini['votes'] as $answer => $width)
    			{
    				$tpl->assign_block_vars('question.radio', array(
    					'NAME' => $z,
    					'ANSWERS' => $answer
    				));
    				$z++;
    			}
    		}
    		elseif ($poll_mini['type'] == '0')
    		{
    			foreach ($poll_mini['votes'] as $answer => $width)
    			{
    				$tpl->assign_block_vars('question.checkbox', array(
    					'NAME' => $z,
    					'ANSWERS' => $answer
    				));
    				$z++;
    			}
    		}
    	}
        return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
    }
    return '';
}
?>