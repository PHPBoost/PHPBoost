<?php
/*##################################################
 *                           SitemapModuleHomePage.class.php
 *                            -------------------
 *   begin                : February 08, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class SitemapModuleHomePage implements ModuleHomePage
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$this->init();
		
		$config_html = new SitemapExportConfig('sitemap/export/sitemap.html.tpl',
			'sitemap/export/module_map.html.tpl', 'sitemap/export/sitemap_section.html.tpl', 'sitemap/export/sitemap_link.html.tpl');

		$sitemap = SitemapService::get_personal_sitemap();
		
		$tpl = new FileTemplate('sitemap/ViewSitemapController.tpl');
		$tpl->add_lang($this->lang);
		$tpl->put('SITEMAP', $sitemap->export($config_html));
		
		return $tpl;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'sitemap');
	}
}
?>