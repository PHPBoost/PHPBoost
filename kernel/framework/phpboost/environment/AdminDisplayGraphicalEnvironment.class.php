<?php
/*##################################################
 *                  admin_display_graphical_environment.class.php
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
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class AdminDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	private $theme_properties;

	public function __construct()
	{
		parent::__construct();

		global $LANG;

		require_once PATH_TO_ROOT . '/lang/' . get_ulang() . '/admin.php';

		$this->check_admin_auth();
	}

	private function check_admin_auth()
	{
		global $LANG;

		//Module de connexion
		$login = retrieve(POST, 'login', '');
		$password = retrieve(POST, 'password', '', TSTRING_UNCHANGE);
		$autoconnexion = retrieve(POST, 'auto', false);
		$unlock = strhash(retrieve(POST, 'unlock', '', TSTRING_UNCHANGE));

		if (retrieve(GET, 'disconnect', false))
		{
			AppContext::get_session()->end();
			AppContext::get_response()->redirect(Environment::get_home_page());
		}

		$sql = PersistenceContext::get_sql();

		//If the member tried to connect
		if (retrieve(POST, 'connect', false) && !empty($login) && !empty($password))
		{
			//TODO @Régis clean this code. Why it's not in the session class?
			$user_id = $sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "' AND level = 2", __LINE__, __FILE__);
			if (!empty($user_id)) //Membre existant.
			{
				$info_connect = $sql->query_array(DB_TABLE_MEMBER, 'level', 'user_warning', 'last_connect', 'test_connect', 'user_ban', 'user_aprob', "WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__);
				$delay_connect = (time() - $info_connect['last_connect']); //Délai entre deux essais de connexion.
				$delay_ban = (time() - $info_connect['user_ban']); //Vérification si le membre est banni.

				if ($delay_ban >= 0 && $info_connect['user_aprob'] == '1' && $info_connect['user_warning'] < '100') //Utilisateur non (plus) banni.
				{
					$session = AppContext::get_session();
					
					//Protection de l'administration par connexion brute force.
					if ($info_connect['test_connect'] < '5' || $unlock === GeneralConfig::load()->get_admin_unlocking_key()) //Si clée de déverouillage bonne aucune vérification.
					{
						$error_report = $session->start($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); //On lance la session.
					}
					elseif ($delay_connect >= 600 && $info_connect['test_connect'] == '5') //5 nouveau essais, 10 minutes après.
					{
						$sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "', test_connect = 0 WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
						$error_report = $session->start($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); //On lance la session.
					}
					elseif ($delay_connect >= 300 && $info_connect['test_connect'] == '5') //2 essais 5 minutes après
					{
						$sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "', test_connect = 3 WHERE user_id = '" . $user_id . "' AND level = 2", __LINE__, __FILE__); //Redonne un essai.
						$error_report = $session->start($user_id, $password, $info_connect['level'], '', '', '', $autoconnexion); //On lance la session.
					}
					else //plus d'essais
					{
						AppContext::get_response()->redirect('/admin/admin_index.php?flood=0');
					}
				}
				elseif ($info_connect['user_aprob'] == '0')
				{
					AppContext::get_response()->redirect('/member/error.php?activ=1');
				}
				elseif ($info_connect['user_warning'] == '100')
				{
					AppContext::get_response()->redirect('/member/error.php?ban_w=1');
				}
				else
				{
					$delay_ban = ceil((0 - $delay_ban)/60);
					AppContext::get_response()->redirect('/member/error.php?ban=' . $delay_ban);
				}

				if (!empty($error_report)) //Erreur
				{
					$info_connect['test_connect']++;
					$sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect = '" . time() . "', test_connect = test_connect + 1 WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
					$info_connect['test_connect'] = 5 - $info_connect['test_connect'];
					AppContext::get_response()->redirect('/admin/admin_index.php?flood=' . $info_connect['test_connect']);
				}
				elseif (!empty($unlock) && $unlock !== GeneralConfig::load()->get_admin_unlocking_key())
				{
					AppContext::get_session()->end();
					AppContext::get_response()->redirect('/admin/admin_index.php?flood=0');
				}
				else //Succès redonne tous les essais.
				{
					$sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "', test_connect = 0 WHERE user_id='" . $user_id . "'", __LINE__, __FILE__); //Remise à zéro du compteur d'essais.
				}
			}
			else
			AppContext::get_response()->redirect('/member/error.php?unexist=1');

			AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
		}

		$flood = retrieve(GET, 'flood', 0);
		$is_admin = AppContext::get_user()->check_level(ADMIN_LEVEL);
		if (!$is_admin || $flood)
		{
			$template = new FileTemplate('admin/AdminLoginController.tpl');

			$template->put_all(array(
				'L_XML_LANGUAGE' => $LANG['xml_lang'],
				'SITE_NAME' => GeneralConfig::load()->get_site_name(),
				'TITLE' => TITLE,
				'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
				'L_REQUIRE_PASSWORD' => $LANG['require_password'],
				'L_CONNECT' => $LANG['connect'],
				'L_ADMIN' => $LANG['admin'],
				'L_PSEUDO' => $LANG['pseudo'],
				'L_PASSWORD' => $LANG['password'],
				'L_AUTOCONNECT'	=> $LANG['autoconnect']
			));

			if ($flood)
			{
				$template->put_all(array(
					'TITLE' => TITLE,
					'ERROR' => (($flood > '0') ? sprintf($LANG['flood_block'], $flood) : $LANG['flood_max']),
					'L_UNLOCK' => $LANG['unlock_admin_panel'],
					'C_UNLOCK' => true
				));
			}

			$template->display();
			Environment::destroy();
			exit;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function display_header()
	{
		global $LANG;

		self::set_page_localization($this->get_page_title());

		$header_tpl = new FileTemplate('admin/admin_header.tpl');

		$include_tinymce_js = AppContext::get_user()->get_attribute('user_editor') == 'tinymce';

		$header_tpl->put_all(array(
			'L_XML_LANGUAGE' => $LANG['xml_lang'],
			'SITE_NAME' => GeneralConfig::load()->get_site_name(),
			'TITLE' => $this->get_page_title(),
			'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
			'THEME_CSS' => $this->get_theme_css_files_html_code(),
			'MODULES_CSS' => $this->get_modules_css_files_html_code(),
			'C_BBCODE_TINYMCE_MODE' => $include_tinymce_js,
			'L_EXTEND_MENU' => $LANG['extend_menu'],
		));

		$header_tpl->put('subheader_menu', self::get_subheader_tpl());

		$header_tpl->display();
	}

	private static function get_subheader_tpl()
	{
		global $LANG;

		$subheader_tpl = new FileTemplate('admin/subheader_menu.tpl');

		$subheader_tpl->put_all(array(
			'L_ADMINISTRATION' => $LANG['administration'],
			'L_INDEX' => $LANG['index'],
			'L_SITE' => $LANG['site'],
			'L_INDEX_SITE' => $LANG['site'],
			'L_INDEX_ADMIN' => $LANG['administration'],
			'L_DISCONNECT' => $LANG['disconnect'],
			'L_TOOLS' => $LANG['tools'],
			'L_CONFIGURATION' => $LANG['configuration'],
			'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
			'L_MAIL_CONFIG' => $LANG['config_mail'],
		    'L_ADD' => $LANG['add'],
		    'L_ADD_CONTENT_MENU' => $LANG['menus_content_add'],
		    'L_ADD_LINKS_MENU' => $LANG['menus_links_add'],
		    'L_ADD_FEED_MENU' => $LANG['menus_feed_add'],
			'L_MANAGEMENT' => $LANG['management'],
			'L_PUNISHEMENT' => $LANG['punishement'],
			'L_UPDATE_MODULES' => $LANG['update_module'],
			'L_SITE_LINK' => $LANG['link_management'],
			'L_SITE_MENU' => $LANG['menu_management'],
			'L_MODERATION' => $LANG['moderation'],
			'L_MAINTAIN' => $LANG['maintain'],
			'L_USER' => $LANG['member_s'],
			'L_EXTEND_FIELD' => $LANG['extend_field'],
			'L_RANKS' => $LANG['ranks'],
			'L_TERMS' => $LANG['terms'],
			'L_GROUP' => $LANG['group'],
			'L_CONTENTS' => $LANG['content'],
			'L_PAGES' => $LANG['pages'],
			'L_FILES' => $LANG['files'],
			'L_THEME' => $LANG['themes'],
			'L_LANG' => $LANG['languages'],
			'L_SMILEY' => $LANG['smile'],
			'L_ADMINISTRATOR_ALERTS' => $LANG['administrator_alerts'],
			'L_STATS' => $LANG['stats'],
			'L_ERRORS' => $LANG['errors'],
			'L_SERVER' => $LANG['server'],
			'L_PHPINFO' => $LANG['phpinfo'],
			'L_SYSTEM_REPORT' => $LANG['system_report'],
			'L_COMMENTS' => $LANG['comments'],
			'L_UPDATER' => $LANG['updater'],
			'L_KERNEL' => $LANG['kernel'],
			'L_MODULES' => $LANG['modules'],
			'L_THEMES' => $LANG['themes'],
			'L_CACHE' => $LANG['cache'],
			'L_SYNDICATION' => $LANG['syndication'],
			'L_CACHE_CONFIG' => LangLoader::get_message('cache_configuration', 'admin-cache-common'),
			'L_CONTENT_CONFIG' => $LANG['content_config'],
			'U_INDEX_SITE' => Environment::get_home_page(),
		    'L_WEBSITE_UPDATES' => $LANG['website_updates'],
			'C_ADMIN_LINKS_1' => false,
			'C_ADMIN_LINKS_2' => false,
			'C_ADMIN_LINKS_3' => false,
			'C_ADMIN_LINKS_4' => false,
			'C_ADMIN_LINKS_5' => false,
			'C_ADMIN_LINKS_1' => false
		));

		$modules = ModulesManager::get_activated_modules_map_sorted_by_localized_name();
		$array_pos = array(0, 4, 3, 3, 3, 1);
		$menus_numbers = array('index' => 1, 'administration' => 2, 'tools' => 3, 'members' => 4,
			 'content' => 5, 'modules' => 6);
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

			//Le module possède une administration
			if ($menu_pos > 0)
			{
				$array_pos[$menu_pos-1]++;
				$idmenu = $array_pos[$menu_pos - 1];
				$subheader_tpl->put('C_ADMIN_LINKS_' . $menu_pos, true);

				$admin_links = $configuration->get_admin_links();
				if (!empty($admin_links))
				{
					$links = '';
					$i = 0;
					$j = 0;
					foreach ($admin_links as $key => $value)
					{
						if (is_array($value))
						{
							$links .= '<li class="extend" onmouseover="show_menu(\'' . $idmenu .
							$i . $module_id . '\', 2);" onmouseout="hide_menu(2);"><a href="#" ' .
									'style="background-image:url(' . TPL_PATH_TO_ROOT . '/' . $module_id .
									'/' . $module_id . '_mini.png);cursor:default;">' . $key .
									'</a><ul id="sssmenu' . $idmenu . $i . $module_id . '">' . "\n";
							foreach ($value as $key2 => $value2)
							{
								$links .= '<li><a href="' . TPL_PATH_TO_ROOT . '/' . $module_id .
									'/' . $value2 . '" style="background-image:url(' .
								TPL_PATH_TO_ROOT . '/' . $module_id . '/' . $module_id . '_mini.png);">'
								. $key2 . '</a></li>' . "\n";
							}
							$links .= '</ul></li>' . "\n";
							$i++;
						}
						else
						{
							$links .= '<li><a href="' . TPL_PATH_TO_ROOT . '/' . $module_id . '/' .
							$value . '" style="background-image:url(' . TPL_PATH_TO_ROOT .
									'/' . $module_id . '/' . $module_id . '_mini.png);">' . $key .
									'</a></li>' . "\n";
						}
						$j++;
					}

					$subheader_tpl->assign_block_vars('admin_links_' . $menu_pos, array(
							'C_ADMIN_LINKS_EXTEND' => ($j > 0 ? true : false),
							'IDMENU' => $idmenu,
							'NAME' => $configuration->get_name(),
							'LINKS' => $links,
							'U_ADMIN_MODULE' => TPL_PATH_TO_ROOT . '/' . $module_id . '/' . $configuration->get_admin_main_page(),
							'IMG' => TPL_PATH_TO_ROOT . '/' . $module_id . '/' . $module_id . '_mini.png'
							));
				}
				else
				{
					$subheader_tpl->assign_block_vars('admin_links_' . $menu_pos, array(
							'C_ADMIN_LINKS_EXTEND' => false,
							'IDMENU' => $menu_pos,
							'NAME' => $configuration->get_name(),
							'U_ADMIN_MODULE' => TPL_PATH_TO_ROOT . '/' . $module_id . '/' . $configuration->get_admin_main_page(),
							'IMG' => TPL_PATH_TO_ROOT . '/' . $module_id . '/' . $module_id . '_mini.png'
							));
				}
			}
		}

		return $subheader_tpl;
	}

	/**
	 * {@inheritdoc}
	 */
	public function display_footer()
	{
		global $LANG;

		$tpl = new FileTemplate('admin/admin_footer.tpl');

		$theme = load_ini_file(PATH_TO_ROOT . '/templates/' . get_utheme() . '/config/', get_ulang());

		$tpl->put_all(array(
			'THEME' => get_utheme(),
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

		if (GraphicalEnvironmentConfig::load()->is_page_bench_enabled())
		{
			$tpl->put_all(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH' => AppContext::get_bench()->to_string(), //Fin du benchmark
				'REQ' => PersistenceContext::get_querier()->get_executed_requests_count() +
			PersistenceContext::get_sql()->get_executed_requests_number(),
				'L_UNIT_SECOND' => HOST,
				'L_REQ' => $LANG['sql_req'],
				'L_ACHIEVED' => $LANG['achieved'],
				'L_UNIT_SECOND' => $LANG['unit_seconds_short']
			));
		}

		$tpl->display();
	}
}

?>