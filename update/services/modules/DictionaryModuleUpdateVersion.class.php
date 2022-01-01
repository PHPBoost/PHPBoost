<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 04
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DictionaryModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('dictionary');

		self::$delete_old_files_list = array(
			'/lang/english/dictionary_english.php',
			'/lang/french/dictionary_french.php',
			'/phpboost/DictionaryHomePageExtensionPoint.class.php',
		);
	}
}
?>
