<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2014 12 22
 * @since   	PHPBoost 4.0 - 2013 03 19
*/

class ArticlesHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), ArticlesDisplayCategoryController::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('articles', 'common', 'articles');
	}
}
?>
