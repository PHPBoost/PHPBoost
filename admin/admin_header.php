<?php
/*##################################################
 *                             admin_header.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

if (defined('PHPBOOST') !== true)
	exit;


$env = new AdminDisplayGraphicalEnvironment();

Environment::set_graphical_environment($env);

if (!defined('TITLE'))
{
	define('TITLE', $LANG['unknow']);
}


if (!defined('TITLE'))
	define('TITLE', $LANG['unknow']);
	
//DEPRECATED PROCESSUS
if (defined('ALTERNATIVE_CSS'))
{
	$alternative = null;
	$styles = @unserialize(ALTERNATIVE_CSS);
	if (is_array($styles))
	{
		foreach ($styles as $module => $style)
		{
			$base 	= '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
			$file = $base . $style . '.css';
			if (file_exists(PATH_TO_ROOT . $file))
			{
				$env->add_css_file($file);
			}
			else
			{
				$env->add_css_file('/' . $module . '/templates/' . $style .	'.css');
			}
		}
	}
	else
	{
		$array_alternative_css = explode(',', str_replace(' ', '', ALTERNATIVE_CSS));
		$module = $array_alternative_css[0];
		$base = '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
		foreach ($array_alternative_css as $alternative)
		{
			$file = $base . $alternative . '.css';
			if (file_exists(PATH_TO_ROOT . $file))
			{
				$env->add_css_file($file);
			}
			else
			{
				$env->add_css_file('/' . $module . '/templates/' . $alternative . '.css');
			}
		}
	}
}

$env->set_page_title(TITLE);

Environment::display_header();

?>