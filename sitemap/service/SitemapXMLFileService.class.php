<?php
/*##################################################
 *                        SitemapXMLFileService.class.php
 *                            -------------------
 *   begin                : December 22, 2009
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
 * @desc This service handles all the operations that can be done on the sitemap.xml file.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SitemapXMLFileService
{
	/**
	 * @desc Generates the sitemap.xml file if needed.
	 */
	public static function generate_if_needed()
	{
		if (self::is_xml_file_generation_enabled() && self::is_out_of_date())
		{
			self::generate();
		}
	}

	/**
	 * @return SitemapExportConfig
	 */
	public static function get_export_config()
	{
		$export_config = new SitemapExportConfig('sitemap/export/sitemap.xml.tpl',
		 'sitemap/export/module_map.xml.tpl', 'sitemap/export/sitemap_section.xml.tpl',
		 'sitemap/export/sitemap_link.xml.tpl');
		return $export_config;
	}

	/**
	 * Tells whether the sitemap.xml file automatical generation is enabled.
	 * @return bool true if enabled, false otherwise
	 */
	public static function is_xml_file_generation_enabled()
	{
		return SitemapConfig::load()->is_sitemap_xml_generation_enabled();
	}

	private static function is_out_of_date()
	{
		$last_date = SitemapConfig::load()->get_last_generation_date();
		$reference_date = self::get_reference_date();
		return $last_date->is_anterior_to($reference_date);
	}

	private static function get_reference_date()
	{
		$reference_date = new Date();
		$life_time = self::get_life_time();
		$reference_date->set_day($reference_date->get_day() - $life_time);
		return $reference_date;
	}

	public static function get_life_time()
	{
		return SitemapConfig::load()->get_sitemap_xml_life_time();
	}

	/**
	 * @desc Generates if possible the file and if errors occur, writes them in the log file
	 * without throwing any exception.
	 */
	public static function generate()
	{
		try
		{
			self::try_to_generate();
		}
		catch(IOException $ex)
		{
			$lang = LangLoader::get('common', 'sitemap');
			ErrorHandler::add_error_in_log($lang['sitemap_xml_could_not_been_written'], __FILE__, __LINE__);
		}
	}

	/**
	 * @desc Tries to generate the sitemap.xml file and throws an exception if it can't be done.
	 * @throws IOException
	 */
	public static function try_to_generate()
	{
		$sitemap = SitemapService::get_public_sitemap();
		$export_config = self::get_export_config();

		$file = new File(PATH_TO_ROOT . '/sitemap.xml');

		$file->write($sitemap->export($export_config)->render());

		self::update_last_generation_date();
	}

	private static function update_last_generation_date()
	{
		$sitemap_config = SitemapConfig::load();
		$sitemap_config->set_last_generation_date(new Date());
		SitemapConfig::save($sitemap_config);
	}
}
?>