<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 05 31
 * @since       PHPBoost 3.0 - 2009 10 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	private $theme_properties;
	private static $lang;
	private static $lang_admin;

	public function __construct()
	{
		parent::__construct();

		$this->load_lang();
	}

	private function load_lang()
	{
		self::$lang = LangLoader::get('main');
		self::$lang_admin = LangLoader::get('admin');
	}

	public function display($content)
	{
		$template = new FileTemplate('admin/body.tpl');
		$template->add_lang(self::$lang);

		$header_logo_path = '';
		$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());

		if ($theme)
		{
			$customize_interface = $theme->get_customize_interface();
			$header_logo_path = $customize_interface->get_header_logo_path();
		}

		$template->put_all(array(
			'SITE_NAME'             => GeneralConfig::load()->get_site_name(),
			'SITE_SLOGAN'           => GeneralConfig::load()->get_site_slogan(),
			'C_HEADER_LOGO'         => !empty($header_logo_path),
			'HEADER_LOGO'           => Url::to_rel($header_logo_path),
			'PHPBOOST_VERSION'      => GeneralConfig::load()->get_phpboost_major_version(),
			'CONTENT'               => $content,
			'L_INDEX_SUPPORT'       => self::$lang['index.support'],
			'L_INDEX_FAQ'           => self::$lang['index.faq'],
			'L_INDEX_DOCUMENTATION' => self::$lang['index.documentation'],
			'L_POWERED_BY'          => self::$lang['powered_by'],
			'L_PHPBOOST_RIGHT'      => self::$lang['phpboost_right'],
			'L_PHPBOOST_LINK'       => self::$lang['phpboost_link'],
			'L_INDEX_DASHBOARD'     => self::$lang['index.dashboard'],
			'L_INDEX_SITE'          => self::$lang['index.site'],
			'L_EXTEND_MENU'         => self::$lang['index.extend_menu'],
			'L_DISCONNECT'          => self::$lang['index.disconnect'],
			'L_ADMIN_MAIN_MENU'     => self::$lang['admin.main_menu'],
			'L_NEED_HELP'           => self::$lang['admin.need_help'],
		));

		if (GraphicalEnvironmentConfig::load()->is_page_bench_enabled())
		{
			$template->put_all(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH'           => AppContext::get_bench()->to_string(), //Fin du benchmark
				'REQ'             => PersistenceContext::get_querier()->get_executed_requests_count(),
				'MEMORY_USED'     => AppContext::get_bench()->get_memory_php_used(),
				'L_REQ'           => self::$lang['sql_req'],
				'L_ACHIEVED'      => self::$lang['achieved'],
				'L_UNIT_SECOND'   => LangLoader::get_message('unit.seconds', 'date-common')
			));
		}

		if (GraphicalEnvironmentConfig::load()->get_display_theme_author() && $theme)
		{
			$theme_configuration = $theme->get_configuration();
			$template->put_all(array(
				'C_DISPLAY_AUTHOR_THEME' => true,
				'L_THEME'                => self::$lang['theme'],
				'L_THEME_NAME'           => $theme_configuration->get_name(),
				'L_BY'                   => TextHelper::strtolower(self::$lang['by']),
				'L_THEME_AUTHOR'         => $theme_configuration->get_author_name(),
				'U_THEME_AUTHOR_LINK'    => $theme_configuration->get_author_link(),
			));
		}

		$template->put('subheader_menu', self::get_subheader_tpl());

		$this->display_page($template);
	}

	private function display_page(View $body_template)
	{
		$template = new FileTemplate('admin/frame.tpl');

		$customization_config = CustomizationConfig::load();
		$cookiebar_config = CookieBarConfig::load();
		$maintenance_config = MaintenanceConfig::load();

		$js_top_tpl = new FileTemplate('js_top.tpl');
		$js_top_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED'     => false
		));

		$js_bottom_tpl = new FileTemplate('js_bottom.tpl');
		$js_bottom_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => false
		));

		$template->put_all(array(
			'C_FAVICON'           => $customization_config->favicon_exists(),
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'FAVICON'             => Url::to_rel($customization_config->get_favicon_path()),
			'FAVICON_TYPE'        => $customization_config->favicon_type(),
			'TITLE'               => $this->get_seo_meta_data()->get_full_title(),
			'MODULES_CSS'         => $this->get_modules_css_files_html_code(),
			'JS_TOP'              => $js_top_tpl,
			'JS_BOTTOM'           => $js_bottom_tpl,
			'L_XML_LANGUAGE'      => self::$lang['xml_lang'],
			'BODY'                => $body_template
		));

		$template->display();
	}

	private static function get_subheader_tpl()
	{
		$subheader_lang = LangLoader::get('admin-links-common');
		$subheader_tpl = new FileTemplate('admin/subheader_menu.tpl');
		$subheader_tpl->add_lang($subheader_lang);

		$modules = ModulesManager::get_activated_modules_map_sorted_by_localized_name();

		$modules_number = 0;
		foreach ($modules as $module)
		{
			if ($module->get_configuration()->get_admin_menu() == 'modules')
			{
				$modules_number++;
			}
		}

		$subheader_tpl->put_all(array(
			'L_ADD'                  => $subheader_lang['add'],
			'L_ADMINISTRATION'       => $subheader_lang['administration'],
			'L_MANAGEMENT'           => $subheader_lang['management'],
			'L_CONFIGURATION'        => $subheader_lang['configuration'],
			'L_CONFIG_GENERAL'       => $subheader_lang['administration.configuration.general'],
			'L_CONFIG_ADVANCED'      => $subheader_lang['administration.configuration.advanced'],
			'L_MAIL_CONFIG'          => $subheader_lang['administration.configuration.mail'],
			'L_THEMES'               => $subheader_lang['administration.themes'],
			'L_LANGS'                => $subheader_lang['administration.langs'],
			'L_SMILEY'               => $subheader_lang['administration.smileys'],
			'L_ADMINISTRATOR_ALERTS' => $subheader_lang['administration.alerts'],
			'L_TOOLS'                => $subheader_lang['tools'],
			'L_UPDATES'              => $subheader_lang['updates'],
			'L_KERNEL'               => $subheader_lang['tools.updates.kernel'],
			'L_MAINTAIN'             => $subheader_lang['tools.maintain'],
			'L_CACHE'                => $subheader_lang['tools.cache'],
			'L_SYNDICATION_CACHE'    => $subheader_lang['tools.cache.syndication'],
			'L_CSS_CACHE_CONFIG'     => $subheader_lang['tools.cache.css'],
			'L_ERRORS'               => LangLoader::get_message('errors', 'admin-errors-common'),
			'L_LOGGED_ERRORS'        => $subheader_lang['tools.errors-archived'],
			'L_404_ERRORS'           => $subheader_lang['tools.404-errors-archived'],
			'L_SERVER'               => $subheader_lang['tools.server'],
			'L_PHPINFO'              => $subheader_lang['tools.server.phpinfo'],
			'L_SYSTEM_REPORT'        => $subheader_lang['tools.server.system-report'],
			'L_USER'                 => $subheader_lang['users'],
			'L_PUNISHEMENT'          => $subheader_lang['users.punishement'],
			'L_GROUP'                => $subheader_lang['users.groups'],
			'L_EXTEND_FIELD'         => $subheader_lang['users.extended-fields'],
			'L_CONTENT'              => $subheader_lang['content'],
			'L_CONTENT_CONFIG'       => $subheader_lang['content'],
			'L_MENUS'                => $subheader_lang['content.menus'],
			'L_ADD_CONTENT_MENU'     => $subheader_lang['content.menus.content'],
			'L_ADD_LINKS_MENU'       => $subheader_lang['content.menus.links'],
			'L_ADD_FEED_MENU'        => $subheader_lang['content.menus.feed'],
			'L_FILES'                => $subheader_lang['content.files'],
			'L_COMMENTS'             => $subheader_lang['content.comments'],
			'L_MODULES'              => $subheader_lang['modules'],
			'U_NBR_MODULES'          => ceil( ($modules_number + 1) / 7),
			'U_INDEX_SITE'           => Environment::get_home_page(),
			'C_ADMIN_LINKS_1'        => false,
			'C_ADMIN_LINKS_2'        => false,
			'C_ADMIN_LINKS_3'        => false,
			'C_ADMIN_LINKS_4'        => false,
			'C_ADMIN_LINKS_5'        => false,
			'C_ADMIN_LINKS_1'        => false
		));

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
