<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 19
 * @since       PHPBoost 5.1 - 2018 09 20
*/

class SmalladsModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('smallads');

		$this->content_tables = array(PREFIX . 'smallads');
		$this->delete_old_files_list = array(
			'/controllers/AdminSmalladsConfigController.class.php',
			'/controllers/SmalladsHomeController.class.php',
			'/fields/SmalladsFormFieldSelectSources.class.php',
			'/lang/english/smallads_english.php',
			'/lang/english/smallads_french.php',
			'/phpboost/SmalladsComments.class.php',
			'/phpboost/SmalladsModuleMiniMenu.class.php',
			'/phpboost/SmalladsSitemapExtensionPoint.class.php',
			'/services/SmalladsAuthorizationsService.class.php',
			'/services/SmalladsKeywordsCache.class.php',
			'/templates/smallads.tpl',
			'/templates/SmalladsModuleMiniMenu.tpl',
			'/templates/images/default.png',
			'/templates/fields/SmalladsFormFieldSelectSources.tpl',
			'/smallads.class.php',
			'/smallads.php',
			'/smallads_begin.php'
		);
		$this->delete_old_folders_list = array(
			'/controllers/categories'
		);

		$this->database_columns_to_add = array(
			array(
				'table_name' => PREFIX . 'smallads',
				'columns' => array(
					'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'rewrited_title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
					'description' => array('type' => 'text', 'length' => 65000),
					'brand' => array('type' => 'string', 'length' => 255),
					'completed' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
					'location' => array('type' => 'text', 'length' => 65000),
					'other_location' => array('type' => 'string', 'length' => 255),
					'views_number' => array('type' => 'integer', 'length' => 11, 'default' => 0),
					'displayed_author_email' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
					'custom_author_email' => array('type' => 'string', 'length' => 255, 'default' => "''"),
					'displayed_author_pm' => array('type' => 'boolean', 'notnull' => 1, 'default' => 1),
					'displayed_author_name' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
					'custom_author_name' => array('type' => 'string', 'length' => 255, 'default' => "''"),
					'displayed_author_phone' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
					'author_phone' => array('type' => 'string', 'length' => 25, 'default' => "''"),
					'publication_start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'publication_end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
					'sources' => array('type' => 'text', 'length' => 65000),
					'carousel' => array('type' => 'text', 'length' => 65000)
				)
			)
		);

		$this->database_columns_to_delete = array(
			array(
				'table_name' => PREFIX . 'smallads',
				'columns' => array('cat_id', 'links_flag', 'shipping', 'vid', 'id_updated', 'date_approved')
			)
		);

		$this->database_keys_to_add = array(
			array(
				'table_name' => PREFIX . 'smallads',
				'keys' => array(
					'title' => true,
					'contents' => true,
					'description' => true,
					'id_category' => true
				)
			)
		);

		$this->database_columns_to_modify = array(
			array(
				'table_name' => PREFIX . 'smallads',
				'columns' => array(
					'picture' => 'thumbnail_url VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0',
					'type' => 'smallad_type VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL',
					'id_created' => 'author_user_id INT(11) NOT NULL DEFAULT 0',
					'approved' => 'published INT(11) NOT NULL DEFAULT 0',
					'date_created' => 'creation_date INT(11) NOT NULL DEFAULT 0',
					'date_updated' => 'updated_date INT(11) NOT NULL DEFAULT 0'
				)
			)
		);
	}

	public function execute()
	{
		parent::execute();
		if (ModulesManager::is_module_installed('smallads'))
		{
			$tables = $this->db_utils->list_tables(true);

			if (!in_array(PREFIX . 'smallads_cats', $tables))
			{
				$this->create_smallads_cats_table();
				$this->insert_smallads_cats_data();
			}

			if (in_array(PREFIX . 'smallads', $tables))
				$this->update_smallads_table();

			$this->delete_old_mini_menu();
			self::pics_to_upload();
		}
	}

	private function create_smallads_cats_table()
	{
		RichCategory::create_categories_table(PREFIX . 'smallads_cats');
	}

	private function insert_smallads_cats_data()
	{
		$messages = LangLoader::get('install', 'smallads');
		$this->querier->insert(PREFIX . 'smallads_cats', array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($messages['default.category.name']),
			'name' => $messages['default.category.name'],
			'description' => $messages['default.category.description'],
			'image' => '/smallads/smallads.png'
		));
	}

	private function update_smallads_table()
	{
		$folder = new Folder(PATH_TO_ROOT . '/smallads/pics/');
		if ($folder->exists())
		{
			$messages = LangLoader::get('install', 'smallads');
			$result = $this->querier->select_rows(PREFIX . 'smallads', array('id', 'title'));
			while ($row = $result->fetch()) {
				$this->querier->update(PREFIX . 'smallads', array(
					'rewrited_title' => Url::encode_rewrite($row['title']),
					'smallad_type' => Url::encode_rewrite($messages['default.smallad.type']),
					'id_category' => 1,
				), 'WHERE id = :id', array('id' => $row['id']));
			}
			$result->dispose();
		}
	}

	private function delete_old_mini_menu()
	{
		$menu_id = 0;
		try {
			$menu_id = $this->querier->get_column_value(DB_TABLE_MENUS, 'id', 'WHERE title = "smallads/SmalladsModuleMiniMenu"');
		} catch (RowNotFoundException $e) {}

		if ($menu_id)
 		{
			$menu = MenuService::load($menu_id);
			MenuService::delete($menu);
			MenuService::generate_cache();
		}
	}

	private static function pics_to_upload()
	{
		// Move pics content to upload and delete pics
		$source = PATH_TO_ROOT . '/smallads/pics/';
		$folder = new Folder($source);
		if ($folder->exists())
		{
			$dest = PATH_TO_ROOT . '/upload/';
			if (is_dir($source)) {
				if ($dh = opendir($source)) {
					while (($file = readdir($dh)) !== false) {
						if ($file != '.' && $file != '..') {
							rename($source . $file, $dest . $file);
						}
					}
					closedir($dh);
				}
			}
			$folder->delete();

			// update thumbnail_url files to /upload/files
			$result = PersistenceContext::get_querier()->select_rows(PREFIX . 'smallads', array('id', 'thumbnail_url'));
			while ($row = $result->fetch()) {
				if ($row['thumbnail_url'] != "") {
					PersistenceContext::get_querier()->update(PREFIX . 'smallads', array(
						'thumbnail_url' => '/upload/' . $row['thumbnail_url'],
					), 'WHERE id = :id', array('id' => $row['id']));
				} else {
					PersistenceContext::get_querier()->update(PREFIX . 'smallads', array(
						'thumbnail_url' => '/smallads/templates/images/no-thumb.png',
					), 'WHERE id = :id', array('id' => $row['id']));
				}
			}
			$result->dispose();
		}
	}
}
?>
