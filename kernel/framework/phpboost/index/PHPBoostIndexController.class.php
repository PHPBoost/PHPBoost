<?php
/**
 * @package     PHPBoost
 * @subpackage  Index
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 28
 * @since       PHPBoost 3.0 - 2012 02 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PHPBoostIndexController extends AbstractController
{
	private $general_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->general_config = GeneralConfig::load();

		$other_home_page = $this->general_config->get_other_home_page();
		if (TextHelper::strpos($other_home_page, '/index.php') !== false)
		{
			AppContext::get_response()->redirect(UserUrlBuilder::home());
		}
		else if (!empty($other_home_page))
		{
			AppContext::get_response()->redirect($other_home_page);
		}
		else
		{
			try {
				$module = AppContext::get_extension_provider_service()->get_provider($this->general_config->get_module_home_page());
				if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
				{
					$home_page = $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page();
					return $this->build_response($home_page->get_view(), $home_page->get_title());
				}
				else
				{
					AppContext::get_response()->redirect(UserUrlBuilder::home());
				}
			} catch (UnexistingExtensionPointProviderException $e) {
				AppContext::get_response()->redirect(UserUrlBuilder::home());
			}
		}
	}

	private function build_response($view, $title)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($title);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(new Url(Url::to_rel('/')));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($title, $this->general_config->get_other_home_page() ? $this->general_config->get_other_home_page() : new Url('/' . $this->general_config->get_module_home_page() . '/'));

		return $response;
	}
}
?>
