<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 04
*/

class InstallFinishController extends InstallController
{
	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$view = new FileTemplate('install/finish.tpl');
		return $this->create_response($view);
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response(View $view)
	{
		$step_title = $this->lang['step.finish.title'];
		$response = new InstallDisplayResponse(6, $step_title, $view);
		return $response;
	}
}
?>
