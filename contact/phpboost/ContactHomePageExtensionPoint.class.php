<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2014 12 22
 * @since   	PHPBoost 3.0 - 2012 02 08
*/

class ContactHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), ContactController::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('module_title', 'common', 'contact');
	}
}
?>
