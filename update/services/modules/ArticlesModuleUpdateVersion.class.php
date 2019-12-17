<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 4.0 - 2014 02 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('articles');

		$this->content_tables = array(PREFIX . 'articles');
		$this->delete_old_files_list = array(
			'/phpboost/ArticlesComments.class.php',
			'/phpboost/ArticlesNewContent.class.php',
			'/phpboost/ArticlesNotation.class.php',
			'/phpboost/ArticlesSitemapExtensionPoint.class.php',
			'/services/ArticlesAuthorizationsService.class.php',
			'/templates/ArticlesFormFieldSelectSources.tpl'
		);
		$this->delete_old_folders_list = array(
			'/controllers/categories',
			'/fields',
			'/templates/images'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'articles',
				'columns' => array(
					'contents' => 'contents MEDIUMTEXT'
				)
			)
		);
	}
}
?>
