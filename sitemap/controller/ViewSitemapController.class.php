<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 01
 * @since       PHPBoost 3.0 - 2009 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ViewSitemapController extends ModuleController
{
	private $lang = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$tpl = $this->build_view();

		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sitemap']);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['sitemap.seo.description'], array('site' => GeneralConfig::load()->get_site_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(SitemapUrlBuilder::view_sitemap());
		return $response;
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sitemap');
	}

	private function build_view()
	{
		$config_html = new SitemapExportConfig('sitemap/export/sitemap.html.tpl',
			'sitemap/export/module_map.html.tpl', 'sitemap/export/sitemap_section.html.tpl', 'sitemap/export/sitemap_link.html.tpl');

		$sitemap = SitemapService::get_personal_sitemap();

		$tpl = new FileTemplate('sitemap/ViewSitemapController.tpl');
		$tpl->add_lang($this->lang);
		$tpl->put('SITEMAP', $sitemap->export($config_html));
		return $tpl;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		return $object->build_view();
	}
}
?>
