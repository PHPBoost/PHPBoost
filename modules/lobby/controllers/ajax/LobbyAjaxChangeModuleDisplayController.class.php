<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyAjaxChangeModuleDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request): Response
	{
		$id      = $request->get_int('id', 0);
		$display = -1;

		if ($id !== 0)
		{
			$config  = LobbyConfig::load();
			$modules = $config->get_modules();

			if (isset($modules[$id]))
			{
				$display            = $modules[$id]['displayed'] ? 0 : 1;
				$modules[$id]['displayed'] = $display;
				$config->set_modules($modules);
				LobbyConfig::save();
			}
		}

		return new JSONResponse(['id' => $id, 'display' => $display]);
	}
}
?>
