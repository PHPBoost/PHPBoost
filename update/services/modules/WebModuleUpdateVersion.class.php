<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 11
 * @since       PHPBoost 4.0 - 2014 05 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('web');

		$this->content_tables = array(PREFIX . 'web');
		self::$delete_old_files_list = array(
			'/controllers/WebDeleteController.class.php',
			'/controllers/WebDisplayCategoryController.class.php',
			'/controllers/WebDisplayPendingWebLinksController.class.php',
			'/controllers/WebDisplayWebLinkController.class.php',
			'/controllers/WebDisplayWebLinkTagController.class.php',
			'/controllers/WebFormController.class.php',
			'/controllers/WebManageController.class.php',
			'/controllers/WebVisitWebLinkController.class.php',
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/WebComments.class.php',
			'/phpboost/WebHomePageExtensionPoint.class.php',
			'/phpboost/WebNewContent.class.php',
			'/phpboost/WebNotation.class.php',
			'/phpboost/WebSitemapExtensionPoint.class.php',
			'/services/WebAuthorizationsService.class.php',
			'/services/WebKeywordsCache.class.php',
			'/services/WebLink.class.php',
			'/templates/WebDisplaySeveralWebLinksController.tpl',
			'/templates/WebDisplayWebLinkController.tpl',
			'/util/AdminWebDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'web',
				'columns' => array(
					'name'            => 'title VARCHAR(255) NOT NULL DEFAULT ""',
					'rewrited_name'   => 'rewrited_title VARCHAR(255) NOT NULL DEFAULT ""',
					'contents'        => 'content MEDIUMTEXT',
					'picture_url'     => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""',
					'partner_picture'  => 'partner_thumbnail VARCHAR(255) NOT NULL DEFAULT ""',
					'url'              => 'website_url VARCHAR(255) NOT NULL DEFAULT ""',
					'short_contents'   => 'summary TEXT',
					'number_views'     => 'views_number INT(11) NOT NULL DEFAULT 0',
					'approbation_type' => 'published INT(1) NOT NULL DEFAULT 0',
					'start_date'       => 'publishing_start_date INT(11) NOT NULL DEFAULT 0',
					'end_date'         => 'publishing_end_date INT(11) NOT NULL DEFAULT 0',
				)
			),
			array(
				'table_name' => PREFIX . 'web_cats',
				'columns' => array(
					'image' => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			)
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'web',
				'columns' => array(
					'update_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
				)
			)
		);
	}

	protected function execute_module_specific_changes()
	{
		// Set update_date to creation_date if update_date = 0
		$new_date = $this->querier->select('SELECT web.id, web.update_date, web.creation_date
			FROM ' . PREFIX . 'web web'
		);

		while ($row = $new_date->fetch())
		{
			$this->querier->update(PREFIX . 'web', array('update_date' => $row['creation_date']), 'WHERE update_date = 0 AND id=:id', array('id' => $row['id']));
		}
		$new_date->dispose();
	}
}
?>
