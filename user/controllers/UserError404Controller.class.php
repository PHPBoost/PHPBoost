<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 05
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserError404Controller extends UserErrorController
{
	public function __construct()
	{
		$error = LangLoader::get_message('warning.error', 'warning-lang');
		$unexist_page = LangLoader::get_message('warning.page.unexists', 'warning-lang');
		$message = '<strong>404.</strong> ' . $unexist_page;
		parent::__construct($error. ' 404', $message, self::WARNING);
	}

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->no_session_location();
		AppContext::get_response()->set_status_code(404);
		AdminError404Service::register_404();
		return parent::execute($request);
	}

	protected function create_view()
	{
		$this->view = new FileTemplate('user/UserError404Controller.tpl');
		$this->view->add_lang(LangLoader::get_all_langs());
	}
}
?>
