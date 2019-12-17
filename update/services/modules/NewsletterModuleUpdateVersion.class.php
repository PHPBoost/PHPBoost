<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 09
 * @since       PHPBoost 5.0 - 2017 03 09
*/

class NewsletterModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('newsletter');
		
		$this->content_tables = array(PREFIX . 'newsletter_archives');
	}
}
?>
