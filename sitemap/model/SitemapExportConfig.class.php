<?php
/**
 * Configuration used to export a Sitemap. It contains some Template objects
 * which are used to export each kind of elements of a sitemap.
 * Using different configurations will enable you for example to export in HTML code to be
 * displayed in a page of the web site (the site map) or to be written in the sitemap.xml
 * file at the root of your site, this file will be read by the search engines to optimize
 * the research of your site.
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 06 16
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
