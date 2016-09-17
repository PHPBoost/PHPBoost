<?php
/*##################################################
 *                       UserAboutCookie.class.php
 *                            -------------------
*   begin                : September 18, 2016
 *   copyright            : (C) 2016 Genet Arnaud
 *   email                : elenwii@phpboost.com
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

class UserAboutCookieController extends AbstractController
{
	private $lang;
	private $template;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();	
		return $this->build_response($this->template);
	}

	private function init()
	{
		$this->template = new FileTemplate('user/aboutcookie.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->template->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_env = $response->get_graphical_environment();
		$graphical_env->set_page_title($this->lang['cookiebar.about-cookie']);
		return $response;
	}
}
?>