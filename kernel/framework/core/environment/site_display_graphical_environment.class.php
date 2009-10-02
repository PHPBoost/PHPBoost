<?php
/*##################################################
 *                       graphical_environment.class.php
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

/**
 * @package core
 * @subpackage environment
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SiteDisplayGraphicalEnvironment implements GraphicalEnvironment
{
	private $user;
	
	public function __construct()
	{
$this->user = EnvironmentServices::get_user();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/core/environment/GraphicalEnvironment#display_header()
	 */
	function display_header()
	{
		global $CONFIG, $LANG, $Errorh, $Template, $Cache, $THEME_CONFIG, $CSS;

		$Template->set_filenames(array(
		'header' => 'header.tpl'
		));

		$this->process_site_maintenance();


		//Ajout des éventuels css alternatifs du module.
		$alternative_css = '';
		if (defined('ALTERNATIVE_CSS'))
		{
			$alternative = null;
			$styles = @unserialize(ALTERNATIVE_CSS);
			if (is_array($styles))
			{
				foreach ($styles as $module => $style) {
					$base 	= '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
					$file = $base . $style . '.css';
					if (file_exists(PATH_TO_ROOT . $file))
					{
						$alternative = TPL_PATH_TO_ROOT . $file;
					}
					else
					{
						$alternative = TPL_PATH_TO_ROOT . '/' . $module . '/templates/' . $style . '.css';
					}
					$alternative_css .= '<link rel="stylesheet" href="' . $alternative . '" type="text/css" media="screen, handheld" />' . "\n";
				}
			}
			else
			{
				$array_alternative_css = explode(',', str_replace(' ', '', ALTERNATIVE_CSS));
				$module = $array_alternative_css[0];
				$base = '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
				foreach ($array_alternative_css as $alternative)
				{
					$file = $base . $alternative . '.css';
					if (file_exists(PATH_TO_ROOT . $file))
					{
						$alternative = TPL_PATH_TO_ROOT . $file;
					}
					else
					{
						$alternative = TPL_PATH_TO_ROOT . '/' . $module . '/templates/' . $alternative . '.css';
					}
					$alternative_css .= '<link rel="stylesheet" href="' . $alternative . '" type="text/css" media="screen, handheld" />' . "\n";
				}
			}
		}

		//On ajoute les css associés aux mini-modules.
		$Cache->load('css');
		if (isset($CSS[get_utheme()]))
		{
			foreach ($CSS[get_utheme()] as $css_mini_module)
			{
				$alternative_css .= "\t\t" . '<link rel="stylesheet" href="' . TPL_PATH_TO_ROOT . $css_mini_module . '" type="text/css" media="screen, handheld" />' . "\n";
			}
		}

		//On récupère la configuration du thème actuel, afin de savoir si il faut placer les séparateurs de colonnes (variable sur chaque thème).
		$THEME = load_ini_file(PATH_TO_ROOT . '/templates/' . get_utheme() . '/config/', get_ulang());

		$member_connected = $this->user->check_level(MEMBER_LEVEL);
		$Template->assign_vars(array(
			'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
			'SID' => SID,
			'SERVER_NAME' => $CONFIG['site_name'],
			'SITE_NAME' => $CONFIG['site_name'],
			'TITLE' => stripslashes(TITLE),
			'SITE_DESCRIPTION' => $CONFIG['site_desc'],
			'SITE_KEYWORD' => $CONFIG['site_keyword'],
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'ALTERNATIVE_CSS' => $alternative_css,
			'C_ADMIN_AUTH' => $this->user->check_level(ADMIN_LEVEL),
			'C_MODERATOR_AUTH' => $this->user->check_level(MODERATOR_LEVEL),
			'C_USER_CONNECTED' => $member_connected,
			'C_USER_NOTCONNECTED' => !$member_connected,
			'C_BBCODE_TINYMCE_MODE' => $this->user->get_attribute('user_editor') == 'tinymce',
			'L_XML_LANGUAGE' => $LANG['xml_lang'],
			'L_VISIT' => $LANG['guest_s'],
			'L_TODAY' => $LANG['today'],
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_PASSWORD' => $LANG['require_password']
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

		$Template->assign_vars(array(
			'C_MENUS_HEADER_CONTENT' => !empty($MENUS[BLOCK_POSITION__HEADER]),
		    'MENUS_HEADER_CONTENT' => $MENUS[BLOCK_POSITION__HEADER],
			'C_MENUS_SUB_HEADER_CONTENT' => !empty($MENUS[BLOCK_POSITION__SUB_HEADER]),
			'MENUS_SUB_HEADER_CONTENT' => $MENUS[BLOCK_POSITION__SUB_HEADER]
		));

		//Si le compteur de visites est activé, on affiche le tout.
		if ($CONFIG['compteur'] == 1)
		{
			$compteur 		= $Sql->query_array(DB_TABLE_VISIT_COUNTER, 'ip AS nbr_ip', 'total', 'WHERE id = "1"', __LINE__, __FILE__);
			$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
			$compteur_day 	= !empty($compteur['total']) ? $compteur['total'] : '1';

			$Template->assign_vars(array(
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
			$Template->assign_vars(array(
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

		$Template->assign_vars(array(
			'C_MENUS_TOPCENTRAL_CONTENT' => !empty($MENUS[BLOCK_POSITION__TOP_CENTRAL]),
			'MENUS_TOPCENTRAL_CONTENT' => $MENUS[BLOCK_POSITION__TOP_CENTRAL]
		));

		$Template->pparse('header');
	}

	public function process_site_maintenance()
	{
		global $CONFIG, $Template;
		
		//Gestion de la maintenance du site.
		if ($CONFIG['maintain'] == -1 || $CONFIG['maintain'] > time())
		{
			if (!$this->user->check_level(ADMIN_LEVEL) && !$this->user->check_auth($CONFIG['maintain_auth'], AUTH_MAINTAIN)) //Non admin et utilisateurs autorisés.
			{
				if (SCRIPT !== (DIR . '/member/maintain.php')) //Evite de créer une boucle infine.
				{
					redirect('/member/maintain.php');
				}
			}
			elseif ($CONFIG['maintain_display_admin']) //Affichage du message d'alerte à l'administrateur.
			{
				//Durée de la maintenance.
				$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000, 21600, 25200, 28800, 57600, 86400, 172800, 604800);
				$array_delay = array($LANG['unspecified'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '10 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '3 ' . $LANG['hours'], '4 ' . $LANG['hours'], '5 ' . $LANG['hours'], '6 ' . $LANG['hours'], '7 ' . $LANG['hours'], '8 ' . $LANG['hours'], '16 ' . $LANG['hours'], '1 ' . $LANG['day'], '2 ' . $LANG['days'], '1 ' . $LANG['week']);
				//Retourne le délai de maintenance le plus proche.
				if ($CONFIG['maintain'] != -1)
				{
					$key_delay = 0;
					$current_time = time();
					$array_size = count($array_time) - 1;
					for ($i = $array_size; $i >= 1; $i--)
					{
						if (($CONFIG['maintain'] - $current_time) - $array_time[$i] < 0 &&  ($CONFIG['maintain'] - $current_time) - $array_time[$i-1] > 0)
						{
							$key_delay = $i-1;
							break;
						}
					}

					//Calcul du format de la date
					$seconds = gmdate_format('s', $CONFIG['maintain'], TIMEZONE_SITE);
					$array_release = array(
					gmdate_format('Y', $CONFIG['maintain'], TIMEZONE_SITE), (gmdate_format('n', $CONFIG['maintain'], TIMEZONE_SITE) - 1), gmdate_format('j', $CONFIG['maintain'], TIMEZONE_SITE),
					gmdate_format('G', $CONFIG['maintain'], TIMEZONE_SITE), gmdate_format('i', $CONFIG['maintain'], TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);

					$seconds = gmdate_format('s', time(), TIMEZONE_SITE);
					$array_now = array(
					gmdate_format('Y', time(), TIMEZONE_SITE), (gmdate_format('n', time(), TIMEZONE_SITE) - 1), gmdate_format('j', time(), TIMEZONE_SITE),
					gmdate_format('G', time(), TIMEZONE_SITE), gmdate_format('i', time(), TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
				}
				else //Délai indéterminé.
				{
					$key_delay = 0;
					$array_release = array('0', '0', '0', '0', '0', '0');
					$array_now = array('0', '0', '0', '0', '0', '0');
				}

				$Template->assign_vars(array(
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
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/core/environment/GraphicalEnvironment#display_footer()
	 */
	function display_footer()
	{

	}
}

?>