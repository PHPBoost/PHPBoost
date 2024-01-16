<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 01 16
 * @since       PHPBoost 4.0 - 2014 06 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SiteDisplayFrameGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	private $display_css_login = false;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * {@inheritdoc}
	 */
	public function display($content)
	{
		$lang = LangLoader::get_all_langs();
		$view = new FileTemplate('frame.tpl');
		$view->add_lang($lang);

		$customization_config = CustomizationConfig::load();
		$cookiebar_config = CookieBarConfig::load();
		$maintenance_config = MaintenanceConfig::load();

		$js_top_tpl = new FileTemplate('js_top.tpl');
		$js_top_tpl->add_lang($lang);
		$js_top_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED'     => $cookiebar_config->is_cookiebar_enabled() && !$maintenance_config->is_under_maintenance(),
			'COOKIEBAR_DURATION'      => $cookiebar_config->get_cookiebar_duration(),
			'COOKIEBAR_TRACKING_MODE' => $cookiebar_config->get_cookiebar_tracking_mode(),
			'COOKIEBAR_CONTENT'       => TextHelper::to_js_string($cookiebar_config->get_cookiebar_content())
		));

		$js_bottom_tpl = new FileTemplate('js_bottom.tpl');
		$js_bottom_tpl->add_lang($lang);
		$js_bottom_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => $cookiebar_config->is_cookiebar_enabled() && !$maintenance_config->is_under_maintenance()
		));

		$js_add_tpl = new FileTemplate('js_add.tpl');
		$js_add_tpl->add_lang($lang);

		$description = $this->get_seo_meta_data()->get_full_description();
		$view->put_all(array(
			'C_CSS_LOGIN_DISPLAYED' => $this->display_css_login,
			'C_FAVICON'             => $customization_config->favicon_exists(),
			'C_CANONICAL_URL'       => $this->get_seo_meta_data()->canonical_link_exists(),
			'C_DESCRIPTION'         => !empty($description),

			'FAVICON_TYPE'     => $customization_config->favicon_type(),
			'TITLE'            => $this->get_seo_meta_data()->get_full_title(),
			'SITE_DESCRIPTION' => $description,
			'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version(),
			'MODULES_CSS'      => $this->get_modules_css_files_html_code(),
			'JS_TOP'           => $js_top_tpl,
			'JS_BOTTOM'        => $js_bottom_tpl,
			'JS_ADDITIONAL'    => $js_add_tpl,
			'BODY'             => new StringTemplate($content),

			'U_CANONICAL' => $this->get_seo_meta_data()->get_canonical_link(),
			'U_FAVICON'   => Url::to_rel($customization_config->get_favicon_path()),
		));

		$view->display(true);
	}

	public function display_css_login()
	{
		$this->display_css_login = true;
	}
}
?>
