<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 18
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxMenusNavController extends ModuleController
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
		$this->view = new FileTemplate('sandbox/SandboxMenusNavController.tpl');
		$this->view->add_lang(array_merge($this->lang, $this->common_lang, LangLoader::get('menu-lang')));
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'MARKUP' => self::get_markup(),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function get_markup()
	{
		$markup_tpl = new FileTemplate('sandbox/pagecontent/menus/markup.tpl');
		$markup_tpl->add_lang(array_merge($this->lang, $this->common_lang));
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
		$graphical_environment->set_page_title($this->common_lang['sandbox.menus'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['sandbox.menus'], SandboxUrlBuilder::menus_nav()->rel());

		return $response;
	}
}
?>
