<?php
/*##################################################
 *                          SitemapService.class.php
 *                            -------------------
 *   begin                : December 12, 2009
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

class SitemapService
{
	/**
	 * @return Sitemap
	 */
	public static function get_public_sitemap()
	{
		$sitemap = new Sitemap();
		$sitemap->build();
		return $sitemap;
	}

	/**
	 * @return Sitemap
	 */
	public static function get_personal_sitemap()
	{
		$sitemap = new Sitemap();
		$sitemap->build(Sitemap::USER_MODE, Sitemap::AUTH_USER);
		return $sitemap;
	}

	public static function write_sitemap_xml_file()
	{
		$sitemap = self::get_public_sitemap();
		$export_config = self::get_xml_file_export_config();

		$file = new File(PATH_TO_ROOT . '/sitemap.xml');

		try
		{
			$file->write($sitemap->export($export_config)->parse(Template::TEMPLATE_PARSER_STRING));
		}
		catch(IOException $ex)
		{
			$lang = LangLoader::get('main', 'sitemap');
			ErrorHandler::add_error_in_log($lang['sitemap_xml_could_not_been_written']);
		}
	}

	/**
	 * @return SitemapExportConfig
	 */
	public static function get_xml_file_export_config()
	{
		$export_config = new SitemapExportConfig('framework/content/sitemap/sitemap.xml.tpl',
		 'framework/content/sitemap/module_map.xml.tpl', 'framework/content/sitemap/sitemap_section.xml.tpl',
		 'framework/content/sitemap/sitemap_link.xml.tpl');
		return $export_config;
	}
}
?>