<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 09
 * @since       PHPBoost 3.0 - 2010 02 10
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxHomeController extends ModuleController
{
	private $view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->view->put_all(array(
			'WELCOME_MESSAGE' => FormatingHelper::second_parse($this->lang['welcome.message']),
			'SANDBOX_SUB_MENU' => self::get_sub_tpl()
		));

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxHomeController.tpl');
		$this->view->add_lang($this->lang);
	}

	private static function get_sub_tpl()
	{
		$sub_lang = LangLoader::get('submenu', 'sandbox');
		$sub_tpl = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$sub_tpl->add_lang($sub_lang);
		return $sub_tpl;
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sandbox.module.title']);

		return $response;
	}
}
?>
