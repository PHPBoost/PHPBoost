<?php
/*##################################################
 *                        ViewSitemapController.class.php
 *                            -------------------
 *   begin                : December 09 2009
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

class ViewSitemapController extends ModuleController
{
	private $lang = array();
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$tpl = $this->build_view();
		
		$response = new SiteDisplayResponse($tpl);
		$response->get_graphical_environment()->set_page_title($this->lang['sitemap']);
		return $response;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'sitemap');
	}
	
	private function build_view()
	{
		$config_html = new SitemapExportConfig('sitemap/export/sitemap.html.tpl',
			'sitemap/export/module_map.html.tpl', 'sitemap/export/sitemap_section.html.tpl', 'sitemap/export/sitemap_link.html.tpl');

		$sitemap = SitemapService::get_personal_sitemap();
		
		$tpl = new FileTemplate('sitemap/ViewSitemapController.tpl');
		$tpl->add_lang($this->lang);
		$tpl->put('SITEMAP', $sitemap->export($config_html));
		return $tpl;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		return $object->build_view();
	}
}
?>