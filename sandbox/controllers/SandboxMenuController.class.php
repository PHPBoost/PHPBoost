<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 09
 * @since   	PHPBoost 3.0 - 2012 05 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxMenuController extends ModuleController
{
	private $view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_left_columns(true);
		$columns_disabled->set_disable_right_columns(true);
		$columns_disabled->set_disable_top_central(true);
		$columns_disabled->set_disable_bottom_central(true);
		$columns_disabled->set_disable_top_footer(true);

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxMenuController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'SITE_NAME' => GeneralConfig::load()->get_site_name(),
			'SITE_SLOGAN' => GeneralConfig::load()->get_site_slogan(),
		));
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
		$response = new SiteDisplayFrameResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['title.menu'], $this->lang['module.title']);

		return $response;
	}
}
?>
