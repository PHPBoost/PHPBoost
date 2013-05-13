<?php
/*##################################################
 *                      ArticlesHomeController.class.php
 *                            -------------------
 *   begin                : May 13, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesHomeController extends ModuleController
{
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		
		return $this->build_response(ArticlesModuleHomePage::get_view());
	}
	
	private function build_response(View $view)
	{	
		$response = new ArticlesDisplayResponse();
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());
		$response->set_page_title($this->lang['articles']);
		
		return $response->display($view);
	}
}
?>

