<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 20
 * @since       PHPBoost 3.0 - 2010 10 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class InstallFinish extends InstallController
{
	public function execute(HTTPRequestCustom $request): Response
	{
		parent::load_lang($request);
		$view = new FileTemplate('install/InstallFinish.tpl');
		return $this->create_response($view);
	}

	private function create_response(View $view): InstallCarrier
	{
		$step_title = $this->lang['install.finish.title'];
		$response = new InstallCarrier(6, $step_title, $this->lang, $view);
		return $response;
	}
}
?>
