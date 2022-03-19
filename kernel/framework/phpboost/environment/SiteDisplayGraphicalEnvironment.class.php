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
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SiteDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	/**
	 * @var BreadCrumb The page breadcrumb
	 */
	private $breadcrumb = null;

	private static $lang;

	public function __construct()
	{
		parent::__construct();
		$this->set_breadcrumb(new BreadCrumb());

		$this->load_lang();
	}

	private function load_lang()
	{
		self::$lang = LangLoader::get_all_langs();
	}

	/**
	 * {@inheritdoc}
	 */
	public function display($content)
	{
		$view = new FileTemplate('body.tpl');
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

			'MAINTAIN'         => $this->display_site_maintenance(),
			'SITE_NAME'        => GeneralConfig::load()->get_site_name(),
			'SITE_SLOGAN'      => GeneralConfig::load()->get_site_slogan(),
			'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version(),
			'CONTENT'          => $content,
			'ACTIONS_MENU'     => ModuleTreeLinksService::display_actions_menu(),

			'U_HEADER_LOGO' => Url::to_rel($header_logo_path),
		));

		$this->display_kernel_message($view);
		$this->display_counter($view);
		$this->display_menus($view);
		$this->get_breadcrumb()->display($view);

		if (GraphicalEnvironmentConfig::load()->is_page_bench_enabled())
		{
			$view->put_all(array(
				'C_DISPLAY_BENCH' => true,

				'BENCH'       => AppContext::get_bench()->to_string(),
				'REQ'         => PersistenceContext::get_querier()->get_executed_requests_count(),
				'MEMORY_USED' => AppContext::get_bench()->get_memory_php_used(),
			));
		}

		if (GraphicalEnvironmentConfig::load()->get_display_theme_author() && $theme)
		{
			$theme_configuration = $theme->get_configuration();
			$view->put_all(array(
				'C_DISPLAY_AUTHOR_THEME' => true,

				'U_THEME_AUTHOR_LINK' => $theme_configuration->get_author_link(),

				'L_THEME_NAME'   => $theme_configuration->get_name(),
				'L_THEME_AUTHOR' => $theme_configuration->get_author_name(),
			));
		}

		$this->display_page($view);
	}

	function display_page(View $body_template)
	{
		$view = new FileTemplate('frame.tpl');
		$view->add_lang(self::$lang);

		$customization_config = CustomizationConfig::load();
		$cookiebar_config = CookieBarConfig::load();
		$maintenance_config = MaintenanceConfig::load();

		$js_top_tpl = new FileTemplate('js_top.tpl');
		$js_top_tpl->add_lang(self::$lang);
		$js_top_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED'     => $cookiebar_config->is_cookiebar_enabled() && !$maintenance_config->is_under_maintenance(),
			'COOKIEBAR_DURATION'      => $cookiebar_config->get_cookiebar_duration(),
			'COOKIEBAR_TRACKING_MODE' => $cookiebar_config->get_cookiebar_tracking_mode(),
			'COOKIEBAR_CONTENT'       => TextHelper::to_js_string($cookiebar_config->get_cookiebar_content())
		));

		$js_bottom_tpl = new FileTemplate('js_bottom.tpl');
		$js_bottom_tpl->add_lang(self::$lang);
		$js_bottom_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => $cookiebar_config->is_cookiebar_enabled() && !$maintenance_config->is_under_maintenance()
		));

		$seo_meta_data = $this->get_seo_meta_data();
		$description = $seo_meta_data->get_full_description();
		$view->put_all(array(
			'C_FAVICON'       => $customization_config->favicon_exists(),
			'C_OPENGRAPH'     => ContentManagementConfig::load()->is_opengraph_enabled(),
			'C_CANONICAL_URL' => $seo_meta_data->canonical_link_exists(),
			'C_PICTURE_URL'   => $seo_meta_data->picture_url_exists(),
			'C_DESCRIPTION'   => !empty($description),

			'FAVICON_TYPE'     => $customization_config->favicon_type(),
			'SITE_NAME'        => GeneralConfig::load()->get_site_name(),
			'TITLE'            => $seo_meta_data->get_full_title(),
			'PAGE_TITLE'       => $seo_meta_data->get_title(),
			'SITE_DESCRIPTION' => $description,
			'PAGE_TYPE'        => $seo_meta_data->get_page_type(),
			'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version(),
			'MODULES_CSS'      => $this->get_modules_css_files_html_code(),
			'JS_TOP'           => $js_top_tpl,
			'JS_BOTTOM'        => $js_bottom_tpl,
			'BODY'             => $body_template,

			'U_FAVICON'   => Url::to_rel($customization_config->get_favicon_path()),
			'U_CANONICAL' => $seo_meta_data->get_canonical_link(),
			'U_PICTURE'   => $seo_meta_data->get_picture_url(),
		));

		foreach ($seo_meta_data->get_additionnal_properties() as $og_id => $og_value)
		{
			if (is_array($og_value))
			{
				foreach ($og_value as $og_sub_value)
				{
					$view->assign_block_vars('og_additionnal_properties', array(
						'ID'    => $og_id,
						'VALUE' => $og_sub_value
					));
				}
			}
			else
			{
				$view->assign_block_vars('og_additionnal_properties', array(
					'ID'    => $og_id,
					'VALUE' => $og_value
				));
			}
		}

		$view->display(true);
	}

	protected function display_counter(Template $view)
	{
		//If the counter is to be displayed, we display it
		if (GraphicalEnvironmentConfig::load()->is_visit_counter_enabled())
		{
			try {
				$visit_counter = PersistenceContext::get_querier()->select_single_row(DB_TABLE_VISIT_COUNTER, array('ip AS nbr_ip', 'total'), 'WHERE id = 1');
			} catch (RowNotFoundException $e) {
				$visit_counter = array('nbr_ip' => 1, 'total' => 1);
			}

			$view->put_all(array(
				'C_VISIT_COUNTER' => true,

				'VISIT_COUNTER_TOTAL' => !empty($visit_counter['nbr_ip']) ? $visit_counter['nbr_ip'] : 1,
				'VISIT_COUNTER_DAY'   => !empty($visit_counter['total']) ? $visit_counter['total'] : 1
			));
		}
	}

	protected function display_menus(Template $view)
	{
		$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());
		$menus = MenusCache::load()->get_menus();
		$columns_disabled = $theme ? $theme->get_columns_disabled() : new ColumnsDisabled();

		foreach ($menus as $cached_menu)
		{
			$menu = $cached_menu->get_menu();
			if ($menu->check_auth() && !$columns_disabled->menus_column_is_disabled($menu->get_block()))
			{
				$display = false;
				$filters = $menu->get_filters();
				$nbr_filters = count($filters);
				foreach ($filters as $filter)
				{
					if (($nbr_filters > 1 && $filter->get_pattern() != '/') || ($filter->match() && !$display))
						$display = true;
				}

				if ($display)
				{
					$menu_content = $cached_menu->has_cached_string() ? $cached_menu->get_cached_string() : $menu->display();
					$block = $menu->get_block();
					switch ($block) {
						case Menu::BLOCK_POSITION__TOP_HEADER:
							$view->put('C_MENUS_TOP_HEADER_CONTENT', true);
							$view->assign_block_vars('menus_top_header', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__HEADER:
							$view->put('C_MENUS_HEADER_CONTENT', true);
							$view->assign_block_vars('menus_header', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__SUB_HEADER:
							$view->put('C_MENUS_SUB_HEADER_CONTENT', true);
							$view->assign_block_vars('menus_sub_header', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__LEFT:
							$view->put('C_MENUS_LEFT_CONTENT', true);
							$view->assign_block_vars('menus_left', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__RIGHT:
							$view->put('C_MENUS_RIGHT_CONTENT', true);
							$view->assign_block_vars('menus_right', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__TOP_CENTRAL:
							$view->put('C_MENUS_TOPCENTRAL_CONTENT', $menu_content);
							$view->assign_block_vars('menus_top_central', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__BOTTOM_CENTRAL:
							$view->put('C_MENUS_BOTTOM_CENTRAL_CONTENT', $menu_content);
							$view->assign_block_vars('menus_bottom_central', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__TOP_FOOTER:
							$view->put('C_MENUS_TOP_FOOTER_CONTENT', true);
							$view->assign_block_vars('menus_top_footer', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__FOOTER:
							$view->put('C_MENUS_FOOTER_CONTENT', true);
							$view->assign_block_vars('menus_footer', array('MENU' => $menu_content));
					}
				}
			}
		}
	}

	protected function display_site_maintenance()
	{
		//Users not authorized cannot come here
		parent::process_site_maintenance();

		$view =  new FileTemplate('maintain.tpl');
		$view->add_lang(self::$lang);

		$maintenance_config = MaintenanceConfig::load();
		if ($maintenance_config->is_under_maintenance() && $maintenance_config->get_display_duration() && (!AppContext::get_current_user()->is_admin() || (AppContext::get_current_user()->is_admin() && $maintenance_config->get_display_duration_for_admin())))
		{
			//Durée de la maintenance.
			$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000, 21600, 25200, 28800, 57600, 86400, 172800, 604800);
			$array_delay = array(self::$lang['common.unspecified'],
				'1 ' . self::$lang['date.minute'], '5 ' . self::$lang['date.minutes'], '10 ' . self::$lang['date.minutes'], '15 ' . self::$lang['date.minutes'], '30 ' . self::$lang['date.minutes'],
				'1 ' . self::$lang['date.hour'], '2 ' . self::$lang['date.hours'], '3 ' . self::$lang['date.hours'], '4 ' . self::$lang['date.hours'], '5 ' . self::$lang['date.hours'], '6 ' . self::$lang['date.hours'], '7 ' . self::$lang['date.hours'], '8 ' . self::$lang['date.hours'], '16 ' . self::$lang['date.hours'],
				'1 ' . self::$lang['date.day'], '2 ' . self::$lang['date.day'],
				'1 ' . self::$lang['date.week']);

			//Retourne le délai de maintenance le plus proche.
			if (!$maintenance_config->is_unlimited_maintenance())
			{
				$key_delay = 0;
				$current_time = time();
				$array_size = count($array_time) - 1;
				$end_timestamp = $maintenance_config->get_end_date()->get_timestamp();
				for ($i = $array_size; $i >= 1; $i--)
				{
					if (($end_timestamp - $current_time) - $array_time[$i] < 0
					&&  ($end_timestamp - $current_time) - $array_time[$i-1] > 0)
					{
						$key_delay = $i-1;
						break;
					}
				}

				//Calcul du format de la date
				$array_release = array(
				Date::to_format($end_timestamp, 'Y', Timezone::SITE_TIMEZONE),
				(Date::to_format($end_timestamp, 'n', Timezone::SITE_TIMEZONE) - 1),
				Date::to_format($end_timestamp, 'j', Timezone::SITE_TIMEZONE),
				Date::to_format($end_timestamp, 'G', Timezone::SITE_TIMEZONE),
				Date::to_format($end_timestamp, 'i', Timezone::SITE_TIMEZONE),
				Date::to_format($end_timestamp, 's', Timezone::SITE_TIMEZONE));

				$array_now = array(
				Date::to_format(time(), 'Y', Timezone::SITE_TIMEZONE),
				(Date::to_format(time(), 'n', Timezone::SITE_TIMEZONE) - 1),
				Date::to_format(time(), 'j', Timezone::SITE_TIMEZONE),
				Date::to_format(time(), 'G', Timezone::SITE_TIMEZONE),
				Date::to_format(time(), 'i', Timezone::SITE_TIMEZONE),
				Date::to_format(time(), 's', Timezone::SITE_TIMEZONE));
			}
			else //Délai indéterminé.
			{
				$key_delay = 0;
				$array_release = array('0', '0', '0', '0', '0', '0');
				$array_now = array('0', '0', '0', '0', '0', '0');
			}

			$view->put_all(array(
				'C_ALERT_MAINTAIN' => true,
				'C_MAINTAIN_DELAY' => true,

				'UNSPECIFIED'             => $maintenance_config->is_unlimited_maintenance() ? 0 : 1,
				'DELAY'                   => isset($array_delay[$key_delay]) ? $array_delay[$key_delay] : '0',
				'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release),
				'MAINTAIN_NOW_FORMAT'     => implode(',', $array_now),
			));
		}

		if ($maintenance_config->is_under_maintenance() && AppContext::get_current_user()->is_admin())
		{
			$form = new HTMLForm('disable_maintenance_form', '', false);

			$submit_button = new FormButtonSubmit(self::$lang['admin.disable.maintenance'], 'disable_maintenance', '', 'bgc-full warning disable-maintenance-button');
			$form->add_button($submit_button);

			if ($submit_button->has_been_submited() && $form->validate())
			{
				$maintenance_config->disable_maintenance();
				MaintenanceConfig::save();
				AppContext::get_response()->redirect(AppContext::get_request()->get_current_url());
			}
			else
				$view->put('DISABLE_MAINTENANCE', $form->display());
		}

		return $view;
	}

	/**
	 * Returns the bread crumb
	 * @return BreadCrumb The breadcrumb
	 */
	public function get_breadcrumb()
	{
		return $this->breadcrumb;
	}

	/**
	 * Sets the page's bread crumb
	 * @param BreadCrumb $breadcrumb The bread crumb to use
	 */
	public function set_breadcrumb(BreadCrumb $breadcrumb)
	{
		$this->breadcrumb = $breadcrumb;
		$this->breadcrumb->set_graphical_environment($this);
	}
}
?>
