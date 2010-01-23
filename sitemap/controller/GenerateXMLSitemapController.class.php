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
	public function execute(HTTPRequest $request)
	{
		$view = new Template('sitemap/GenerateXMLSitemapController.tpl');
		$view->add_lang(LangLoader::get('common', 'sitemap'));
		
		try
		{
			SitemapXMLFileService::try_to_generate();
		}
		catch(IOException $ex)
		{
			$view->assign_vars(
				array('C_GOT_ERROR' => true)
			);
		}
		
		$view->assign_vars(array(
			'U_GENERATE' => SitemapUrlBuilder::get_xml_file_generation()->absolute()
		));

		return new AdminSitemapResponse($view);
	}
}