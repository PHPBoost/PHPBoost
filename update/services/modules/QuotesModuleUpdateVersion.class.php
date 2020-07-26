<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 07 13
 * @since       PHPBoost 6.0 - 2019 11 03
 * @contributor xela <xela@phpboost.com>
*/

class QuotesModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('quotes');

		$this->content_tables = array(array('name' => PREFIX . 'quotes', 'content_field' => 'quote'));
		self::$delete_old_files_list = array(
			'/controllers/AdminQuotesManageController.class.php',
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/QuotesHomePageExtensionPoint.class.php',
			'/phpboost/QuotesSitemapExtensionPoint.class.php',
			'/phpboost/QuotesTreeLinks.class.php',
			'/services/QuotesAuthorizationsService.class.php',
			'/util/AdminQuotesDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);
		
		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'quotes_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);
	}
}
?>
