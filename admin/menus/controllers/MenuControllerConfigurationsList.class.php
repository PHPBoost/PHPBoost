<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 27
 * @since       PHPBoost 3.0 - 2009 10 25
 * @contributor ph-7 <me@ph7.me>
*/

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
