<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 5.0 - 2016 09 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserAboutCookieController extends AbstractController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->view = new FileTemplate('user/UserAboutCookieController.tpl');
		$this->lang = LangLoader::get_all_langs();
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$config = CookieBarConfig::load();

		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($config->get_cookiebar_aboutcookie_title());
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['user.seo.about.cookie']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::aboutcookie());

		$this->view->put_all(array(
			'ABOUTCOOKIE_TITLE'   => $config->get_cookiebar_aboutcookie_title(),
			'ABOUTCOOKIE_CONTENT' => FormatingHelper::second_parse($config->get_cookiebar_aboutcookie_content())
		));

		return $response;
	}
}
?>
