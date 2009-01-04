<?php
/*##################################################
 *                              quotes_mini.php
 *                            -------------------
 *   begin                : October 14, 2008
 *   copyright         : (C) 2008 Alain GANDON based on Guestbook_mini.php
 *   email              
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

if( defined('PHPBOOST') !== true ) exit;

//Mini quotes non activée si sur la page archive quotes.
if( strpos(SCRIPT, '/quotes/quotes.php') === false )
{
	load_module_lang('quotes');
	$Cache->load('quotes'); //Chargement du cache
	
	###########################Affichage##############################
	if ( !empty($CONFIG_QUOTES['tpl_vertical'])) {
		$vertical = TRUE;
	} else {
		$vertical = FALSE;
	}
	$Template->set_filenames(array(
		'quotes_mini'=> 'quotes/quotes_mini.tpl'
	));

	$quotes_rand = $_quotes_rand_msg[array_rand($_quotes_rand_msg)];

	//Pseudo.
	if( $quotes_rand['user_id'] != -1 ) 
		$quotes_login = '<a class="small_link" href="../member/member' . url('.php?id=' . $quotes_rand['user_id'], '-' . $quotes_rand['user_id'] . '.php') . '" title="' . $quotes_rand['user_id'] . '"><span style="font-weight:bold;">' . wordwrap_html($quotes_rand['user_id'], 13) . '</span></a>';
	else
		$quotes_login = '<span style="font-style:italic;">' . (!empty($quotes_rand['user_id']) ? wordwrap_html($quotes_rand['user_id'], 13) : $LANG['guest']) . '</span>';
		
	$quotes_contents = $quotes_rand['contents'];
	$quotes_contents = nl2br($quotes_contents);
	
	$quotes_author = $quotes_rand['author'];
	
	$Template->assign_vars(array(
		'C_HORIZONTAL' => !$vertical,
		'C_VERTICAL' => $vertical,
		'L_RANDOM_QUOTES' => $LANG['title_quotes'],
		'L_ALL_QUOTES' => $LANG['title_all_quotes'],
		'RAND_MSG_ID' => $quotes_rand['id'],
		'RAND_MSG_CONTENTS' => $quotes_contents,
		'RAND_MSG_AUTHOR' => $quotes_author,
		'RAND_MSG_LOGIN' => $quotes_login,
		'L_BY' => $LANG['by']
	));
}

?>