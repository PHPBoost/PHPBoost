<?php
/*##################################################
 *                             UrlHelper.class.php
 *                            -------------------
 *   begin                : Januar 23, 2010
 *   copyright            : (C) 2010 Régis Viarre
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

class UrlHelper 
{
	/**
	 * @desc Adds the session ID to an URL if the user doesn't accepts cookies.
	 * This functions allows you to generate an URL according to the site configuration concerning the URL rewriting.
	 * @param string $url URL if the URL rewriting is disabled
	 * @param string $mod_rewrite URL if the URL rewriting is enabled
	 * @param string $ampersand In a redirection you mustn't put the & HTML entity (&amp;). In this case set that parameter to &.
	 * @return string The URL to use.
	 */
	public static function url($url, $mod_rewrite = '', $ampersand = '&amp;')
	{
		global $CONFIG, $Session;
	
		if (!is_object($Session))
		{
			$session_mod = 0;
		}
		else
		{
			$session_mod = $Session->supports_cookies();
		}
	
		if ($session_mod == 0)
		{
			if ($CONFIG['rewrite'] == 1 && !empty($mod_rewrite)) //Activation du mod rewrite => cookies activés.
			{
				return $mod_rewrite;
			}
			else
			{
				return $url;
			}
		}
		elseif ($session_mod == 1)
		{
			return $url . ((strpos($url, '?') === false) ? '?' : $ampersand) . 'sid=' . $Session->data['session_id'] . $ampersand . 'suid=' . $Session->data['user_id'];
		}
	}
	
}





?>