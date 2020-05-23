<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 22
 * @since       PHPBoost 5.3 - 2020 03 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
		$this->lang = LangLoader::get('layout', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxLayoutController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'MESSAGE'         => self::build_markup('sandbox/pagecontent/layout/message.tpl'),
			'CELL_LAYOUT' => self::build_markup('sandbox/pagecontent/layout/cell_layout.tpl'),
			'SORTABLE' => self::build_markup('sandbox/pagecontent/layout/sortable.tpl'),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function build_markup($tpl)
	{
		$view = new FileTemplate($tpl);
		$view->add_lang($this->lang);
		$view->add_lang($this->common_lang);

		$date = new Date();
		$view->put_all(array(
			'TODAY' => $date->format(Date::FORMAT_DAY_MONTH_YEAR),
			'TODAY_TIME' => $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
			'NO_AVATAR_URL' => Url::to_rel(FormFieldThumbnail::get_default_thumbnail_url(UserAccountsConfig::NO_AVATAR_URL))
		));

		return $view;
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
		$graphical_environment->set_page_title($this->common_lang['title.layout'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.layout']);

		return $response;
	}
}
?>
