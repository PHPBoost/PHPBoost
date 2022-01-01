<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 02
*/
#################################################*/

class SearchModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('search');

		self::$delete_old_files_list = array(
			'/lang/english/search_english.php',
			'/lang/french/search_french.php'
		);
	}
}
?>
