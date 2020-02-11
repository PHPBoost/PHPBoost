<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 08
 * @since       PHPBoost 5.1 - 2017 09 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SandboxModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__HEADER;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-sandbox';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('sandbox.module.title', 'common', 'sandbox');
	}

	public function is_displayed()
	{
		return true;
	}

	private $mini_lang;

	public function get_menu_content()
	{
		$this->mini_lang = LangLoader::get('module-mini', 'sandbox');
		$tpl = new FileTemplate('sandbox/SandboxModuleMiniMenu.tpl');
		$tpl->add_lang(LangLoader::get('common', 'sandbox'));
		$tpl->add_lang($this->mini_lang);
		$tpl->add_lang(LangLoader::get('module-mini', 'sandbox'));
		$config = SandboxConfig::load();

		MenuService::assign_positions_conditions($tpl, $this->get_block());
		$menus_status = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();

		$user = AppContext::get_current_user();
		if($config->get_superadmin_enabled() == true)
			$is_superadmin = $user->get_display_name() == $config->get_superadmin_name() && User::ADMIN_LEVEL;
		else
			$is_superadmin = $user->get_level() == User::ADMIN_LEVEL;

		$this->build_enable_css_cache();
		if ($this->enable_css_button->has_been_submited() && $this->enable_css_cache->validate())
			$this->enable_css_cache();

		$this->build_disable_css_cache();
		if ($this->disable_css_button->has_been_submited() && $this->disable_css_cache->validate())
			$this->disable_css_cache();

		$this->build_clean_css_cache();
		if ($this->clean_css_button->has_been_submited() && $this->clean_css_cache->validate())
			$this->clean_css_cache();

		$this->build_clean_cache();
		if ($this->clean_button->has_been_submited() && $this->clean_cache->validate())
			$this->clean_cache();

		$this->build_clean_syndication_cache();
		if ($this->clean_syndication_button->has_been_submited() && $this->clean_syndication_cache->validate())
			$this->clean_syndication_cache();

		$this->build_disable_left_menu();
		if ($this->disable_left_button->has_been_submited() && $this->disable_left_menu->validate())
			$this->disable_columns('left', true);

		$this->build_enable_left_menu();
		if ($this->enable_left_button->has_been_submited() && $this->enable_left_menu->validate())
			$this->disable_columns('left', false);

		$this->build_disable_right_menu();
		if ($this->disable_right_button->has_been_submited() && $this->disable_right_menu->validate())
			$this->disable_columns('right', true);

		$this->build_enable_right_menu();
		if ($this->enable_right_button->has_been_submited() && $this->enable_right_menu->validate())
			$this->disable_columns('right', false);

		$result_404 = PersistenceContext::get_querier()->select('SELECT count(*) AS total
		FROM ' . PREFIX . 'errors_404');
		while($row = $result_404->fetch())
		{
			$rows[] = $row;
		}
		foreach($result_404 as $row)
		{
			$nb_404 = $row['total'];
		}

		// themeswitcher
		$theme_id = AppContext::get_request()->get_string('switchtheme', '');
		if (!empty($theme_id))
		{
			$theme = ThemesManager::get_theme($theme_id);
			if ($theme !== null)
			{
				if ($theme->is_activated() && $theme->check_auth())
				{
					$user->update_theme($theme->get_id());
				}
			}
			$query_string = preg_replace('`switchtheme=[^&]+`u', '', QUERY_STRING);
			AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
		}

		MenuService::assign_positions_conditions($tpl, $this->get_block());

		foreach (ThemesManager::get_activated_and_authorized_themes_map_sorted_by_localized_name() as $theme)
		{
			$tpl->assign_block_vars('themes', array(
				'C_SELECTED' => $user->get_theme() == $theme->get_id(),
				'NAME' => $theme->get_configuration()->get_name(),
				'IDNAME' => $theme->get_id()
			));
		}

		$tpl->put_all(array(
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'C_LEFT_ENABLED'      => !$menus_status->left_columns_is_disabled(),
			'C_RIGHT_ENABLED'     => !$menus_status->right_columns_is_disabled(),
			'C_IS_LOCALHOST'      => AppContext::get_request()->get_is_localhost(),
			'C_IS_SUPERADMIN'     => $is_superadmin,
			'C_LOGGED_ERRORS'     => ((bool)count($this->get_logged_errors_nb())),
			'C_404_ERRORS'        => (bool)$nb_404,
			'C_NO_EXPANSION'      => $config->get_expansion_type() == SandboxConfig::NO_EXPANSION,

			'PUSHED_CONTENT' => $config->get_pushed_content() ? '#push-container' : '',
			'DISABLED_BODY'  => $config->get_disabled_body() ? 'true' : 'false',
			'OPENING_TYPE'   => $config->get_menu_opening_type(),
			'EXPANSION_TYPE' => $config->get_expansion_type(),

			'PBT_VERSION'  => Environment::get_phpboost_version(),
			'PHP_VERSION'  => ServerConfiguration::get_phpversion(),
			'DBMS_VERSION' => PersistenceContext::get_dbms_utils()->get_dbms_version(),
			'INSTALL_DATE' => GeneralConfig::load()->get_site_install_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
			'COMMENTS_NB'  => count(CommentsCache::load()->get_comments()),
			'ERRORS_NB'    => count($this->get_logged_errors_nb()),
			'404_NB'       => $nb_404,

			'ENABLE_CSS_CACHE'  => $this->enable_css_cache->display(),
			'DISABLE_CSS_CACHE' => $this->disable_css_cache->display(),
			'CLEAN_CSS_CACHE'   => $this->clean_css_cache->display(),
			'CLEAN_TPL_CACHE'   => $this->clean_cache->display(),
			'CLEAN_RSS_CACHE'   => $this->clean_syndication_cache->display(),
			'ENABLE_LEFT_COL'   => $this->enable_left_menu->display(),
			'DISABLE_LEFT_COL'  => $this->disable_left_menu->display(),
			'ENABLE_RIGHT_COL'  => $this->enable_right_menu->display(),
			'DISABLE_RIGHT_COL' => $this->disable_right_menu->display(),
			'DEFAULT_THEME'     => UserAccountsConfig::load()->get_default_theme()
		));

		return $tpl->render();
	}

	private function get_logged_errors_nb()
	{
		$array_errinfo = array();
		$file_path = PATH_TO_ROOT . '/cache/error.log';

		if (is_file($file_path) && is_readable($file_path)) //Fichier accessible en lecture
		{
			$handle = @fopen($file_path, 'r');
			if ($handle)
			{
				$i = 1;
				while (!feof($handle))
				{
					$buffer = fgets($handle);
					switch ($i)
					{
						case 1:
						$errinfo['errdate'] = $buffer;
						break;
						case 2:
						$errinfo['errno'] = $buffer;
						break;
						case 3:
						$errinfo['errmsg'] = $buffer;
						break;
						case 4:
						$errinfo['errstacktrace'] = $buffer;
						$i = 0;
						$array_errinfo[] = array(
							'errclass' => ErrorHandler::get_errno_class($errinfo['errno']),
							'errmsg' => $errinfo['errmsg'],
							'errstacktrace'=> $errinfo['errstacktrace'],
							'errdate' => $errinfo['errdate']
						);
						break;
					}
					$i++;
				}
				@fclose($handle);
			}
		}

		return array_reverse($array_errinfo); //Tri en sens inverse car enregistrement à la suite dans le fichier de log
	}

	private function build_enable_css_cache()
	{
		$enable_css_cache = new HTMLForm('enable_css_cache', '', false);
		$enable_css_cache->set_css_class('sandbox-mini-form enable-css-cache');

		$this->enable_css_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fab fa-fw fa-css3" aria-hidden="true"></i>
				<i class="fa fa-plus success stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.enable.css.cache'] . ' </span>
		', 'enable_css_cache');
		$enable_css_cache->add_button($this->enable_css_button);

		$this->enable_css_cache = $enable_css_cache;
	}

	protected function enable_css_cache()
	{
		$css_cache_config = CSSCacheConfig::load();
		$css_cache_config->enable();
		CSSCacheConfig::save();
	}

	private function build_disable_css_cache()
	{
		$disable_css_cache = new HTMLForm('disable_css_cache', '', false);
		$disable_css_cache->set_css_class('sandbox-mini-form disable-css-cache');

		$this->disable_css_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fab fa-fw fa-css3" aria-hidden="true"></i>
				<i class="fa fa-minus error stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.disable.css.cache'] . ' </span>
		', 'disable_css_cache');
		$disable_css_cache->add_button($this->disable_css_button);

		$this->disable_css_cache = $disable_css_cache;
	}

	protected function disable_css_cache()
	{
		$css_cache_config = CSSCacheConfig::load();
		$css_cache_config->disable();
		CSSCacheConfig::save();
	}

	private function build_clean_css_cache()
	{
		$clean_css_cache = new HTMLForm('clean_css_cache', '', false);
		$clean_css_cache->set_css_class('sandbox-mini-form clean-css-cache');

		$this->clean_css_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fab fa-fw fa-css3" aria-hidden="true"></i>
				<i class="fa fa-sync notice stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.clean.css.cache'] . ' </span>
		', 'clean_css_cache');
		$clean_css_cache->add_button($this->clean_css_button);

		$this->clean_css_cache = $clean_css_cache;
	}

	protected function clean_css_cache()
	{
		AppContext::get_cache_service()->clear_css_cache();
	}

	private function build_clean_cache()
	{
		$this->css_cache_config = CSSCacheConfig::load();
		$clean_cache = new HTMLForm('clean_cache', '', false);
		$clean_cache->set_css_class('sandbox-mini-form clean-cache');

		$this->clean_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fab fa-fw fa-html5" aria-hidden="true"></i>
				<i class="fa fa-sync notice stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.clean.tpl.cache'] . ' </span>
		', 'clean_cache');
		$clean_cache->add_button($this->clean_button);

		$this->clean_cache = $clean_cache;
	}

	protected function clean_cache()
	{
		AppContext::get_cache_service()->clear_cache();
		HtaccessFileCache::regenerate();
		NginxFileCache::regenerate();
	}

	private function build_clean_syndication_cache()
	{
		$clean_syndication_cache = new HTMLForm('clean_syndication_cache', '', false);
		$clean_syndication_cache->set_css_class('sandbox-mini-form clean-syndication-cache');

		$this->clean_syndication_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fa fa-fw fa-rss" aria-hidden="true"></i>
				<i class="fa fa-sync notice stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.clean.rss.cache'] . ' </span>
		', 'clean_syndication_cache');
		$clean_syndication_cache->add_button($this->clean_syndication_button);

		$this->clean_syndication_cache = $clean_syndication_cache;
	}

	protected function clean_syndication_cache()
	{
		AppContext::get_cache_service()->clear_syndication_cache();
	}

	private function build_disable_left_menu()
	{
		$disable_left_menu = new HTMLForm('disable_left_menu', '', false);
		$disable_left_menu->set_css_class('sandbox-mini-form disable-left-menu');

		$this->disable_left_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fa fa-fw fa-th-list" aria-hidden="true"></i>
				<i class="fa fa-minus error stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.disable.left.col'] . ' </span>
		', 'disable_left_menu');
		$disable_left_menu->add_button($this->disable_left_button);

		$this->disable_left_menu = $disable_left_menu;
	}

	private function build_enable_left_menu()
	{
		$enable_left_menu = new HTMLForm('enable_left_menu', '', false);
		$enable_left_menu->set_css_class('sandbox-mini-form enable-left-menu');

		$this->enable_left_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fa fa-fw fa-th-list" aria-hidden="true"></i>
				<i class="fa fa-plus success stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.enable.left.col'] . ' </span>
		', 'enable_left_menu');
		$enable_left_menu->add_button($this->enable_left_button);

		$this->enable_left_menu = $enable_left_menu;
	}

	private function build_disable_right_menu()
	{
		$disable_right_menu = new HTMLForm('disable_right_menu', '', false);
		$disable_right_menu->set_css_class('sandbox-mini-form disable-right-menu');

		$this->disable_right_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fa fa-fw fa-th-list fa-rotate-180" aria-hidden="true"></i>
				<i class="fa fa-minus error stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.disable.right.col'] . '</span>
		', 'disable_right_menu');
		$disable_right_menu->add_button($this->disable_right_button);

		$this->disable_right_menu = $disable_right_menu;
	}

	private function build_enable_right_menu()
	{
		$enable_right_menu = new HTMLForm('enable_right_menu', '', false);
		$enable_right_menu->set_css_class('sandbox-mini-form enable-right-menu');

		$this->enable_right_button = new FormButtonSubmit('
			<span class="stacked">
				<i class="fa fa-fw fa-th-list fa-rotate-180" aria-hidden="true"></i>
				<i class="fa fa-plus success stack-event stack-sup stack-right" aria-hidden="true"></i>
			</span> <span> ' . $this->mini_lang['mini.enable.right.col'] . '</span>
		', 'enable_right_menu');
		$enable_right_menu->add_button($this->enable_right_button);

		$this->enable_right_menu = $enable_right_menu;
	}

	protected function disable_columns($column, $disable = false)
	{
		$menus_config = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled = new ColumnsDisabled();

		if ($column == 'left')
		{
			$columns_disabled->set_disable_left_columns($disable);
			$columns_disabled->set_disable_right_columns($menus_config->right_columns_is_disabled());
		}
		if ($column == 'right')
		{
			$columns_disabled->set_disable_left_columns($menus_config->left_columns_is_disabled());
			$columns_disabled->set_disable_right_columns($disable);
		}

		ThemesManager::change_columns_disabled(AppContext::get_current_user()->get_theme(), $columns_disabled);
		MenuService::generate_cache();
		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			return $this->get_menu_content();
		}
		return '';
	}
}
?>
