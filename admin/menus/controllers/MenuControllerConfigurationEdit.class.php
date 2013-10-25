<?php
/*##################################################
 *                           MenuControllerConfigurationEdit.class.php
 *                            -------------------
 *   begin                : October 27 2009
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

class MenuControllerConfigurationEdit extends AdminController
{
	/**
	 * @var int
	 */
	private $object_id;

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var AdminMenusDisplayResponse
	 */
	private $response;

	public function execute(HTTPRequestCustom $request)
	{
		try
		{
			$this->try_execute();
		}
		catch (ObjectNotFoundException $exception)
		{
			$error_controller = new UserErrorController(
			    'Menu configuration does not exists',
			    'The requested menu configuration ' . $request->get_value('menu_config_id') . ' does not exists',
			UserErrorController::FATAL
			);

			$error_controller->set_correction_link('Menu configuration list', MenuUrlBuilder::menu_configuration_list()->rel());
			$error_controller->set_response_classname('AdminMenusDisplayResponse');

			return $error_controller->execute($request);
		}
		catch (UnexistingHTTPParameterException $exception)
		{
			$error_controller = ClassLoader::new_instance('/admin/menus/controllers/MenuControllerError');
			return $error_controller->execute($request);
		}

		return $this->response;
	}

	private function try_execute()
	{
		$this->load_env();

		$this->object_id = AppContext::get_request()->get_getint('menu_config_id');
		$menu_config = MenuConfigurationDAO::instance()->find_by_id($this->object_id);

			
		$this->view->put_all(array(
			'NAME' => $menu_config->get_name(),
			'MATCH_REGEX' => $menu_config->get_match_regex(),
			'U_CONFIGURE' => MenuUrlBuilder::menu_configuration_configure($menu_config->get_id())->rel(),
			'U_LIST' => MenuUrlBuilder::menu_configuration_list()->rel()
		));
	}

	private function load_env()
	{
		global $LANG;

		$this->view = new FileTemplate('admin/menus/configuration/edit.tpl');
		$this->response = new AdminMenusDisplayResponse($this->view);
		$env = $this->response->get_graphical_environment();

		$env->set_page_title($LANG['menu_configuration_edition']);
	}
}
?>