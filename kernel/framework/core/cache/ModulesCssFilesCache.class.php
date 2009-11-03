<?php
/*##################################################
 *                   modules_css_files_cache_data.class.php
 *                            -------------------
 *   begin                : October 17, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/




/**
 * This class contains the cache data of the css files for the installed modules.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class ModulesCssFilesCache implements CacheData
{
	private $themes_files = array();
	
	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/cache/CacheData#synchronize()
	 */
	public function synchronize()
	{
		global $MODULES, $THEME_CONFIG, $CONFIG;

		$THEME_CONFIG = is_array($THEME_CONFIG) ? $THEME_CONFIG : array();
		$MODULES = is_array($MODULES) ? $MODULES : array();

		//We brows all the enabled modules
		foreach ($THEME_CONFIG as $theme => $infos)
		{
			$files_for_this_theme = array();
			foreach ($MODULES as $name => $array)
			{
				//If the module is enabled, we add it to the list
				if ($array['activ'] == '1')
				{
					if (file_exists(PATH_TO_ROOT . '/templates/' . $theme . '/modules/' . $name . '/' . $name . '_mini.css'))
					{
						$files_for_this_theme[] = '/templates/' . $theme . '/modules/' . $name . '/' . $name . '_mini.css';
					}
					elseif (file_exists(PATH_TO_ROOT . '/' . $name . '/templates/' . $name . '_mini.css'))
					{
						$files_for_this_theme[] = '/' . $name . '/templates/' . $name . '_mini.css';
					}
				}
			}
			$this->themes_files[$theme] = $files_for_this_theme;
		}
	}
	
	/**
	 * Returns the list of the files to load for a given theme.
	 * @param $theme The theme for which you want the files to load
	 * @return string[] List of the paths of the files to load.
	 * @throws PropertyNonFoundException If the configuration for this theme is unknown
	 */
	public function get_files_for_theme($theme)
	{
		if (isset($this->themes_files[$theme]))
		{
			return $this->themes_files[$theme];
		}
		else
		{
			throw new PropertyNotFoundException($theme);
		}
	}

	/**
	 * Loads and returns the modules css files cached data.
	 * @return ModulesCssFilesCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'modules-css-files');
	}

	/**
	 * Invalidates the current modules css files cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'modules-css-files');
	}
}