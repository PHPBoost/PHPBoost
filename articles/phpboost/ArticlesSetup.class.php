<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 10
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ArticlesSetup extends DefaultModuleSetup
{
	private function insert_data()
	{
		$lang = LangLoader::get('install', 'articles');
		$this->add_category($lang['default.category.name'], $lang['default.category.description']);
		$this->add_item($lang['default.article.title'], $lang['default.article.content'], $lang['default.article.summary']);
	}
}
?>
