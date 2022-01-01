<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 02 21
*/

class BugtrackerAjaxCheckStatusChangedController extends AbstractController
{
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$id = $request->get_value('id', 0);
		$status = $request->get_value('status', '');
		$old_status = $request->get_value('old_status', '');

		$this->view->put('RESULT', (int)(!empty($id) && $old_status == $status));
	}

	private function init()
	{
		$this->view = new StringTemplate('{RESULT}');
	}
}
?>
