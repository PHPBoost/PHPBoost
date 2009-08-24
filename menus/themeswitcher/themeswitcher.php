<?php
/*##################################################
 *                               themeswitcher.php
 *                            -------------------
 *   begin                : November 16, 2008
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

function menu_themeswitcher_themeswitcher($position, $block)
{
	global $CONFIG, $THEME_CONFIG, $User, $LANG, $Session;

	load_menu_lang('themeswitcher');
	
	$switchtheme = !empty($_GET['switchtheme']) ? urldecode($_GET['switchtheme']) : '';
    if (!empty($switchtheme))
    {
        if ($User->check_level(MEMBER_LEVEL))
        {
            $Session->csrf_get_protect();
        }
        
    	if (preg_match('`[ a-z0-9_-]{3,20}`i', $switchtheme) && strpos($switchtheme, '\'') === false)
    	{
    		$User->update_user_theme($switchtheme); //Mise à jour du thème du membre.
    		if (QUERY_STRING != '')
    			redirect(trim(HOST . SCRIPT . '?' . preg_replace('`switchtheme=[^&]+`', '', QUERY_STRING), '?'));
    		else
    			redirect(HOST . SCRIPT);
    	}
    }
    
    $tpl = new Template('menus/themeswitcher/themeswitcher.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $block);
    
    $utheme = get_utheme();
    foreach($THEME_CONFIG as $theme => $array_info)
    {
    	if ($User->check_level($array_info['secure']) && $theme != 'default')
    	{
			$selected = ($utheme == $theme) ? ' selected="selected"' : '';
    		$info_theme = @parse_ini_file(PATH_TO_ROOT . '/templates/' . $theme . '/config/' . get_ulang() . '/config.ini');
    		$tpl->assign_block_vars('themes', array(
    			'NAME' => $info_theme['name'],
    			'IDNAME' => $theme,
    			'SELECTED' => $selected
    		));
    	}
    }
    
    $tpl->assign_vars(array(
    	'DEFAULT_THEME' => $CONFIG['theme'],
    	'L_SWITCH_THEME' => $LANG['switch_theme'],
    	'L_DEFAULT_THEME' => $LANG['defaut_theme'],
    	'L_SUBMIT' => $LANG['submit']
    ));
    
    return $tpl->parse(TEMPLATE_STRING_MODE);
}

?>