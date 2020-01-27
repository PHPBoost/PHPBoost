<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 27
 * @since       PHPBoost 4.0 - 2013 03 19
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ArticlesExtensionPointProvider extends ModuleExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('articles');
	}

	public function feeds()
	{
		return new ArticlesFeedProvider();
	}

	public function home_page()
	{
		return new DefaultHomePageDisplay($this->get_id(), ArticlesDisplayCategoryController::get_view($this->get_id()));
	}

	public function search()
	{
		return new ArticlesSearchable();
	}
}
?>
