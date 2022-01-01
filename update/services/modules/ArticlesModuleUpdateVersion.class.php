<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 06
 * @since       PHPBoost 4.0 - 2014 02 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('articles');

		$this->content_tables = array(PREFIX . 'articles');
		self::$delete_old_files_list = array(
			'/controllers/AdminArticlesConfigController.class.php',
			'/controllers/ArticlesDeleteController.class.php',
			'/controllers/ArticlesDisplayArticlesController.class.php',
			'/controllers/ArticlesDisplayArticlesTagController.class.php',
			'/controllers/ArticlesDisplayCategoryController.class.php',
			'/controllers/ArticlesDisplayPendingArticlesController.class.php',
			'/controllers/ArticlesFormController.class.php',
			'/controllers/ArticlesManageController.class.php',
			'/controllers/ArticlesPrintArticlesController.class.php',
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/ArticlesComments.class.php',
			'/phpboost/ArticlesCommentsTopic.class.php',
			'/phpboost/ArticlesExtensionPointProvider.class.php',
			'/phpboost/ArticlesFeedProvider.class.php',
			'/phpboost/ArticlesHomePageExtensionPoint.class.php',
			'/phpboost/ArticlesNewContent.class.php',
			'/phpboost/ArticlesNotation.class.php',
			'/phpboost/ArticlesScheduledJobs.class.php',
			'/phpboost/ArticlesSearchable.class.php',
			'/phpboost/ArticlesSetup.class.php',
			'/phpboost/ArticlesSitemapExtensionPoint.class.php',
			'/phpboost/ArticlesTreeLinks.class.php',
			'/templates/ArticlesDisplayArticlesController.tpl',
			'/templates/ArticlesDisplaySeveralArticlesController.tpl',
			'/templates/ArticlesFormController.tpl',
			'/templates/ArticlesFormFieldSelectSources.tpl'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories',
			'/fields',
			'/services',
			'/util'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'articles',
				'columns' => array(
					'rewrited_title'        => 'rewrited_title VARCHAR(255) NOT NULL DEFAULT ""',
					'contents'              => 'content MEDIUMTEXT',
					'picture_url'           => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""',
					'description'           => 'summary TEXT',
					'author_name_displayed' => 'author_name_displayed INT(11) NOT NULL DEFAULT 1',
					'number_view'           => 'views_number INT(11) NOT NULL DEFAULT 0',
					'date_created'          => 'creation_date INT(11) NOT NULL DEFAULT 0',
					'date_updated'          => 'update_date INT(11) NOT NULL DEFAULT 0'
				)
			),
			array(
				'table_name' => PREFIX . 'articles_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);

		$this->database_columns_to_delete = array(
			array(
				'table_name' => PREFIX . 'articles',
				'columns' => array(
					'author_name_displayed'
				)
			)
		);
	}
}
?>
