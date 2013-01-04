<?php
/*##################################################
 *                        ThemesSwitcherModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : reidlos@phpboost.com
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

class ThemesSwitcherModuleMiniMenu extends ModuleMiniMenu
{    
    public function get_default_block()
    {
    	return self::BLOCK_POSITION__RIGHT;
    }
    
	public function admin_display()
    {
        return '';
    }

	public function display($tpl = false)
    {
    	$themeswitcher_lang = LangLoader::get('themeswitcher_common', 'ThemesSwitcher');
		$user = AppContext::get_current_user();
		
    	$theme_id = AppContext::get_request()->get_string('switchtheme', '');
        if (!empty($theme_id))
        {
	        $theme = ThemeManager::get_theme($theme_id);
			if ($theme !== null)
			{
				if ($theme->is_activated() && $theme->check_auth())
				{
					$user->update_theme($theme->get_id());
				}
			}
			$query_string = preg_replace('`switchtheme=[^&]+`', '', QUERY_STRING);
			AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
        }
	
	    $tpl = new FileTemplate('ThemesSwitcher/themeswitcher.tpl');
	
	    MenuService::assign_positions_conditions($tpl, $this->get_block());
	
	    foreach (ThemeManager::get_activated_and_authorized_themes_map() as $id => $theme)
		{
			$selected = ($user->get_theme() == $id) ? ' selected="selected"' : '';
    		$tpl->assign_block_vars('themes', array(
    			'NAME' => $theme->get_configuration()->get_name(),
    			'IDNAME' => $id,
    			'SELECTED' => $selected
    		));
	    }
	
	    $tpl->put_all(array(
	    	'DEFAULT_THEME' => UserAccountsConfig::load()->get_default_theme(),
	    	'L_SWITCH_THEME' => $themeswitcher_lang['switch_theme'],
	    	'L_DEFAULT_THEME' => $themeswitcher_lang['defaut_theme'],
	    	'L_SUBMIT' => LangLoader::get_message('submit', 'main')
	    ));
	
	    return $tpl->render();
    }
}
?>