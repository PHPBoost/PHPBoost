<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 04 20
*/

class AdminThemesDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-themes-common');
		$this->set_title($lang['themes.theme_management']);

		$this->add_link($lang['themes.installed_theme'], AdminThemeUrlBuilder::list_installed_theme());
		$this->add_link($lang['themes.add_theme'], AdminThemeUrlBuilder::add_theme());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
