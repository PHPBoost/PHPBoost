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
	/**
	 * @var SEOMetaData
	 */
	private $seo_meta_data = null;

	public function __construct()
	{
		parent::__construct();
		$general_config = GeneralConfig::load();
		$this->seo_meta_data = new SEOMetaData(
			$general_config->get_site_description(),
			$general_config->get_site_keywords()
		);
	}
	
	protected function get_modules_css_files_html_code()
	{
		$css_cache_config = CSSCacheConfig::load();
		$css_files = array_merge(ModulesCssFilesService::get_css_files_always_displayed(), ModulesCssFilesService::get_css_files_running_module_displayed());
		if ($css_cache_config->is_enabled())
		{
			$html_code = '<link rel="stylesheet" href="' . CSSCacheManager::get_css_path($css_files) . '" type="text/css" media="screen, print, handheld" />';
		}
		else
		{
			$html_code = '';
			foreach ($css_files as $file)
			{
				$html_code .= '<link rel="stylesheet" href="' . Url::to_rel($file) .	'" type="text/css" media="screen, print, handheld" />';
			}
		}
		return $html_code;
	}
	
	public function get_seo_meta_data()
	{
		return $this->seo_meta_data;
	}
	
	public function set_seo_meta_data($seo_meta_data)
	{
		$this->seo_meta_data = $seo_meta_data;
	}
	
	public function get_page_title()
	{
		return $this->get_seo_meta_data()->get_title();
	}
	
	public function set_page_title($title)
	{
		$this->get_seo_meta_data()->set_title($title);
			
		defined('TITLE') or define('TITLE', $title);
		
		self::set_page_localization($this->get_page_title());
	}
}
?>