<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 27
*/
#################################################*/

class SitemapModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('sitemap');
		
		self::$delete_old_files_list = array(
			'/phpboost/SitemapHomePageExtensionPoint.class.php'
		);
	}
}
?>
