<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 31
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxMenuController extends ModuleController
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
		$this->lang = LangLoader::get('menu', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxMenuController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'L_ELEM' => $this->lang['cssmenu.element'],
			'SANDBOX_SUBMENU' => self::get_submenu(),
			'MARKUP' => self::get_markup()
		));
	}

	private function get_submenu()
	{
		$submenu_lang = LangLoader::get('submenu', 'sandbox');
		$submenu_tpl = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$submenu_tpl->add_lang($submenu_lang);
		return $submenu_tpl;
	}

	private function get_markup()
	{
		$markup_tpl = new FileTemplate('sandbox/pagecontent/menus/markup.tpl');
		$markup_tpl->add_lang($this->lang);
		$markup_tpl->add_lang($this->common_lang);
		return $markup_tpl;
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
		$graphical_environment->set_page_title($this->common_lang['title.menu'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.menu'], SandboxUrlBuilder::menus()->rel());

		return $response;
	}
}
?>
