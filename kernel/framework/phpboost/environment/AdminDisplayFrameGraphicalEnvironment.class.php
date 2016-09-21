<?php
/*##################################################
 *                  AdminDisplayFrameGraphicalEnvironment.class.php
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
class AdminDisplayFrameGraphicalEnvironment extends AbstractDisplayGraphicalEnvironment
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function display($content)
	{
		$template = new FileTemplate('admin/frame.tpl');
		
		$customization_config = CustomizationConfig::load();
		$cookiebar_config = CookieBarConfig::load();
		
		$js_top_tpl = new FileTemplate('js_top.tpl');
		$js_top_tpl->put_all(array(
			'C_COOKIEBAR_ENABLED' => $cookiebar_config->is_cookiebar_enabled(),
			'COOKIEBAR_DURATION' => $cookiebar_config->get_cookiebar_duration(),
			'COOKIEBAR_TRACKING_MODE' => $cookiebar_config->get_cookiebar_tracking_mode(),
			'COOKIEBAR_CONTENT' => TextHelper::to_js_string($cookiebar_config->get_cookiebar_content())
		));
		
		$template->put_all(array(
			'C_FAVICON' => $customization_config->favicon_exists(),
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'FAVICON' => Url::to_rel($customization_config->get_favicon_path()),
			'FAVICON_TYPE' => $customization_config->favicon_type(),
			'TITLE' => $this->get_seo_meta_data()->get_full_title(),
			'MODULES_CSS' => $this->get_modules_css_files_html_code(),
			'JS_TOP' => $js_top_tpl,
			'JS_BOTTOM' => new FileTemplate('js_bottom.tpl'),
			'L_XML_LANGUAGE' => LangLoader::get_message('xml_lang', 'main'),
			'BODY' => new StringTemplate($content)
		));
		
		$template->display();
	}
}
?>