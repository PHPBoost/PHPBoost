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

if (defined('PHPBOOST') !== true) exit;

function guestbook_mini($position, $block)
{
    global $LANG, $Cache, $_guestbook_rand_msg;
    
    //Mini guestbook non activée si sur la page archive guestbook.
    if (strpos(SCRIPT, '/guestbook/guestbook.php') === false)
    {
    	load_module_lang('guestbook');
    	$Cache->load('guestbook'); //Chargement du cache
    	
    	###########################Affichage##############################
    	$tpl = new Template('guestbook/guestbook_mini.tpl');
        import('core/menu_service');
        MenuService::assign_positions_conditions($tpl, $block);

		$rand = array_rand($_guestbook_rand_msg);
    	$guestbook_rand = isset($_guestbook_rand_msg[$rand]) ? $_guestbook_rand_msg[$rand] : array();
		
		if ($guestbook_rand === array())
		{
			$tpl->assign_vars(array(
	    		'C_ANY_MESSAGE_GESTBOOK' => false,
				'L_RANDOM_GESTBOOK' => $LANG['title_guestbook'],
				'L_NO_MESSAGE_GESTBOOK' => $LANG['no_message_guestbook']
	    	));
		}
		else
		{
	    	//Pseudo.
	    	if ($guestbook_rand['user_id'] != -1)
	    		$guestbook_login = '<a class="small_link" href="' . TPL_PATH_TO_ROOT . '/member/member' . url('.php?id=' . $guestbook_rand['user_id'], '-' . $guestbook_rand['user_id'] . '.php') . '" title="' . $guestbook_rand['login'] . '"><span style="font-weight:bold;">' . wordwrap_html($guestbook_rand['login'], 13) . '</span></a>';
	    	else
	    		$guestbook_login = '<span style="font-style:italic;">' . (!empty($guestbook_rand['login']) ? wordwrap_html($guestbook_rand['login'], 13) : $LANG['guest']) . '</span>';
	    	
	    	$tpl->assign_vars(array(
				'C_ANY_MESSAGE_GESTBOOK' => true,
				'L_RANDOM_GESTBOOK' => $LANG['title_guestbook'],
	    		'RAND_MSG_ID' => $guestbook_rand['id'],
	    		'RAND_MSG_CONTENTS' => (strlen($guestbook_rand['contents']) > 149) ? ucfirst($guestbook_rand['contents']) . ' <a href="' . TPL_PATH_TO_ROOT . '/guestbook/guestbook.php" class="small_link">' . $LANG['guestbook_more_contents'] . '</a>' : ucfirst($guestbook_rand['contents']),
	    		'RAND_MSG_LOGIN' => $guestbook_login,
	    		'L_BY' => $LANG['by']
	    	));
		}
		return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
    }
	return '';
}

?>