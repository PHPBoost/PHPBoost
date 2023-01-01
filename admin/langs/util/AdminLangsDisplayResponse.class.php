<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 06
 * @since       PHPBoost 3.0 - 2012 01 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLangsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['addon.langs.management']);

		$this->add_link($lang['addon.langs.installed'], AdminLangsUrlBuilder::list_installed_langs());
		$this->add_link($lang['addon.langs.add'], AdminLangsUrlBuilder::install());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
