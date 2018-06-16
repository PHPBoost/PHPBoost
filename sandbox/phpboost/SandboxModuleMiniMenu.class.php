<?php
/*##################################################
 *                               SandboxModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : September 28, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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
		return LangLoader::get_message('mini.module.title', 'common', 'sandbox');
	}

	public function is_displayed()
	{
		return true;
	}

	public function get_menu_content()
	{
		$tpl = new FileTemplate('sandbox/SandboxModuleMiniMenu.tpl');
		$tpl->add_lang(LangLoader::get('common', 'sandbox'));
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

        $this->build_disable_right_menu();
        if ($this->disable_right_button->has_been_submited() && $this->disable_right_menu->validate())
            $this->disable_right_menu();

        $this->build_enable_right_menu();
        if ($this->enable_right_button->has_been_submited() && $this->enable_right_menu->validate())
            $this->enable_right_menu();

        $this->build_disable_left_menu();
        if ($this->disable_left_button->has_been_submited() && $this->disable_left_menu->validate())
            $this->disable_left_menu();

        $this->build_enable_left_menu();
        if ($this->enable_left_button->has_been_submited() && $this->enable_left_menu->validate())
            $this->enable_left_menu();

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
			'C_LEFT_ENABLED' => !$menus_status->left_columns_is_disabled(),
			'C_RIGHT_ENABLED' => !$menus_status->right_columns_is_disabled(),
            'C_SLIDE_RIGHT' => $config->get_open_menu() == SandboxConfig::RIGHT_MENU,
            'C_IS_LOCALHOST' => AppContext::get_request()->get_is_localhost(),
			'C_IS_SUPERADMIN' => $is_superadmin,
            'C_LOGGED_ERRORS' => ((bool)count($this->get_logged_errors_nb())),
            'C_404_ERRORS' => (bool)$nb_404,

            'PBT_VERSION' => Environment::get_phpboost_version(),
            'PHP_VERSION' => ServerConfiguration::get_phpversion(),
            'DBMS_VERSION' => PersistenceContext::get_dbms_utils()->get_dbms_version(),
            'INSTALL_DATE' => GeneralConfig::load()->get_site_install_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
            'COMMENTS_NB' => count(CommentsCache::load()->get_comments()),
            'ERRORS_NB' => count($this->get_logged_errors_nb()),
            '404_NB' => $nb_404,
			'ENABLE_CSS_CACHE' => $this->enable_css_cache->display(),
            'DISABLE_CSS_CACHE' => $this->disable_css_cache->display(),
			'CLEAN_CSS_CACHE' => $this->clean_css_cache->display(),
			'CLEAN_TPL_CACHE' => $this->clean_cache->display(),
			'CLEAN_SYNDICATION_CACHE' => $this->clean_syndication_cache->display(),
            'ENABLE_LEFT_COL' => $this->enable_left_menu->display(),
            'DISABLE_LEFT_COL' => $this->disable_left_menu->display(),
            'ENABLE_RIGHT_COL' => $this->enable_right_menu->display(),
            'DISABLE_RIGHT_COL' => $this->disable_right_menu->display(),
            'DEFAULT_THEME'=> UserAccountsConfig::load()->get_default_theme()
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
		$enable_css_cache->set_html_id('enable_css_cache');

		$this->enable_css_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fab fa-css3 icon-main"></i>
                <i class="fa fa-plus icon-sup"></i>
            </span>
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
		$disable_css_cache->set_html_id('disable_css_cache');

		$this->disable_css_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fab fa-css3 icon-main"></i>
                <i class="fa fa-minus icon-sup"></i>
            </span>
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
		$clean_css_cache->set_html_id('clean_css_cache');

		$this->clean_css_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fab fa-css3 icon-main"></i>
                <i class="fa fa-refresh icon-sup"></i>
            </span>
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
		$clean_cache->set_html_id('clean_cache');

		$this->clean_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fab fa-html5 icon-main"></i>
                <i class="fa fa-refresh icon-sup"></i>
            </span>
        ', 'clean_cache');
		$clean_cache->add_button($this->clean_button);

		$this->clean_cache = $clean_cache;
	}

    protected function clean_cache()
    {
		AppContext::get_cache_service()->clear_cache();
		HtaccessFileCache::regenerate();
    }

	private function build_clean_syndication_cache()
	{
		$clean_syndication_cache = new HTMLForm('clean_syndication_cache', '', false);
		$clean_syndication_cache->set_html_id('clean_syndication_cache');

		$this->clean_syndication_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fa fa-rss icon-main"></i>
                <i class="fa fa-refresh icon-sup"></i>
            </span>
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
		$disable_left_menu->set_html_id('disable_left_menu');

		$this->disable_left_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fa fa-th-list icon-main"></i>
                <i class="fa fa-minus icon-sup"></i>
            </span>
        ', 'disable_left_menu');
		$disable_left_menu->add_button($this->disable_left_button);

		$this->disable_left_menu = $disable_left_menu;
	}

	protected function disable_left_menu()
	{
        $menus_config = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
        $menus_config->set_disable_left_columns(true);
        MenuService::generate_cache();
	}

	private function build_enable_left_menu()
	{
		$enable_left_menu = new HTMLForm('enable_left_menu', '', false);
		$enable_left_menu->set_html_id('enable_left_menu');

		$this->enable_left_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fa fa-th-list icon-main"></i>
                <i class="fa fa-plus icon-sup"></i>
            </span>
        ', 'enable_left_menu');
		$enable_left_menu->add_button($this->enable_left_button);

		$this->enable_left_menu = $enable_left_menu;
	}

	protected function enable_left_menu()
	{
        $menus_config = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
        $menus_config->set_disable_left_columns(false);
        MenuService::generate_cache();
	}

	private function build_disable_right_menu()
	{
		$disable_right_menu = new HTMLForm('disable_right_menu', '', false);
		$disable_right_menu->set_html_id('disable_right_menu');

		$this->disable_right_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fa fa-th-list fa-rotate-180 icon-main"></i>
                <i class="fa fa-minus icon-sup"></i>
            </span>
        ', 'disable_right_menu');
		$disable_right_menu->add_button($this->disable_right_button);

		$this->disable_right_menu = $disable_right_menu;
	}

	protected function disable_right_menu()
	{
        $theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());
        $menus_config = $theme->get_columns_disabled();
        $menus_config->set_disable_right_columns(true);

	    MenuService::generate_cache();
	}

	private function build_enable_right_menu()
	{
		$enable_right_menu = new HTMLForm('enable_right_menu', '', false);
		$enable_right_menu->set_html_id('enable_right_menu');

		$this->enable_right_button = new FormButtonSubmit('
            <span class="icon-stack">
                <i class="fa fa-th-list fa-rotate-180 icon-main"></i>
                <i class="fa fa-plus icon-sup"></i>
            </span>
        ', 'enable_right_menu');
		$enable_right_menu->add_button($this->enable_right_button);

		$this->enable_right_menu = $enable_right_menu;
	}

	protected function enable_right_menu()
	{
        $menus_config = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
        $menus_config->set_disable_right_columns(false);
        MenuService::generate_cache();
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
