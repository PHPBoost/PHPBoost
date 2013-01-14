<?php
/*##################################################
 *                   UserDisplayGraphicalEnvironment.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class UserDisplayGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	private static $main_lang;
	
	public function __construct()
	{
		parent::__construct();
		$this->load_langs();
	}
	
	private function load_langs()
	{
		self::$main_lang = LangLoader::get('main');
	}

	/**
	 * {@inheritdoc}
	 */
	function display_header()
	{
		self::set_page_localization($this->get_page_title());

		$template = new FileTemplate('user/header.tpl');

		$general_config = GeneralConfig::load();
		
		$theme = ThemeManager::get_theme(get_utheme());
		$customize_interface = $theme->get_customize_interface();
		$header_logo_path = $customize_interface->get_header_logo_path();

		$customization_config = CustomizationConfig::load();
	
		$template->put_all(array(
			'SITE_NAME' => $general_config->get_site_name(),
			'C_FAVICON' => $customization_config->favicon_exists(),
			'FAVICON' => TPL_PATH_TO_ROOT . $customization_config->get_favicon_path(),
			'FAVICON_TYPE' => $customization_config->favicon_type(),
			'C_HEADER_LOGO' => !empty($header_logo_path),
			'HEADER_LOGO' => TPL_PATH_TO_ROOT . $header_logo_path,
			'TITLE' => $this->get_page_title(),
			'SITE_DESCRIPTION' => $this->get_seo_meta_data()->get_description(),
			'SITE_KEYWORD' => $this->get_seo_meta_data()->get_keywords(),
			'L_XML_LANGUAGE' => self::$main_lang['xml_lang'],
		));
		
		$template->display();
	}

	/**
	 * {@inheritdoc}
	 */
	function display_footer()
	{
		$template = new FileTemplate('user/footer.tpl');
		
		$theme_configuration = ThemeManager::get_theme(get_utheme())->get_configuration();
		$template->put_all(array(
			'THEME' => get_utheme(),
			'L_POWERED_BY' => self::$main_lang['powered_by'],
			'L_PHPBOOST_RIGHT' => self::$main_lang['phpboost_right'],
			'L_THEME' => self::$main_lang['theme'],
			'L_THEME_NAME' => $theme_configuration->get_name(),
			'L_BY' => strtolower(self::$main_lang['by']),
			'L_THEME_AUTHOR' => $theme_configuration->get_author_name(),
			'U_THEME_AUTHOR_LINK' => $theme_configuration->get_author_link(),
		    'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version()
		));

		if (GraphicalEnvironmentConfig::load()->is_page_bench_enabled())
		{
			$template->put_all(array(
				'C_DISPLAY_BENCH' => true,
				'BENCH' => AppContext::get_bench()->to_string(),
				'REQ' => PersistenceContext::get_querier()->get_executed_requests_count() +
				PersistenceContext::get_sql()->get_executed_requests_number(),
				'L_REQ' => self::$main_lang['sql_req'],
				'L_ACHIEVED' => self::$main_lang['achieved'],
				'L_UNIT_SECOND' => self::$main_lang['unit_seconds_short'],
			));
		}

		$template->display();
	}
}
?>