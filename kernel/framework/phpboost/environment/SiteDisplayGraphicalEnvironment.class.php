<?php
/*##################################################
 *                   SiteDisplayGraphicalEnvironment.class.php
 *                            -------------------
 *   begin                : October 01, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @package core
 * @subpackage environment
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SiteDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	/**
	 * @var bool
	 */
	private $display_left_menus = true;
	/**
	 * @var bool
	 */
	private $display_right_menus = true;
	/**
	 * @var BreadCrumb The page breadcrumb
	 */
	private $breadcrumb = null;

	public function __construct()
	{
		parent::__construct();
		$this->set_breadcrumb(new BreadCrumb());
	}

	/**
	 * {@inheritdoc}
	 */
	function display_header()
	{
		global $CONFIG, $LANG, $Errorh, $Cache;

		self::set_page_localization($this->get_page_title());

		$template =  new FileTemplate('header.tpl');

		$this->display_site_maintenance($template);

		$this->add_menus_css_files();

		$general_config = GeneralConfig::load();
		
		$template->assign_vars(array(
			'SITE_NAME' => $general_config->get_site_name(),
			'C_BBCODE_TINYMCE_MODE' => AppContext::get_user()->get_attribute('user_editor') == 'tinymce',
			'TITLE' => $this->get_page_title(),
			'SITE_DESCRIPTION' => $general_config->get_site_description(),
			'SITE_KEYWORD' => $general_config->get_site_keywords(),
			'ALTERNATIVE_CSS' => $this->get_css_files_html_code(),
			'L_XML_LANGUAGE' => $LANG['xml_lang'],
			'L_VISIT' => $LANG['guest_s'],
			'L_TODAY' => $LANG['today'],
		));

		$this->display_counter($template);

		$this->display_menus($template);

		//Bread crumb
		$this->get_breadcrumb()->display($template);

		$template->display();
	}

	protected function add_menus_css_files()
	{

		$css_files_cache = ModulesCssFilesCache::load();
		try
		{
			$css_files = $css_files_cache->get_files_for_theme(get_utheme());
			foreach ($css_files as $file)
			{
				$this->add_css_file($file);
			}
		}
		catch(PropertyNotFoundException $ex)
		{
		}
	}

	protected function display_counter(Template $template)
	{
		global $CONFIG;

		//If the counter is to be displayed, we display it
		if (GraphicalEnvironmentConfig::load()->is_visit_counter_enabled())
		{
			$compteur = PersistenceContext::get_sql()->query_array(DB_TABLE_VISIT_COUNTER,
				'ip AS nbr_ip', 'total', 'WHERE id = "1"', __LINE__, __FILE__);

			$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
			$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';

			$template->assign_vars(array(
				'C_COMPTEUR' => true,
				'COMPTEUR_TOTAL' => $compteur_total,
				'COMPTEUR_DAY' => $compteur_day
			));
		}
	}

	protected function display_menus(Template $template)
	{
		global $MENUS;

		//Inclusion des blocs
		if (Debug::is_debug_mode_enabled())
		{
			$result = @include_once(PATH_TO_ROOT . '/cache/menus.php');
		}
		if (!$result)
		{
			global $Cache, $Errorh;
			//En cas d'échec, on régénère le cache
			$Cache->Generate_file('menus');
				
			//On inclut une nouvelle fois
			if (!include_once(PATH_TO_ROOT . '/cache/menus.php'))
			{
				$Errorh->handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
			}
		}

		$template->assign_vars(array(
			'C_MENUS_HEADER_CONTENT' => !empty($MENUS[Menu::BLOCK_POSITION__HEADER]),
		    'MENUS_HEADER_CONTENT' => $MENUS[Menu::BLOCK_POSITION__HEADER],
			'C_MENUS_SUB_HEADER_CONTENT' => !empty($MENUS[Menu::BLOCK_POSITION__SUB_HEADER]),
			'MENUS_SUB_HEADER_CONTENT' => $MENUS[Menu::BLOCK_POSITION__SUB_HEADER]
		));

		$user_theme_properties = ThemesCache::load()->get_theme_properties(get_utheme());
		$left_column  = ($user_theme_properties['left_column'] && $this->are_left_menus_enabled());
		$right_column = ($user_theme_properties['right_column'] && $this->are_right_menus_enabled());

		//Début de la colonne de gauche.
		if ($left_column) //Gestion des blocs de gauche.
		{
			// Affichage des modules droits à gauche sur les thèmes à une colonne (gauche).
			$left_column_content = $MENUS[Menu::BLOCK_POSITION__LEFT] . (!$right_column ? $MENUS[Menu::BLOCK_POSITION__RIGHT] : '');
			$template->assign_vars(array(
				'C_MENUS_LEFT_CONTENT' => !empty($left_column_content),
				'MENUS_LEFT_CONTENT' => $left_column_content
			));
		}
		if ($right_column)  //Gestion des blocs de droite.
		{
			// Affichage des modules gauches à droite sur les thèmes à une colonne (droite).
			$right_column_content = $MENUS[Menu::BLOCK_POSITION__RIGHT] . (!$left_column ? $MENUS[Menu::BLOCK_POSITION__LEFT] : '');
			$template->assign_vars(array(
				'C_MENUS_RIGHT_CONTENT' => !empty($right_column_content),
				'MENUS_RIGHT_CONTENT' => $right_column_content
			));
		}

		$template->assign_vars(array(
			'C_MENUS_TOPCENTRAL_CONTENT' => !empty($MENUS[Menu::BLOCK_POSITION__TOP_CENTRAL]),
			'MENUS_TOPCENTRAL_CONTENT' => $MENUS[Menu::BLOCK_POSITION__TOP_CENTRAL]
		));
	}

	protected function display_site_maintenance(Template $template)
	{
		global $Template, $LANG;

		//Users not authorized cannot come here
		parent::process_site_maintenance();

		$maintenance_config = MaintenanceConfig::load();
		if ($this->is_under_maintenance() && $maintenance_config->get_display_duration_for_admin())
		{
			//Durée de la maintenance.
			$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000,
			21600, 25200, 28800, 57600, 86400, 172800, 604800);
			$array_delay = array($LANG['unspecified'], '1 ' . $LANG['minute'],
				'5 ' . $LANG['minutes'], '10 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], 
				'30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], 
				'3 ' . $LANG['hours'], '4 ' . $LANG['hours'], '5 ' . $LANG['hours'], 
				'6 ' . $LANG['hours'], '7 ' . $LANG['hours'], '8 ' . $LANG['hours'], 
				'16 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], 
				'1 ' . $LANG['week']);

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
				$seconds = gmdate_format('s', $end_timestamp, TIMEZONE_SITE);
				$array_release = array(
				gmdate_format('Y', $end_timestamp, TIMEZONE_SITE),
				(gmdate_format('n', $end_timestamp, TIMEZONE_SITE) - 1),
				gmdate_format('j', $end_timestamp, TIMEZONE_SITE),
				gmdate_format('G', $end_timestamp, TIMEZONE_SITE),
				gmdate_format('i', $end_timestamp, TIMEZONE_SITE),
				($seconds < 10) ? trim($seconds, 0) : $seconds);

				$seconds = gmdate_format('s', time(), TIMEZONE_SITE);
				$array_now = array(
				gmdate_format('Y', time(), TIMEZONE_SITE), (gmdate_format('n', time(),
				TIMEZONE_SITE) - 1), gmdate_format('j', time(), TIMEZONE_SITE),
				gmdate_format('G', time(), TIMEZONE_SITE), gmdate_format('i', time(),
				TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
			}
			else //Délai indéterminé.
			{
				$key_delay = 0;
				$array_release = array('0', '0', '0', '0', '0', '0');
				$array_now = array('0', '0', '0', '0', '0', '0');
			}

			$template->assign_vars(array(
				'C_ALERT_MAINTAIN' => true,
				'C_MAINTAIN_DELAY' => true,
				'UNSPECIFIED' => $maintenance_config->is_unlimited_maintenance() ? 0 : 1,
				'DELAY' => isset($array_delay[$key_delay]) ? $array_delay[$key_delay] : '0',
				'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release),
				'MAINTAIN_NOW_FORMAT' => implode(',', $array_now),
				'L_MAINTAIN_DELAY' => $LANG['maintain_delay'],
				'L_LOADING' => $LANG['loading'],
				'L_DAYS' => $LANG['days'],
				'L_HOURS' => $LANG['hours'],
				'L_MIN' => $LANG['minutes'],
				'L_SEC' => $LANG['seconds'],
			));
		}
	}

	public function enable_left_menus()
	{
		$this->display_left_menus = true;
	}

	public function disable_left_menus()
	{
		$this->display_left_menus = false;
	}

	public function enable_right_menus()
	{
		$this->display_right_menus = true;
	}

	public function disable_right_menus()
	{
		$this->display_right_menus = false;
	}

	public function are_left_menus_enabled()
	{
		return $this->display_left_menus;
	}

	public function are_right_menus_enabled()
	{
		return $this->display_right_menus;
	}

	/**
	 * {@inheritdoc}
	 */
	function display_footer()
	{
		global $CONFIG, $MENUS, $LANG;
		$template = new FileTemplate('footer.tpl');

		$theme = load_ini_file(
		PATH_TO_ROOT . '/templates/' . get_utheme() . '/config/', get_ulang());

		$template->assign_vars(array(
			'THEME' => get_utheme(),
			'C_MENUS_BOTTOM_CENTRAL_CONTENT' => !empty($MENUS[Menu::BLOCK_POSITION__BOTTOM_CENTRAL]),
			'MENUS_BOTTOMCENTRAL_CONTENT' => $MENUS[Menu::BLOCK_POSITION__BOTTOM_CENTRAL],
			'C_MENUS_TOP_FOOTER_CONTENT' => !empty($MENUS[Menu::BLOCK_POSITION__TOP_FOOTER]),
			'MENUS_TOP_FOOTER_CONTENT' => $MENUS[Menu::BLOCK_POSITION__TOP_FOOTER],
			'C_MENUS_FOOTER_CONTENT' => !empty($MENUS[Menu::BLOCK_POSITION__FOOTER]),
			'MENUS_FOOTER_CONTENT' => $MENUS[Menu::BLOCK_POSITION__FOOTER],
			'C_DISPLAY_AUTHOR_THEME' => GraphicalEnvironmentConfig::load()->get_display_theme_author(),
			'L_POWERED_BY' => $LANG['powered_by'],
			'L_PHPBOOST_RIGHT' => $LANG['phpboost_right'],
			'L_THEME' => $LANG['theme'],
			'L_THEME_NAME' => $theme['name'],
			'L_BY' => strtolower($LANG['by']),
			'L_THEME_AUTHOR' => $theme['author'],
			'U_THEME_AUTHOR_LINK' => $theme['author_link'],
		    'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version()
		));

		//We add a page to the page displayed counter
		StatsSaver::update_pages_displayed('pages');

		if ($CONFIG['bench'])
		{
			$template->assign_vars(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH' => AppContext::get_bench()->to_string(),
				'REQ' => PersistenceContext::get_querier()->get_executed_requests_count() + 
			PersistenceContext::get_sql()->get_executed_requests_number(),
				'L_REQ' => $LANG['sql_req'],
				'L_ACHIEVED' => $LANG['achieved'],
				'L_UNIT_SECOND' => $LANG['unit_seconds_short']
			));
		}

		$template->display();
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