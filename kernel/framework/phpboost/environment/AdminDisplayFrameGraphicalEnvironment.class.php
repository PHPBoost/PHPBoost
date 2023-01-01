<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 19
 * @since       PHPBoost 4.0 - 2014 01 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminDisplayFrameGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	public function __construct()
	{
		parent::__construct();
	}

	public function display($content)
	{
		$lang = LangLoader::get_all_langs();
		$view = new FileTemplate('admin/frame.tpl');
		$view->add_lang($lang);

		$customization_config = CustomizationConfig::load();
		$cookiebar_config = CookieBarConfig::load();
		$maintenance_config = MaintenanceConfig::load();

		$js_top_tpl = new FileTemplate('js_top.tpl');
		$js_top_tpl->add_lang($lang);
		$js_top_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => false
		));

		$js_bottom_tpl = new FileTemplate('js_bottom.tpl');
		$js_bottom_tpl->add_lang($lang);
		$js_bottom_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => false
		));

		$view->put_all(array(
			'C_FAVICON' => $customization_config->favicon_exists(),

			'FAVICON'      => Url::to_rel($customization_config->get_favicon_path()),
			'FAVICON_TYPE' => $customization_config->favicon_type(),
			'TITLE'        => $this->get_seo_meta_data()->get_full_title(),
			'MODULES_CSS'  => $this->get_modules_css_files_html_code(),
			'JS_TOP'       => $js_top_tpl,
			'JS_BOTTOM'    => $js_bottom_tpl,
			'BODY'         => new StringTemplate($content)
		));

		$view->display();
	}
}
?>
