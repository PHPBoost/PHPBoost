<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 27
*/
#################################################*/

class ContactModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('contact');
		
		$this->delete_old_files_list = array(
			'/phpboost/ContactHomePageExtensionPoint.class.php'
		);
	}
}
?>
