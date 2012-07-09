<?php
/*##################################################
 *              AbstractDisplayGraphicalEnvironment.class.php
 *                            -------------------
 *   begin                : October 06, 2009
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
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
 abstract class AbstractDisplayGraphicalEnvironment extends AbstractGraphicalEnvironment
{
	private $page_title = '';

	public function __construct()
	{
		parent::__construct();
	}
	
	protected function get_modules_css_files_html_code()
	{
		return $this->get_css_files_always_displayed_html_code() . "\n" . $this->get_css_files_running_module_displayed();
	}
	
	private function get_css_files_always_displayed_html_code()
	{
		$theme_id = get_utheme();
		$css_cache_config = CSSCacheConfig::load();
		if ($css_cache_config->is_enabled())
		{
			$css_cache = new CSSCacheManager();
			$css_cache->set_files(ModulesCssFilesService::get_css_files_always_displayed($theme_id));
			$css_cache->set_cache_file_location(PATH_TO_ROOT . '/cache/css/css-cache-modules-' . $theme_id .'.css');
			$css_cache->execute($css_cache_config->get_optimization_level());
			$html_code = '<link rel="stylesheet" href="' . $css_cache->get_cache_file_location() . '" type="text/css" media="screen, print, handheld" />';
		}
		else
		{
			$html_code = '';
			foreach (ModulesCssFilesService::get_css_files_always_displayed($theme_id) as $file)
			{
				$html_code .= '<link rel="stylesheet" href="' . Url::to_rel($file) .	'" type="text/css" media="screen, print, handheld" />';
			}
		}
		return $html_code;
	}
	
	private function get_css_files_running_module_displayed()
	{
		$html_code = '';
		foreach (ModulesCssFilesService::get_css_files_running_module_displayed(get_utheme()) as $css_file)
		{
			$html_code .= '<link rel="stylesheet" href="' . Url::to_rel($css_file) . 
				'" type="text/css" media="screen, print, handheld" />';
		}
		return $html_code;
	}
	
	public function get_page_title()
	{
		return $this->page_title;
	}
	
	public function set_page_title($title) 
	{
		$this->page_title = $title;
		defined('TITLE') or define('TITLE', $title);
	}
}
?>