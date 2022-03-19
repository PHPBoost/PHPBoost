<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 19
 * @since       PHPBoost 3.0 - 2009 10 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	private $theme_properties;
	private static $lang;

	public function __construct()
	{
		parent::__construct();

		$this->load_lang();
	}

	private function load_lang()
	{
		self::$lang = LangLoader::get_all_langs();
	}

	public function display($content)
	{
		$view = new FileTemplate('admin/body.tpl');
		$view->add_lang(self::$lang);

		$header_logo_path = '';
		$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());

		if ($theme)
		{
			$customize_interface = $theme->get_customize_interface();
			$header_logo_path = $customize_interface->get_header_logo_path();
		}

		$view->put_all(array(
			'C_HEADER_LOGO' => !empty($header_logo_path),

			'SITE_NAME'        => GeneralConfig::load()->get_site_name(),
			'SITE_SLOGAN'      => GeneralConfig::load()->get_site_slogan(),
			'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version(),
			'CONTENT'          => $content,

			'U_HEADER_LOGO' => Url::to_rel($header_logo_path),
		));

		if (GraphicalEnvironmentConfig::load()->is_page_bench_enabled())
		{
			$view->put_all(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH'           => AppContext::get_bench()->to_string(), //Fin du benchmark
				'REQ'             => PersistenceContext::get_querier()->get_executed_requests_count(),
				'MEMORY_USED'     => AppContext::get_bench()->get_memory_php_used(),
			));
		}

		if (GraphicalEnvironmentConfig::load()->get_display_theme_author() && $theme)
		{
			$theme_configuration = $theme->get_configuration();
			$view->put_all(array(
				'C_DISPLAY_AUTHOR_THEME' => true,

				'L_THEME_NAME'   => $theme_configuration->get_name(),
				'L_THEME_AUTHOR' => $theme_configuration->get_author_name(),

				'U_THEME_AUTHOR_LINK' => $theme_configuration->get_author_link(),
			));
		}

		$view->put('SUBHEADER_MENU', self::get_subheader_tpl());

		$this->display_page($view);
	}

	private function display_page(View $body_template)
	{
		$view = new FileTemplate('admin/frame.tpl');
		$view->add_lang(self::$lang);

		$customization_config = CustomizationConfig::load();
		$cookiebar_config = CookieBarConfig::load();
		$maintenance_config = MaintenanceConfig::load();

		$js_top_tpl = new FileTemplate('js_top.tpl');
		$js_top_tpl->add_lang(self::$lang);
		$js_top_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => false
		));

		$js_bottom_tpl = new FileTemplate('js_bottom.tpl');
		$js_bottom_tpl->add_lang(self::$lang);
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
			'BODY'         => $body_template,
		));

		$view->display();
	}

	private static function get_subheader_tpl()
	{
		$subheader_tpl = new FileTemplate('admin/subheader_menu.tpl');
		$subheader_tpl->add_lang(self::$lang);

		$modules = ModulesManager::get_activated_modules_map_sorted_by_localized_name();

		$modules_number = 0;
		foreach ($modules as $module)
		{
			if ($module->get_configuration()->get_admin_menu() == 'modules')
			{
				$modules_number++;
			}
		}

		$array_pos = array(0, 4, 4, 3, 3, 1);
		$menus_numbers = array(
			'index' => 1,
			'administration' => 2,
			'tools' => 3,
			'members' => 4,
			'content' => 5,
			'modules' => 6
		);

		foreach ($modules as $module)
		{
			$module_id = $module->get_id();
			$configuration = $module->get_configuration();
			$menu_pos_name = $configuration->get_admin_menu();
			$menu_pos = 0;

			if (!empty($menu_pos_name) && !empty($menus_numbers[$menu_pos_name]))
			{
				$menu_pos = $menus_numbers[$menu_pos_name];
			}

			if ($menu_pos > 0)
			{
				$array_pos[$menu_pos-1]++;
				$idmenu = $array_pos[$menu_pos - 1];
				$subheader_tpl->put('C_ADMIN_LINKS_' . $menu_pos, true);

				$subheader_tpl->assign_block_vars('admin_links_' . $menu_pos, array(
					'MODULE_MENU' => ModuleTreeLinksService::display_admin_actions_menu($module)
				));
			}
		}

		return $subheader_tpl;
	}
}
?>
