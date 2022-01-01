<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 14
 * @since       PHPBoost 5.2 - 2020 03 09
*/

class HomeLandingModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('HomeLanding');

		self::$delete_old_files_list = array(
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/services/modules/HomeLandingRss.class.php',
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
			'/templates/pagecontent/rssreader.tpl',
			'/templates/pagecontent/web.tpl',
			'/templates/pagecontent/web-cat.tpl',
		);

		self::$delete_old_folders_list = array(
			'/templates/rsscache'
		);
	}
}
?>
