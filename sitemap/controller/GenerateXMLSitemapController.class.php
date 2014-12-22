<?php
/*##################################################
 *                     GenerateXMLSitemapController.class.php
 *                            -------------------
 *   begin                : December 08 2009
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

class GenerateXMLSitemapController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$view = new FileTemplate('sitemap/GenerateXMLSitemapController.tpl');
		$lang = LangLoader::get('common', 'sitemap');
		$view->add_lang($lang);
		
		try
		{
			SitemapXMLFileService::try_to_generate();
		}
		catch(IOException $ex)
		{
			$view->put_all(
				array('C_GOT_ERROR' => true)
			);
		}
		
		$view->put_all(array(
			'U_GENERATE' => SitemapUrlBuilder::get_xml_file_generation()->rel()
		));

		$response = new AdminSitemapResponse($view);
		$response->get_graphical_environment()->set_page_title($lang['generate_xml_file'], $lang['sitemap']);
		return $response;
	}
}
?>