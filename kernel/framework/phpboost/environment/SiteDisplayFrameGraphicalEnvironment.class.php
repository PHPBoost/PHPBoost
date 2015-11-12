<?php
/*##################################################
 *                   SiteDisplayFrameGraphicalEnvironment.class.php
 *                            -------------------
 *   begin                : January 21, 2014
 *   copyright            : (C) 2014 Kevin MASSY
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
class SiteDisplayFrameGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	private $display_css_login = false;
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * {@inheritdoc}
	 */
	public function display($content)
	{
		$template = new FileTemplate('frame.tpl');

		$customization_config = CustomizationConfig::load();
		
		$lang = LangLoader::get('main');
		$description = $this->get_seo_meta_data()->get_full_description();
		$template->put_all(array(
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'C_CSS_LOGIN_DISPLAYED' => $this->display_css_login,
			'C_FAVICON' => $customization_config->favicon_exists(),
			'C_CANONICAL_URL' => $this->get_seo_meta_data()->canonical_link_exists(),
			'C_DESCRIPTION' => !empty($description),
			'FAVICON' => Url::to_rel($customization_config->get_favicon_path()),
			'FAVICON_TYPE' => $customization_config->favicon_type(),
			'TITLE' => $this->get_seo_meta_data()->get_full_title(),
			'SITE_DESCRIPTION' => $description,
			'U_CANONICAL' => $this->get_seo_meta_data()->get_canonical_link(),
			'L_XML_LANGUAGE' => LangLoader::get_message('xml_lang', 'main'),
			'PHPBOOST_VERSION' => GeneralConfig::load()->get_phpboost_major_version(),
			'MODULES_CSS' => $this->get_modules_css_files_html_code(),
			'JS_TOP' => new FileTemplate('js_top.tpl'),
			'JS_BOTTOM' => new FileTemplate('js_bottom.tpl'),
			'BODY' => new StringTemplate($content)
		));
		
		$template->display(true);
	}
	
	public function display_css_login()
	{
		$this->display_css_login = true;
	}
}
?>