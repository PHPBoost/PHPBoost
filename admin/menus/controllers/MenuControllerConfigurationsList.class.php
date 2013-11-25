<?php
/*##################################################
 *                           MenuControllerConfigurationsList.class.php
 *                            -------------------
 *   begin                : October 25 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class MenuControllerConfigurationsList extends AdminController
{
	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var SiteDisplayResponse
	 */
	private $response;

	public function execute(HTTPRequestCustom $request)
	{
		global $LANG;

		$this->load_env();

		$menu_configurations = MenuConfigurationDAO::instance()->find_by_criteria(
		'WHERE id!=:default_config_id ORDER BY priority DESC;', array('default_config_id' => 1));

		foreach ($menu_configurations as $menu_config)
		{
			$this->view->assign_block_vars('menu_configuration', array(
                'NAME' => $menu_config->get_name(),
                'MATCH_REGEX' => $menu_config->get_match_regex(),
                'U_EDIT' => MenuUrlBuilder::menu_configuration_edit($menu_config->get_id())->rel(),
				'U_CONFIGURE' => MenuUrlBuilder::menu_configuration_configure($menu_config->get_id())->rel()
			));
		}

		$default_menu_config = MenuConfigurationDAO::instance()->find_by_id(1);
		$this->view->put_all(array(
			'U_DEFAULT_MENU_CONFIG_CONFIGURE' => MenuUrlBuilder::menu_configuration_configure($default_menu_config->get_id())->rel()
		));

		return $this->response;
	}

	private function load_env()
	{
		global $LANG;

		$this->view = new FileTemplate('admin/menus/configuration/list.tpl');
		$this->response = new AdminMenusDisplayResponse($this->view);
		$env = $this->response->get_graphical_environment();

		$env->set_page_title($LANG['menu_configurations']);
	}
}
?>