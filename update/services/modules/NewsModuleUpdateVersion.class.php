<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 3.0 - 2012 04 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class NewsModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('news');

		$this->content_tables = array(PREFIX . 'news');
		$this->delete_old_files_list = array(
			'/controllers/NewsDeleteController.class.php',
			'/controllers/NewsDisplayCategoryController.class.php',
			'/controllers/NewsDisplayNewsController.class.php',
			'/controllers/NewsDisplayPendingNewsController.class.php',
			'/controllers/NewsDisplayNewsTagController.class.php',
			'/controllers/NewsFormController.class.php',
			'/controllers/NewsManageController.class.php',
			'/phpboost/NewsComments.class.php',
			'/phpboost/NewsNewContent.class.php',
			'/phpboost/NewsSitemapExtensionPoint.class.php',
			'/phpboost/NewsHomePageExtensionPoint.class.php',
			'/services/NewsAuthorizationsService.class.php',
			'/services/NewsKeywordsCache.class.php',
			'/templates/NewsFormFieldSelectSources.tpl',
			'/templates/NewsDisplayNewsController.tpl',
			'/templates/NewsDisplaySeveralNewsController.tpl',
			'/util/AdminNewsDisplayResponse.class.php'
		);
		$this->delete_old_folders_list = array(
			'/controllers/categories',
			'/fields'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'news',
				'columns' => array(
					'name'    		   => 'title VARCHAR(255) NOT NULL DEFAULT ""',
					'rewrited_name'    => 'rewrited_title VARCHAR(255) NOT NULL DEFAULT ""',
					'contents'         => 'content MEDIUMTEXT',
					'short_contents'   => 'summary TEXT',
					'picture_url'      => 'thumbnail_url VARCHAR(255) NOT NULL DEFAULT ""',
					'approbation_type' => 'publication INT(1) NOT NULL DEFAULT 0',
					'number_view'      => 'views_number INT(11) NOT NULL DEFAULT 0',
				)
			)
		);
	}
}
?>
