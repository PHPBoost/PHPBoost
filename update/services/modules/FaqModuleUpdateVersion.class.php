<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 06
 * @since       PHPBoost 4.0 - 2014 05 22
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('faq');

		$this->content_tables = array(array('name' => PREFIX . 'faq', 'content_field' => 'answer'));
		self::$delete_old_files_list = array(
			'/controllers/ajax/FaqAjaxDeleteQuestionController.class.php',
			'/controllers/FaqDeleteController.class.php',
			'/controllers/FaqDisplayCategoryController.class.php',
			'/controllers/FaqDisplayPendingFaqQuestionsController.class.php',
			'/controllers/FaqFormController.class.php',
			'/controllers/FaqManageController.class.php',
			'/controllers/FaqReorderCategoryQuestionsController.class.php',
			'/lang/english/config.php',
			'/lang/french/config.php',
			'/phpboost/FaqNewContent.class.php',
			'/phpboost/FaqSitemapExtensionPoint.class.php',
			'/phpboost/FaqHomePageExtensionPoint.class.php',
			'/services/FaqAuthorizationsService.class.php',
			'/services/FaqQuestion.class.php',
			'/templates/FaqReorderCategoryQuestionsController.tpl',
			'/templates/FaqDisplaySeveralFaqQuestionsController.tpl',
			'/templates/faq-mini.css',
			'/util/AdminFaqDisplayResponse.class.php'
		);
		self::$delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'faq_cats',
				'columns' => array(
					'image'    => 'thumbnail VARCHAR(255) NOT NULL DEFAULT ""'
				)
			),
			array(
				'table_name' => PREFIX . 'faq',
				'columns' => array(
					'question' => 'title VARCHAR(255) NOT NULL DEFAULT ""',
					'answer'   => 'content MEDIUMTEXT',
				)
			)
		);
		UpdateServices::update_table_content(PREFIX . 'faq', 'content');
	}
}
?>
