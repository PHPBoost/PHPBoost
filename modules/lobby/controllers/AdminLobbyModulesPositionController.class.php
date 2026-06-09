<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLobbyModulesPositionController extends DefaultAdminModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('lobby/AdminLobbyModulesPositionController.tpl');
	}

	public function execute(HTTPRequestCustom $request): Response
	{
		$this->lang   = LangLoader::get_all_langs('lobby');
		$this->config = LobbyConfig::load();

		$this->update_modules($request);

		$built_in     = [LobbyConfig::MODULE_ANCHORS_MENU, LobbyConfig::MODULE_CAROUSEL, LobbyConfig::MODULE_EDITO, LobbyConfig::MODULE_LASTCOMS];
		$modules_count = 0;

		foreach ($this->config->get_modules() as $id => $properties)
		{
			$module = new LobbyModule();
			$module->set_properties($properties);

			$is_builtin = in_array($module->get_module_id(), $built_in);
			$is_active  = $is_builtin ? true : $module->is_active();

			$this->view->assign_block_vars('modules_list', [
				'C_ACTIVE'  => $is_active,
				'C_DISPLAY' => $module->is_displayed(),
				'ID'        => $id,
				'NAME'      => $is_active ? $module->get_module_name() : '',
				'U_EDIT'    => LobbyUrlBuilder::configuration($module->get_phpboost_module_id() ?: $module->get_module_id())->rel(),
			]);
			$modules_count++;
		}

		$this->view->put_all([
			'C_MODULES'         => $modules_count > 0,
			'C_SEVERAL_MODULES' => $modules_count > 1,
		]);

		return new AdminLobbyDisplayResponse($this->view, $this->lang['lobby.modules.position']);
	}

	private function update_modules(HTTPRequestCustom $request): void
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.position.update'], MessageHelper::SUCCESS, 5));
		}
	}

	private function update_position(HTTPRequestCustom $request): void
	{
		$modules        = $this->config->get_modules();
		$sorted_modules = [];

		$modules_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));

		foreach ($modules_list as $position => $tree)
		{
			$sorted_modules[$position + 1] = $modules[$tree->id];
			unset($modules[$tree->id]);
		}

		foreach ($modules as $position => $module)
		{
			$sorted_modules[] = $module;
		}

		$this->config->set_modules($sorted_modules);
		LobbyConfig::save();
	}
}
?>
