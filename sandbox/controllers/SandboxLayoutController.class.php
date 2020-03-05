<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 04
 * @since       PHPBoost 5.3 - 2020 03 04
*/

class SandboxLayoutController extends ModuleController
{
	private $view;
	private $common_lang;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('fwkboost', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxLayoutController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'SANDBOX_SUBMENU' => self::get_submenu(),
			'MESSAGE' => self::build_message_view()
		));
	}

	private function get_submenu()
	{
		$this->lang = LangLoader::get('submenu', 'sandbox');
		$submenu_tpl = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$submenu_tpl->add_lang($this->lang);
		return $submenu_tpl;
	}

	private function build_message_view()
	{
		$this->lang = LangLoader::get('fwkboost', 'sandbox');
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$message_tpl = new FileTemplate('sandbox/pagecontent/layout/message.tpl');
		$message_tpl->add_lang($this->lang);
		$message_tpl->add_lang($this->common_lang);

		return $message_tpl;
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
		$graphical_environment->set_page_title($this->common_lang['title.fwkboost'], $this->common_lang['title.layout'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.fwkboost']);
		$breadcrumb->add($this->common_lang['title.layout']);

		return $response;
	}
}
?>
