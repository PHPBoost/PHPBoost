<?php
/*##################################################
 *                               guestbook_mini.php
 *                            -------------------
 *   begin                : May 30, 2008
 *   copyright          : (C) 2008 Viarre Régis
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

if( defined('PHPBOOST') !== true ) exit;

//Mini guestbook non activée si sur la page archive guestbook.
if( strpos(SCRIPT, '/guestbook/guestbook.php') === false )
{
	load_module_lang('guestbook');
	$Cache->load('guestbook'); //Chargement du cache
	
	###########################Affichage##############################
	$Template->set_filenames(array(
		'guestbook_mini'=> 'guestbook/guestbook_mini.tpl'
	));

	$guestbook_rand = $_guestbook_rand_msg[array_rand($_guestbook_rand_msg)];

	//Pseudo.
	if( $guestbook_rand['user_id'] != -1 ) 
		$guestbook_login = '<a class="small_link" href="../member/member' . url('.php?id=' . $guestbook_rand['user_id'], '-' . $guestbook_rand['user_id'] . '.php') . '" title="' . $guestbook_rand['login'] . '"><span style="font-weight:bold;">' . wordwrap_html($guestbook_rand['login'], 13) . '</span></a>';
	else
		$guestbook_login = '<span style="font-style:italic;">' . (!empty($guestbook_rand['login']) ? wordwrap_html($guestbook_rand['login'], 13) : $LANG['guest']) . '</span>';
	
	$guestbook_contents = ucfirst(wordwrap_html($guestbook_rand['contents'], 22));
	$Template->assign_vars(array(
		'L_RANDOM_GESTBOOK' => $LANG['title_guestbook'],
		'RAND_MSG_ID' => $guestbook_rand['id'],
		'RAND_MSG_CONTENTS' => (strlen($guestbook_contents) > 22) ? $guestbook_contents . ' <a href="' . PATH_TO_ROOT . '/guestbook/guestbook.php" class="small_link">' . $LANG['guestbook_more_contents'] . '</a>' : $guestbook_contents,
		'RAND_MSG_LOGIN' => $guestbook_login,
		'L_BY' => $LANG['by']
	));
}

?>