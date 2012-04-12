<?php
/*##################################################
 *                        LangsSwitcherModuleMiniMenu.class.php
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

class LangsSwitcherModuleMiniMenu extends ModuleMiniMenu
{    
    public function get_default_block()
    {
    	return self::BLOCK_POSITION__RIGHT;
    }

	public function display($tpl = false)
    {
	    $langswitcher_lang = LangLoader::get('langswitcher_common', 'LangsSwitcher');
		$user = AppContext::get_current_user();
	
		$switchlang = !empty($_GET['switchlang']) ? urldecode($_GET['switchlang']) : '';
	    if (!empty($switchlang))
	    {
	        if ($user->check_level(User::MEMBER_LEVEL))
	        {
	            $Session->csrf_get_protect();
	        }
	
	    	if (preg_match('`[ a-z0-9_-]{3,20}`i', $switchlang) && strpos($switchlang, '\'') === false)
	    	{
	    		$user->update_ang($switchlang); //Mise à jour du thème du membre.
	    		if (QUERY_STRING != '')
	    		{
					$query_string = preg_replace('`token=[^&]+`', '', QUERY_STRING);
					$query_string = preg_replace('`&switchlang=[^&]+`', '', $query_string);
					AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
	    		}
	    		else
	    			AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	    	}
	    }
	
	    $tpl = new FileTemplate('LangsSwitcher/langswitcher.tpl');
	
	    MenuService::assign_positions_conditions($tpl, $this->get_block());
	
	    $array_js_identifier = '';
	
	    foreach(LangManager::get_activated_langs_map() as $id => $lang)
	    {
	    	if ($lang->check_auth())
	    	{
				$selected = ($user->get_locale() == $id) ? ' selected="selected"' : '';
	    		$tpl->assign_block_vars('langs', array(
	    			'NAME' => $lang->get_configuration()->get_name(),
	    			'IDNAME' => $id,
	    			'SELECTED' => $selected
	    		));
	    	}
	    }
	
	    $lang_identifier = str_replace('en', 'uk', LangLoader::get_message('xml_lang', 'main'));
	    $tpl->put_all(array(
	    	'DEFAULT_LANG' => UserAccountsConfig::load()->get_default_lang(),
	    	'IMG_LANG_IDENTIFIER' => TPL_PATH_TO_ROOT . '/images/stats/countries/' . $lang_identifier . '.png',
	    	'L_SWITCH_LANG' => $langswitcher_lang['switch_lang'],
	    	'L_DEFAULT_LANG' => $langswitcher_lang['default_lang'],
	    	'L_SUBMIT' => LangLoader::get_message('submit', 'main')
	    ));
	
	    return $tpl->render();
    }
}
?>