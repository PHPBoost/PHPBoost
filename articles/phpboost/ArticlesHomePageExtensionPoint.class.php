<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 08
 * @since       PHPBoost 4.0 - 2013 03 19
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), ArticlesDisplayCategoryController::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('articles.module.title', 'common', 'articles');
	}
}
?>
