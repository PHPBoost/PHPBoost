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
	public static function CSRF()
	{
		$controller = new UserErrorController(
		LangLoader::get_message('error', 'errors'),
		LangLoader::get_message('csrf_attack', 'main'),
		UserErrorController::NOTICE);
		return $controller;
	}
	
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
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_unactivated_module']);
		return $controller;
	}
	
	public static function user_not_authorized()
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_auth']);
		return $controller;
	}
	
	public static function user_in_read_only()
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_readonly']);
		return $controller;
	}
	
    public static function unexisting_page()
    {
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_unexist_page']);
		return $controller;
    }

	public static function member_banned($delay = 0)
	{
		$lang = LangLoader::get('main');
		$errors_lang = LangLoader::get('errors');
		if ($delay > 0)
		{
			if ($delay < 60)
				$message = $delay . ' ' . (($delay > 1) ? $lang['minutes'] : $lang['minute']);
			elseif ($delay < 1440)
			{
				$delay_ban = NumberHelper::round($delay/60, 0);
				$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['hours'] : $lang['hour']);
			}
			elseif ($delay < 10080)
			{
				$delay_ban = NumberHelper::round($delay/1440, 0);
				$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['days'] : $lang['day']);
			}
			elseif ($delay < 43200)
			{
				$delay_ban = NumberHelper::round($delay/10080, 0);
				$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['weeks'] : $lang['week']);
			}
			elseif ($delay < 525600)
			{
				$delay_ban = NumberHelper::round($delay/43200, 0);
				$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['months'] : $lang['month']);
			}
			else
			{
				$delay_ban = NumberHelper::round($delay/525600, 0);
				$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['years'] : $lang['year']);
			}
			$message = $errors_lang['e_member_ban'] . ' ' . $message;
		}
		else
		{
			$message = $errors_lang['e_member_ban_w'];
		}
		$controller = new UserErrorController($errors_lang['error'], $message);
		return $controller;
	}

	public static function unexisting_member()
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_unexist_member']);
		return $controller;
	}
	
    public static function unexisting_category()
    {
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_unexist_cat']);
		return $controller;
    }
    
    public static function unknow()
    {
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['unknow_error'], UserErrorController::QUESTION);
		return $controller;
    }
    
	public static function member_not_enabled()
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_unactiv_member']);
		return $controller;
	}
	
	public static function member_flood($number_test_connection = 0)
	{
		$lang = LangLoader::get('errors');
		$message = $number_test_connection > 0 ? sprintf($lang['e_test_connect'], $number_test_connection) : $lang['e_nomore_test_connect'];
		$controller = new UserErrorController($lang['error'], $message);
		return $controller;
	}

	public static function flood()
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_flood']);
		return $controller;
	}
	
	public static function link_flood($max_link)
	{
		$lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], sprintf($lang['e_l_flood'], $max_link));
		return $controller;
	}
	
	public static function link_login_flood()
	{
        $lang = LangLoader::get('errors');
		$controller = new UserErrorController($lang['error'], $lang['e_link_pseudo']);
		return $controller;
	}
}
?>