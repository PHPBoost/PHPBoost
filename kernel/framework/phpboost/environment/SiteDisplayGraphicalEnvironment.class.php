<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 16
 * @since       PHPBoost 3.0 - 2009 10 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

class SiteDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	/**
	 * @var BreadCrumb The page breadcrumb
	 */
	private $breadcrumb = null;

	private static $main_lang;

	public function __construct()
	{
		parent::__construct();
		$this->load_langs();
		$this->set_breadcrumb(new BreadCrumb());
	}

	private function load_langs()
	{
		self::$main_lang = LangLoader::get('main');
	}

	/**
	 * {@inheritdoc}
	 */
	public function display($content)
	{
		$template = new FileTemplate('body.tpl');

		$header_logo_path = '';
		$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());

		if ($theme)
		{
			$customize_interface = $theme->get_customize_interface();
			$header_logo_path = $customize_interface->get_header_logo_path();
		}

		$template->put_all(array(
			'MAINTAIN'         => $this->display_site_maintenance(),
			'SITE_NAME'        => GeneralConfig::load()->get_site_name(),
			'SITE_SLOGAN'      => GeneralConfig::load()->get_site_slogan(),
			'C_HEADER_LOGO'    => !empty($header_logo_path),
			'HEADER_LOGO'      => Url::to_rel($header_logo_path),
			'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version(),
			'CONTENT'          => $content,
			'ACTIONS_MENU'     => ModuleTreeLinksService::display_actions_menu(),
			'L_POWERED_BY'     => self::$main_lang['powered_by'],
			'L_PHPBOOST_RIGHT' => self::$main_lang['phpboost_right'],
			'L_PHPBOOST_LINK'  => self::$main_lang['phpboost_link'],
		));

		$this->display_kernel_message($template);
		$this->display_counter($template);
		$this->display_menus($template);
		$this->get_breadcrumb()->display($template);

		if (GraphicalEnvironmentConfig::load()->is_page_bench_enabled())
		{
			$template->put_all(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH'           => AppContext::get_bench()->to_string(),
				'REQ'             => PersistenceContext::get_querier()->get_executed_requests_count(),
				'MEMORY_USED'     => AppContext::get_bench()->get_memory_php_used(),
				'L_REQ'           => self::$main_lang['sql_req'],
				'L_ACHIEVED'      => self::$main_lang['achieved'],
				'L_UNIT_SECOND'   => LangLoader::get_message('unit.seconds', 'date-common')
			));
		}

		if (GraphicalEnvironmentConfig::load()->get_display_theme_author() && $theme)
		{
			$theme_configuration = $theme->get_configuration();
			$template->put_all(array(
				'C_DISPLAY_AUTHOR_THEME' => true,
				'L_THEME'                => self::$main_lang['theme'],
				'L_THEME_NAME'           => $theme_configuration->get_name(),
				'L_BY'                   => TextHelper::strtolower(self::$main_lang['by']),
				'L_THEME_AUTHOR'         => $theme_configuration->get_author_name(),
				'U_THEME_AUTHOR_LINK'    => $theme_configuration->get_author_link(),
			));
		}

		$this->display_page($template);
	}

	function display_page(View $body_template)
	{
		$template = new FileTemplate('frame.tpl');

		$customization_config = CustomizationConfig::load();
		$cookiebar_config = CookieBarConfig::load();
		$maintenance_config = MaintenanceConfig::load();

		$js_top_tpl = new FileTemplate('js_top.tpl');
		$js_top_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED'     => $cookiebar_config->is_cookiebar_enabled() && !$maintenance_config->is_under_maintenance(),
			'COOKIEBAR_DURATION'      => $cookiebar_config->get_cookiebar_duration(),
			'COOKIEBAR_TRACKING_MODE' => $cookiebar_config->get_cookiebar_tracking_mode(),
			'COOKIEBAR_CONTENT'       => TextHelper::to_js_string($cookiebar_config->get_cookiebar_content())
		));

		$js_bottom_tpl = new FileTemplate('js_bottom.tpl');
		$js_bottom_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => $cookiebar_config->is_cookiebar_enabled() && !$maintenance_config->is_under_maintenance()
		));

		$seo_meta_data = $this->get_seo_meta_data();
		$description = $seo_meta_data->get_full_description();
		$template->put_all(array(
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'C_FAVICON'           => $customization_config->favicon_exists(),
			'C_OPENGRAPH'         => ContentManagementConfig::load()->is_opengraph_enabled(),
			'C_CANONICAL_URL'     => $seo_meta_data->canonical_link_exists(),
			'C_PICTURE_URL'       => $seo_meta_data->picture_url_exists(),
			'C_DESCRIPTION'       => !empty($description),
			'FAVICON'             => Url::to_rel($customization_config->get_favicon_path()),
			'FAVICON_TYPE'        => $customization_config->favicon_type(),
			'SITE_NAME'           => GeneralConfig::load()->get_site_name(),
			'TITLE'               => $seo_meta_data->get_full_title(),
			'PAGE_TITLE'          => $seo_meta_data->get_title(),
			'SITE_DESCRIPTION'    => $description,
			'U_CANONICAL'         => $seo_meta_data->get_canonical_link(),
			'U_PICTURE'           => $seo_meta_data->get_picture_url(),
			'PAGE_TYPE'           => $seo_meta_data->get_page_type(),
			'L_XML_LANGUAGE'      => self::$main_lang['xml_lang'],
			'PHPBOOST_VERSION'    => GeneralConfig::load()->get_phpboost_major_version(),
			'MODULES_CSS'         => $this->get_modules_css_files_html_code(),
			'JS_TOP'              => $js_top_tpl,
			'JS_BOTTOM'           => $js_bottom_tpl,
			'BODY'                => $body_template
		));

		foreach ($seo_meta_data->get_additionnal_properties() as $og_id => $og_value)
		{
			if (is_array($og_value))
			{
				foreach ($og_value as $og_sub_value)
				{
					$template->assign_block_vars('og_additionnal_properties', array(
						'ID' => $og_id,
						'VALUE' => $og_sub_value
					));
				}
			}
			else
			{
				$template->assign_block_vars('og_additionnal_properties', array(
					'ID' => $og_id,
					'VALUE' => $og_value
				));
			}
		}

		$template->display(true);
	}

	protected function display_counter(Template $template)
	{
		//If the counter is to be displayed, we display it
		if (GraphicalEnvironmentConfig::load()->is_visit_counter_enabled())
		{
			try {
				$visit_counter = PersistenceContext::get_querier()->select_single_row(DB_TABLE_VISIT_COUNTER, array('ip AS nbr_ip', 'total'), 'WHERE id = 1');
			} catch (RowNotFoundException $e) {
				$visit_counter = array('nbr_ip' => 1, 'total' => 1);
			}

			$template->put_all(array(
				'L_VISIT'             => self::$main_lang['guest_s'],
				'L_TODAY'             => LangLoader::get_message('today', 'date-common'),
				'C_VISIT_COUNTER'     => true,
				'VISIT_COUNTER_TOTAL' => !empty($visit_counter['nbr_ip']) ? $visit_counter['nbr_ip'] : 1,
				'VISIT_COUNTER_DAY'   => !empty($visit_counter['total']) ? $visit_counter['total'] : 1
			));
		}
	}

	protected function display_menus(Template $template)
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
						case Menu::BLOCK_POSITION__HEADER:
							$template->put('C_MENUS_HEADER_CONTENT', true);
							$template->assign_block_vars('menus_header', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__SUB_HEADER:
							$template->put('C_MENUS_SUB_HEADER_CONTENT', true);
							$template->assign_block_vars('menus_sub_header', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__LEFT:
							$template->put('C_MENUS_LEFT_CONTENT', true);
							$template->assign_block_vars('menus_left', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__RIGHT:
							$template->put('C_MENUS_RIGHT_CONTENT', true);
							$template->assign_block_vars('menus_right', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__TOP_CENTRAL:
							$template->put('C_MENUS_TOPCENTRAL_CONTENT', $menu_content);
							$template->assign_block_vars('menus_top_central', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__BOTTOM_CENTRAL:
							$template->put('C_MENUS_BOTTOM_CENTRAL_CONTENT', $menu_content);
							$template->assign_block_vars('menus_bottom_central', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__TOP_FOOTER:
							$template->put('C_MENUS_TOP_FOOTER_CONTENT', true);
							$template->assign_block_vars('menus_top_footer', array('MENU' => $menu_content));
						break;

						case Menu::BLOCK_POSITION__FOOTER:
							$template->put('C_MENUS_FOOTER_CONTENT', true);
							$template->assign_block_vars('menus_footer', array('MENU' => $menu_content));
					}
				}
			}
		}
	}

	protected function display_site_maintenance()
	{
		//Users not authorized cannot come here
		parent::process_site_maintenance();

		$template =  new FileTemplate('maintain.tpl');

		$maintenance_config = MaintenanceConfig::load();
		if ($maintenance_config->is_under_maintenance() && $maintenance_config->get_display_duration() && (!AppContext::get_current_user()->is_admin() || (AppContext::get_current_user()->is_admin() && $maintenance_config->get_display_duration_for_admin())))
		{
			$date_lang = LangLoader::get('date-common');
			//Durée de la maintenance.
			$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000, 21600, 25200, 28800, 57600, 86400, 172800, 604800);
			$array_delay = array(LangLoader::get_message('unspecified', 'main'),
				'1 ' . $date_lang['minute'], '5 ' . $date_lang['minutes'], '10 ' . $date_lang['minutes'], '15 ' . $date_lang['minutes'], '30 ' . $date_lang['minutes'],
				'1 ' . $date_lang['hour'], '2 ' . $date_lang['hours'], '3 ' . $date_lang['hours'], '4 ' . $date_lang['hours'], '5 ' . $date_lang['hours'], '6 ' . $date_lang['hours'], '7 ' . $date_lang['hours'], '8 ' . $date_lang['hours'], '16 ' . $date_lang['hours'],
				'1 ' . $date_lang['day'], '2 ' . $date_lang['day'],
				'1 ' . $date_lang['week']);

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

			$template->put_all(array(
				'C_ALERT_MAINTAIN'        => true,
				'C_MAINTAIN_DELAY'        => true,
				'UNSPECIFIED'             => $maintenance_config->is_unlimited_maintenance() ? 0 : 1,
				'DELAY'                   => isset($array_delay[$key_delay]) ? $array_delay[$key_delay] : '0',
				'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release),
				'MAINTAIN_NOW_FORMAT'     => implode(',', $array_now),
				'L_MAINTAIN_DELAY'        => self::$main_lang['maintain_delay'],
				'L_LOADING'               => self::$main_lang['loading'],
				'L_DAYS'                  => $date_lang['days'],
				'L_HOURS'                 => $date_lang['hours'],
				'L_MIN'                   => $date_lang['minutes'],
				'L_SEC'                   => $date_lang['seconds']
			));
		}
		
		if ($maintenance_config->is_under_maintenance() && AppContext::get_current_user()->is_admin())
		{
			$form = new HTMLForm('disable_maintenance_form', '', false);

			$submit_button = new FormButtonSubmit(LangLoader::get_message('disable.maintenance', 'admin-maintain-common'), 'disable_maintenance', '', 'bgc warning disable-maintenance-button');
			$form->add_button($submit_button);

			if ($submit_button->has_been_submited() && $form->validate())
			{
				$maintenance_config->disable_maintenance();
				MaintenanceConfig::save();
				AppContext::get_response()->redirect(AppContext::get_request()->get_current_url());
			}
			else
				$template->put('DISABLE_MAINTENANCE', $form->display());
		}
		
		return $template;
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
