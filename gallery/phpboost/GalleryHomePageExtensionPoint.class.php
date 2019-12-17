<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 02 18
 * @since       PHPBoost 3.0 - 2012 02 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class GalleryHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), GalleryDisplayCategoryController::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('module_title', 'common', 'gallery');
	}
}
?>
