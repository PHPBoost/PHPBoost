<?php
/*##################################################
 *                          SiteMapController.class.php
 *                            -------------------
 *   begin                : December 09 2009
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

class SiteMapController implements Controller
{
	public function execute(HTTPRequest $request)
	{
		$sitemap = new SiteMap();
		$sitemap->build();

		$sub_section_tpl = new Template('sitemap/sitemap_section.html.tpl');
		$sub_section_tpl->assign_vars(array(
		'L_LEVEL' => 'de niveau'
		));

		$config_html = new SiteMapExportConfig('sitemap/sitemap.html.tpl',
			'sitemap/module_map.html.tpl', $sub_section_tpl, 'sitemap/sitemap_link.html.tpl');

		return new SiteDisplayResponse($sitemap->export($config_html));
	}
}
?>