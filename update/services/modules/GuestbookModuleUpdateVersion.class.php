<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 12 18
 * @since   	PHPBoost 5.0 - 2017 03 09
*/

class GuestbookModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('guestbook');
	}

	public function execute()
	{
		if (ModulesManager::is_module_installed('guestbook'))
		{
			$this->update_content();
		}
	}

	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'guestbook');
	}
}
?>
