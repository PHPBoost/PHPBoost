<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 11
 * @since       PHPBoost 4.0 - 2014 05 22
*/

class PagesModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('pages');
		
		$this->content_tables = array(PREFIX . 'pages');
		
		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'pages',
				'columns' => array(
					'contents' => 'contents MEDIUMTEXT'
				)
			)
		);
	}
}
?>
