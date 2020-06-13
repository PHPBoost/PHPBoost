<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 04 18
 * @since       PHPBoost 5.2 - 2020 03 09
*/

class HomeLandingModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('HomeLanding');

		$this->delete_old_files_list = array(
			'/templates/pagecontent/onepage.tpl',
			'/templates/pagecontent/articles.tpl',
			'/templates/pagecontent/articles-cat.tpl',
			'/templates/pagecontent/download.tpl',
			'/templates/pagecontent/download-cat.tpl',
			'/templates/pagecontent/events.tpl',
			'/templates/pagecontent/forum.tpl',
			'/templates/pagecontent/guestbook.tpl',
			'/templates/pagecontent/lastcoms.tpl',
			'/templates/pagecontent/news.tpl',
			'/templates/pagecontent/news-cat.tpl',
			'/templates/pagecontent/web.tpl',
			'/templates/pagecontent/web-cat.tpl',
		);

		// HomeLandingConfig::init_modules_array();
	}
}
?>
