<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 3.0 - 2012 01 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminLangsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-langs-common');
		$this->set_title($lang['langs.langs_management']);

		$this->add_link($lang['langs.installed_langs'], AdminLangsUrlBuilder::list_installed_langs());
		$this->add_link($lang['langs.add_lang'], AdminLangsUrlBuilder::install());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
