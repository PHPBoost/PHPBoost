<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 30
 * @since       PHPBoost 5.0 - 2016 09 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class UserAboutCookieController extends AbstractController
{
	private $lang;
	private $template;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		return $this->build_response($this->template);
	}

	private function init()
	{
		$this->template = new FileTemplate('user/UserAboutCookieController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->template->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$config = CookieBarConfig::load();

		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($config->get_cookiebar_aboutcookie_title());
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['seo.user.about-cookie']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::aboutcookie());

		$this->template->put_all(array(
			'ABOUTCOOKIE_TITLE' => $config->get_cookiebar_aboutcookie_title(),
			'ABOUTCOOKIE_CONTENT' => FormatingHelper::second_parse($config->get_cookiebar_aboutcookie_content())
		));

		return $response;
	}
}
?>
