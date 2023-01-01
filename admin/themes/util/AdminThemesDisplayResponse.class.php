<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 3.0 - 2011 04 20
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminThemesDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['addon.themes.management']);

		$this->add_link($lang['addon.themes.installed'], AdminThemeUrlBuilder::list_installed_theme());
		$this->add_link($lang['addon.themes.add'], AdminThemeUrlBuilder::add_theme());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
