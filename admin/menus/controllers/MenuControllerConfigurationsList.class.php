<?php
/*##################################################
 *                           MenuControllerConfigurationsList.class.php
 *                            -------------------
 *   begin                : October 25 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
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

import('/admin/menus/models/MenuConfiguration');


class MenuControllerConfigurationsList implements Controller
{
	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var SiteDisplayResponse
	 */
	private $response;

	public function execute(HTTPRequest $request)
	{
		$this->load_env();

		$menu_configurations = MenuConfigurationDAO::instance()->find_all(DAO::FIND_ALL, 0, array(
		array('column' => 'priority', 'way' => DAO::ORDER_BY_ASC)));
//			
//		$this->view->assign_vars(array(
//            'U_CREATE' => Blog::global_action_url(Blog::GLOBAL_ACTION_CREATE)->absolute(),
//            'U_LIST' => Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute()
//		));
//
//		foreach ($blogs as $blog)
//		{
//			$this->view->assign_block_vars('blogs', array(
//                'TITLE' => $blog->get_title(),
//                'DESCRIPTION' => second_parse($blog->get_description()),
//                'U_DETAILS' => $blog->action_url(Blog::ACTION_DETAILS)->absolute(),
//                'U_EDIT' => $blog->action_url(Blog::ACTION_EDIT)->absolute(),
//                'U_DELETE' => $blog->action_url(Blog::ACTION_DELETE)->absolute(),
//                'USER' => $blog->get_login()
//			));
//		}

		return $this->response;
	}

	private function load_env()
	{
		import('/admin/menus/util/AdminMenusDisplayResponse');

		global $LANG;

		$this->view = new View('admin/menus/configuration/list.tpl');
		$this->view->add_lang($LANG);
		$this->response = new AdminMenusDisplayResponse($this->view);
		$env = $this->response->get_graphical_environment();

		$env->set_page_title($LANG['menu_configurations']);
	}
}
?>