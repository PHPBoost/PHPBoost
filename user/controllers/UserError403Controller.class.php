<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 05
 * @since       PHPBoost 3.0 - 2012 10 19
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserError403Controller extends UserErrorController
{
	public function __construct()
	{
		$error = LangLoader::get_message('warning.error', 'warning-lang');
		$unexist_page = LangLoader::get_message('warning.page.forbidden', 'warning-lang');
		$message = '<strong>403.</strong> ' . $unexist_page;
		parent::__construct($error. ' 403', $message, self::WARNING);
	}

	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_response()->set_status_code(403);
		return parent::execute($request);
	}

	protected function create_view()
	{
		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_right_columns(true);
		$columns_disabled->set_disable_left_columns(true);
		$columns_disabled->set_disable_top_central(true);
		$columns_disabled->set_disable_bottom_central(true);
		$this->view = new FileTemplate('user/UserError403Controller.tpl');
		$this->view->add_lang(LangLoader::get_all_langs());
	}
}
?>
