<?php
/*##################################################
 *                 site_display_graphical_environment.class.php
 *                            -------------------
 *   begin                : October 01, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('core/environment/abstract_display_graphical_environment');

/**
 * @package core
 * @subpackage environment
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SiteDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	private $theme_properties;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/core/environment/GraphicalEnvironment#display_header()
	 */
	function display_header()
	{
		global $CONFIG, $LANG, $Errorh, $Cache, $THEME_CONFIG, $CSS;

		$template =  new Template('header.tpl');

		$this->process_site_maintenance($template);

		//Mini modules CSS
		$Cache->load('css');
		if (isset($CSS[get_utheme()]))
		{
			foreach ($CSS[get_utheme()] as $css_mini_module)
			{
				$this->add_css_file($css_mini_module);
			}
		}

		$this->theme_properties = load_ini_file(
		PATH_TO_ROOT . '/templates/' . get_utheme() . '/config/', get_ulang());

		$template->assign_vars(array(
			'SERVER_NAME' => $CONFIG['site_name'],
			'SITE_NAME' => $CONFIG['site_name'],
			'C_BBCODE_TINYMCE_MODE' => EnvironmentServices::get_user()
				->get_attribute('user_editor') == 'tinymce',
			'TITLE' => Environment::get_page_title(),
			'SITE_DESCRIPTION' => $CONFIG['site_desc'],
			'SITE_KEYWORD' => $CONFIG['site_keyword'],
			'ALTERNATIVE_CSS' => $this->get_css_files_html_code(),
			'L_XML_LANGUAGE' => $LANG['xml_lang'],
			'L_VISIT' => $LANG['guest_s'],
			'L_TODAY' => $LANG['today'],
		));

		//Inclusion des blocs
		import('core/menu_service');
		if (!DEBUG)
		{
			$result = @include_once(PATH_TO_ROOT . '/cache/menus.php');
		}
		else
		{
			$result = include_once(PATH_TO_ROOT . '/cache/menus.php');
		}
		if (!$result)
		{
			//En cas d'échec, on régénère le cache
			$Cache->Generate_file('menus');

			//On inclut une nouvelle fois
			if (!@include_once(PATH_TO_ROOT . '/cache/menus.php'))
			{
				$Errorh->handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
			}
		}

		$template->assign_vars(array(
			'C_MENUS_HEADER_CONTENT' => !empty($MENUS[BLOCK_POSITION__HEADER]),
		    'MENUS_HEADER_CONTENT' => $MENUS[BLOCK_POSITION__HEADER],
			'C_MENUS_SUB_HEADER_CONTENT' => !empty($MENUS[BLOCK_POSITION__SUB_HEADER]),
			'MENUS_SUB_HEADER_CONTENT' => $MENUS[BLOCK_POSITION__SUB_HEADER]
		));

		//Si le compteur de visites est activé, on affiche le tout.
		if ($CONFIG['compteur'] == 1)
		{
			$compteur = EnvironmentServices::get_sql()->query_array(DB_TABLE_VISIT_COUNTER, 'ip AS nbr_ip', 'total', 'WHERE id = "1"', __LINE__, __FILE__);
			$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
			$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';

			$template->assign_vars(array(
				'C_COMPTEUR' => true,
				'COMPTEUR_TOTAL' => $compteur_total,
				'COMPTEUR_DAY' => $compteur_day
			));
		}

		//Gestion de l'affichage des modules.
		if (!defined('NO_LEFT_COLUMN'))
		{
			define('NO_LEFT_COLUMN', false);
		}
		if (!defined('NO_RIGHT_COLUMN'))
		{
			define('NO_RIGHT_COLUMN', false);
		}

		$left_column  = ($THEME_CONFIG[get_utheme()]['left_column'] && !NO_LEFT_COLUMN);
		$right_column = ($THEME_CONFIG[get_utheme()]['right_column'] && !NO_RIGHT_COLUMN);

		//Début de la colonne de gauche.
		if ($left_column) //Gestion des blocs de gauche.
		{
			// Affichage des modules droits à gauche sur les thèmes à une colonne (gauche).
			$left_column_content = $MENUS[BLOCK_POSITION__LEFT] . (!$right_column ? $MENUS[BLOCK_POSITION__RIGHT] : '');
			$template->assign_vars(array(
				'C_MENUS_LEFT_CONTENT' => !empty($left_column_content),
				'MENUS_LEFT_CONTENT' => $left_column_content
			));
		}
		if ($right_column)  //Gestion des blocs de droite.
		{
			// Affichage des modules gauches à droite sur les thèmes à une colonne (droite).
			$right_column_content = $MENUS[BLOCK_POSITION__RIGHT] . (!$left_column ? $MENUS[BLOCK_POSITION__LEFT] : '');
			$Template->assign_vars(array(
				'C_MENUS_RIGHT_CONTENT' => !empty($right_column_content),
				'MENUS_RIGHT_CONTENT' => $right_column_content
			));
		}

		//Gestion du fil d'ariane, et des titres des pages dynamiques.
		EnvironmentServices::get_breadcrumb()->display();

		$template->assign_vars(array(
			'C_MENUS_TOPCENTRAL_CONTENT' => !empty($MENUS[BLOCK_POSITION__TOP_CENTRAL]),
			'MENUS_TOPCENTRAL_CONTENT' => $MENUS[BLOCK_POSITION__TOP_CENTRAL]
		));

		$template->parse();
	}

	protected function process_site_maintenance(Template $template)
	{
		global $CONFIG, $Template;

		//Users not authorized cannot come here
		parent::process_site_maintenance();

		if ($this->is_under_maintenance() && $CONFIG['maintain_display_admin'])
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
			if ($CONFIG['maintain'] != -1)
			{
				$key_delay = 0;
				$current_time = time();
				$array_size = count($array_time) - 1;
				for ($i = $array_size; $i >= 1; $i--)
				{
					if (($CONFIG['maintain'] - $current_time) - $array_time[$i] < 0
					&&  ($CONFIG['maintain'] - $current_time) - $array_time[$i-1] > 0)
					{
						$key_delay = $i-1;
						break;
					}
				}

				//Calcul du format de la date
				$seconds = gmdate_format('s', $CONFIG['maintain'], TIMEZONE_SITE);
				$array_release = array(
				gmdate_format('Y', $CONFIG['maintain'], TIMEZONE_SITE),
				(gmdate_format('n', $CONFIG['maintain'], TIMEZONE_SITE) - 1),
				gmdate_format('j', $CONFIG['maintain'], TIMEZONE_SITE),
				gmdate_format('G', $CONFIG['maintain'], TIMEZONE_SITE),
				gmdate_format('i', $CONFIG['maintain'], TIMEZONE_SITE),
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
				'UNSPECIFIED' => $CONFIG['maintain'] != -1 ? 1 : 0,
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

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/core/environment/GraphicalEnvironment#display_footer()
	 */
	function display_footer()
	{
		global $CONFIG, $MENUS, $LANG;
		$template = new Template('footer.tpl');

		$template->assign_vars(array(
			'THEME' => get_utheme(),
			'C_MENUS_BOTTOM_CENTRAL_CONTENT' => !empty($MENUS[BLOCK_POSITION__BOTTOM_CENTRAL]),
			'MENUS_BOTTOMCENTRAL_CONTENT' => $MENUS[BLOCK_POSITION__BOTTOM_CENTRAL],
			'C_MENUS_TOP_FOOTER_CONTENT' => !empty($MENUS[BLOCK_POSITION__TOP_FOOTER]),
			'MENUS_TOP_FOOTER_CONTENT' => $MENUS[BLOCK_POSITION__TOP_FOOTER],
			'C_MENUS_FOOTER_CONTENT' => !empty($MENUS[BLOCK_POSITION__FOOTER]),
			'MENUS_FOOTER_CONTENT' => $MENUS[BLOCK_POSITION__FOOTER],
			'C_DISPLAY_AUTHOR_THEME' => ($CONFIG['theme_author'] ? true : false),
			'L_POWERED_BY' => $LANG['powered_by'],
			'L_PHPBOOST_RIGHT' => $LANG['phpboost_right'],
			'L_THEME' => $LANG['theme'],
			'L_THEME_NAME' => $this->theme_properties['name'],
			'L_BY' => strtolower($LANG['by']),
			'L_THEME_AUTHOR' => $this->theme_properties['author'],
			'U_THEME_AUTHOR_LINK' => $this->theme_properties['author_link'],
		    'PHPBOOST_VERSION' => $CONFIG['version']
		));

		//We add a page to the page displayed counter
		pages_displayed();

		if ($CONFIG['bench'])
		{
			$template->assign_vars(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH' => EnvironmentServices::get_bench()->to_string(),
				'REQ' => EnvironmentServices::get_sql_querier()->get_executed_requests_count() + 
			EnvironmentServices::get_sql()->get_executed_requests_number(),
				'L_REQ' => $LANG['sql_req'],
				'L_ACHIEVED' => $LANG['achieved'],
				'L_UNIT_SECOND' => $LANG['unit_seconds_short']
			));
		}

		$template->parse();
	}
}

?>