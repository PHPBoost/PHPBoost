<?php
/*##################################################
 *                          PHPBoostError.class.php
 *                            -------------------
 *   begin                : December 9, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class PHPBoostErrors
{
	public static function module_not_installed()
	{
        $lang = LangLoader::get('errors');
		$controller = new UserErrorController(
		$lang['e_uninstalled_module'],
		$lang['e_uninstalled_module'],
		UserErrorController::NOTICE);
		return $controller;
	}

	public static function module_not_activated()
	{
		// TODO
        $controller = new UserErrorController('TODO', 'Module not activated');
        return $controller;
	}
	
	public static function user_not_authorized()
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('e_auth', 'errors'));
		
        return $controller;
	}
	
	public static function user_in_read_only()
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('e_readonly', 'errors'));
		
        return $controller;
	}
	
    public static function unexisting_page()
    {
       $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('e_unexist_page', 'errors'));
        
        return $controller;
    }

	public static function member_banned()
	{
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('e_member_ban_w', 'errors'));
        
        return $controller;
	}

	public static function unexisting_member()
	{
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('e_unexist_member', 'errors'));
        
        return $controller;
	}
	
    public static function unexisting_category()
    {
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('e_unexist_cat', 'errors'));
        
        return $controller;
    }
    
    public static function unknow()
    {
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('unknow_error', 'errors'), UserErrorController::QUESTION);
        
        return $controller;
    }
    
	public static function member_not_enabled()
	{
        $lang = LangLoader::get('errors');
        $controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            LangLoader::get_message('e_unactiv_member', 'errors'));
        
        return $controller;
	}

	public static function flood()
	{
        $lang = LangLoader::get('errors');
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'),
		  LangLoader::get_message('e_flood', 'errors'));
		
        return $controller;
	}
	
	public static function link_flood()
	{
        $lang = LangLoader::get('errors');
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'),
		LangLoader::get_message('e_l_flood', 'errors'));

		return $controller;
	}
	
	public static function link_login_flood()
	{
        $lang = LangLoader::get('errors');
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'),
		LangLoader::get_message('e_link_pseudo', 'errors'));

		return $controller;
	}
}