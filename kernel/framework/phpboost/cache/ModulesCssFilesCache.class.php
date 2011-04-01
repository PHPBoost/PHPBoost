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




/**
 * This class contains the cache data of the css files for the installed modules.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class ModulesCssFilesCache implements CacheData
{
	private $themes_files = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		//We browse all the enabled modules
		foreach (ThemesCache::load()->get_installed_themes() as $theme => $properties)
		{
			$files_for_this_theme = array();
			foreach (ModulesManager::get_activated_modules_map() as $name => $module)
			{
				//If the module is enabled, we add it to the list
				if ($module->is_activated())
				{
					if (file_exists(PATH_TO_ROOT . '/templates/' . $theme . '/modules/' . $name . '/' . $name . '_mini.css'))
					{
						$files_for_this_theme[] = '/templates/' . $theme . '/modules/' . $name . '/' . $name . '_mini.css';
					}
					elseif (file_exists(PATH_TO_ROOT . '/templates/' . $theme . '/modules/' . $name . '/' . $name . '.css'))
					{
						$files_for_this_theme[] = '/templates/' . $theme . '/modules/' . $name . '/' . $name . '.css';
					}
					elseif (file_exists(PATH_TO_ROOT . '/' . $name . '/templates/' . $name . '_mini.css'))
					{
						$files_for_this_theme[] = '/' . $name . '/templates/' . $name . '_mini.css';
					}
					elseif (file_exists(PATH_TO_ROOT . '/' . $name . '/templates/' . $name . '.css'))
					{
						$files_for_this_theme[] = '/' . $name . '/templates/' . $name . '.css';
					}
				}
			}
			
			$folder = new Folder(PATH_TO_ROOT . '/templates/'. $theme .'/theme/');
			$relative_path = Path::get_path_from_root($folder->get_path());
			foreach ($folder->get_files('`.css$`') as $file)
			{
				if (strpos($file->get_name(), 'css-cache') === false && strpos($file->get_name(), 'tinymce.css') === false && strpos($file->get_name(), 'print.css') === false)
				{
					$files_for_this_theme[] = $relative_path . '/' . $file->get_name();
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