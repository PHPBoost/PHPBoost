<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 3.0 - 2009 10 27
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MenuControllerConfigurationEdit extends AdminController
{
	private int $object_id;
	private Template $view;
	private AdminMenusDisplayResponse $response;

	public function execute(HTTPRequestCustom $request): Response
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
			$error_controller = PHPBoostErrors::unexisting_page();
			$error_controller->set_response_classname('AdminMenusDisplayResponse');
			return $error_controller->execute($request);
		}

		return $this->response;
	}

	private function try_execute(): void
	{
		$this->load_env();

		$this->object_id = AppContext::get_request()->get_getint('menu_config_id');
		/** @var MenuConfiguration $menu_config */
		$menu_config = MenuConfigurationDAO::instance()->find_by_id($this->object_id);

		$this->view->put_all([
			'NAME' => $menu_config->get_name(),
			'MATCH_REGEX' => $menu_config->get_match_regex(),
			'U_CONFIGURE' => MenuUrlBuilder::menu_configuration_configure($menu_config->get_id())->rel(),
			'U_LIST' => MenuUrlBuilder::menu_configuration_list()->rel()
		]);
	}

	private function load_env(): void
	{
		$this->view = new FileTemplate('admin/content/menus/configuration/edit.tpl');
		$this->response = new AdminMenusDisplayResponse($this->view);
		$env = $this->response->get_graphical_environment();

		$env->set_page_title(LangLoader::get_message('menu.edit.menu', 'menu-lang'));
	}
}
?>
