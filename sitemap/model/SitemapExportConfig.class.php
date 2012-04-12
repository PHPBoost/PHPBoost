<?php
/*##################################################
 *                        SitemapExportConfig.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc Configuration used to export a Sitemap. It contains some Template objects
 * which are used to export each kind of elements of a sitemap.
 * Using different configurations will enable you for example to export in HTML code to be
 * displayed in a page of the web site (the site map) or to be written in the sitemap.xml
 * file at the root of your site, this file will be read by the search engines to optimize
 * the research of your site.
 *
 */
class SitemapExportConfig
{
	/**
	 * @var Template object used to export the Sitemap objects
	 */
	private $site_map_file;

	/**
	 * @var Template object used to export the ModuleMap objects
	 */
	private $module_map_file;

	/**
	 * @var Template object used to export the SitemapSection objects
	 */
	private $section_file;
	/**
	 * @var Template object used to export the SitemapLink objects
	 */
	private $link_file;

	/**
	 * @desc Builds a SitemapExportConfig object
	 * @param mixed $module_map_file The template used to export a ModuleMap object. Can be a Template object or a string (path of the template to use).
	 * @param mixed $section_file The template used to export a SitemapSection object. Can be a Template object or a string (path of the template to use).
	 * @param mixed $link_file The template used to export a SitemapLink object. Can be a Template object or a string (path of the template to use).
	 */
	public function __construct($site_map_file, $module_map_file, $section_file, $link_file)
	{
		//If we receive a string it's the path of the template, otherwise it's already the Template object
		$this->site_map_file = is_string($site_map_file) ? new FileTemplate($site_map_file) : $site_map_file;
		$this->module_map_file = is_string($module_map_file) ? new FileTemplate($module_map_file) : $module_map_file;
		$this->section_file = is_string($section_file) ? new FileTemplate($section_file) : $section_file;
		$this->link_file = is_string($link_file) ? new FileTemplate($link_file) : $link_file;
	}

	/**
	 * @desc Returns the Template object to use while exporting a Sitemap object.
	 * @return Template
	 */
	public function get_site_map_stream()
	{
		return clone $this->site_map_file;
	}

	/**
	 * @desc Returns the Template object to use while exporting a ModuleMap object.
	 * @return Template
	 */
	public function get_module_map_stream()
	{
		return clone $this->module_map_file;
	}

	/**
	 * @desc Returns the Template object to use while exporting a SitemapSection object.
	 * @return Template
	 */
	public function get_section_stream()
	{
		return clone $this->section_file;
	}

	/**
	 * @desc Returns the Template object to use while exporting a SitemapLink object.
	 * @return Template
	 */
	public function get_link_stream()
	{
		return clone $this->link_file;
	}

	/**
	 * @desc Sets the Template object to use while exporting a Site object.
	 * @param mixed $module_map_file The template used to export a Site object. Can be a Template object or a string (path of the template to use).
	 */
	public function set_site_map_stream($site_map_file)
	{
		$this->site_map_file = is_string($site_map_file) ? new FileTemplate($site_map_file) : $site_map_file;
	}

	/**
	 * @desc Sets the Template object to use while exporting a ModuleMap object.
	 * @param mixed $module_map_file The template used to export a ModuleMap object. Can be a Template object or a string (path of the template to use).
	 */
	public function set_module_map_stream($module_map_file)
	{
		$this->module_map_file = is_string($module_map_file) ? new FileTemplate($module_map_file) : $module_map_file;
	}

	/**
	 * @desc Sets the Template object to use while exporting a SitemapSection object.
	 * @param mixed $section_file The template used to export a SitemapSection object. Can be a Template object or a string (path of the template to use).
	 */
	public function set_section_stream($section_file)
	{
		$this->section_file = is_string($section_file) ? new FileTemplate($section_file) : $section_file;
	}

	/**
	 * @desc Sets the Template object to use while exporting a SitemapLink object.
	 * @param mixed $link_file The template used to export a SitemapLink object. Can be a Template object or a string (path of the template to use).
	 */
	public function set_link_stream($link_file)
	{
		$this->link_file = is_string($link_file) ? new FileTemplate($link_file) : $link_file;
	}
}
?>