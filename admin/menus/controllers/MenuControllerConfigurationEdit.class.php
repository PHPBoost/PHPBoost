<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 27
*/

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
		$this->view = new FileTemplate('admin/menus/configuration/edit.tpl');
		$this->response = new AdminMenusDisplayResponse($this->view);
		$env = $this->response->get_graphical_environment();

		$env->set_page_title(LangLoader::get_message('menu.edit.menu', 'menu-lang'));
	}
}
?>
