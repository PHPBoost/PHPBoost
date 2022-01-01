<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 21
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
		self::$delete_old_files_list = array(
			'/phpboost/NewsComments.class.php',
			'/phpboost/NewsCommentsTopic.class.php',
			'/phpboost/NewsExtensionPointProvider.class.php',
			'/phpboost/NewsFeedProvider.class.php',
			'/phpboost/NewsHomePageExtensionPoint.class.php',
			'/phpboost/NewsNewContent.class.php',
			'/phpboost/NewsScheduledJobs.class.php',
			'/phpboost/NewsSearchable.class.php',
			'/phpboost/NewsSetup.class.php',
			'/phpboost/NewsSitemapExtensionPoint.class.php',
			'/phpboost/NewsTreeLinks.class.php',
			'/templates/NewsFormFieldSelectSources.tpl',
			'/templates/NewsDisplayNewsController.tpl',
			'/templates/NewsDisplaySeveralNewsController.tpl',
			// 6.0.b1
			'/templates/NewsItemController.tpl',
			'/templates/NewsSeveralItemsController.tpl',
		);
		self::$delete_old_folders_list = array(
			'/controllers',
			'/fields',
			'/services',
			'/util'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'news',
				'columns' => array(
					'name'             => 'title VARCHAR(255) NOT NULL DEFAULT ""',
					'rewrited_name'    => 'rewrited_title VARCHAR(255) NOT NULL DEFAULT ""',
					'contents'         => 'content MEDIUMTEXT',
					'short_contents'   => 'summary TEXT',
					'picture_url'      => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""',
					'start_date'       => 'publishing_start_date INT(11) NOT NULL DEFAULT 0',
					'updated_date'     => 'update_date INT(11) NOT NULL DEFAULT 0',
					'end_date'         => 'publishing_end_date INT(11) NOT NULL DEFAULT 0',
					'approbation_type' => 'published INT(1) NOT NULL DEFAULT 0',
					'number_view'      => 'views_number INT(11) NOT NULL DEFAULT 0',
					// 6.0.b1
					'publication'   => 'published INT(1) NOT NULL DEFAULT 0',
					'thumbnail_url' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""',
				)
			),
			array(
				'table_name' => PREFIX . 'news_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);

		$this->database_columns_to_delete = array(
			array(
				'table_name' => PREFIX . 'news',
				'columns' => array(
					'archive'
				)
			)
		); 
	}

	protected function execute_module_specific_changes()
	{
		// Set update_date to creation_date if update_date = 0
		$new_date = $this->querier->select('SELECT id, update_date, creation_date
			FROM ' . PREFIX . 'news'
		);

		while ($row = $new_date->fetch())
		{
			$this->querier->update(PREFIX . 'news', array('update_date' => $row['creation_date']), 'WHERE update_date = 0 AND id = :id', array('id' => $row['id']));
		}
		$new_date->dispose();
	}
}
?>
