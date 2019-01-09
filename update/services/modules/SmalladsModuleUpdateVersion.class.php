<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 01 09
 * @since   	PHPBoost 5.1 - 2018 09 20
*/

class SmalladsModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;

	public function __construct()
	{
		parent::__construct('smallads');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}

	public function execute()
	{
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

			$this->update_content();

			$this->delete_old_mini_menu();
			self::pics_to_upload();
		}

		$this->delete_old_files();
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
		$columns = $this->db_utils->desc_table(PREFIX . 'smallads');

		$rows_change = array(
			'picture' => 'thumbnail_url VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 0',
			'type' => 'smallad_type VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL',
			'id_created' => 'author_user_id INT(11) NOT NULL DEFAULT 0',
			'approved' => 'published INT(11) NOT NULL DEFAULT 0',
			'date_created' => 'creation_date INT(11) NOT NULL DEFAULT 0',
			'date_updated' => 'updated_date INT(11) NOT NULL DEFAULT 0'
		);

		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE ' . PREFIX . 'download CHANGE ' . $old_name . ' ' . $new_name);
		}

		if (isset($columns['cat_id']))
			$this->db_utils->drop_column(PREFIX . 'smallads', 'cat_id');
		if (isset($columns['links_flag']))
			$this->db_utils->drop_column(PREFIX . 'smallads', 'links_flag');
		if (isset($columns['shipping']))
			$this->db_utils->drop_column(PREFIX . 'smallads', 'shipping');
		if (isset($columns['vid']))
			$this->db_utils->drop_column(PREFIX . 'smallads', 'vid');
		if (isset($columns['id_updated']))
			$this->db_utils->drop_column(PREFIX . 'smallads', 'id_updated');
		if (isset($columns['date_approved']))
			$this->db_utils->drop_column(PREFIX . 'smallads', 'date_approved');

		if (!isset($columns['id_category']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'id_category', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['rewrited_title']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'rewrited_title', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($columns['description']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'description', array('type' => 'text', 'length' => 65000));
		if (!isset($columns['brand']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'brand', array('type' => 'string', 'length' => 255));
		if (!isset($columns['completed']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'completed', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($columns['location']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'location', array('type' => 'text', 'length' => 65000));
		if (!isset($columns['other_location']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'other_location', array('type' => 'string', 'length' => 255));
		if (!isset($columns['views_number']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'views_number', array('type' => 'integer', 'length' => 11, 'default' => 0));
		if (!isset($columns['displayed_author_email']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'displayed_author_email', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($columns['custom_author_email']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'custom_author_email', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($columns['displayed_author_pm']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'displayed_author_pm', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		if (!isset($columns['displayed_author_name']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'displayed_author_name', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		if (!isset($columns['custom_author_name']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'custom_author_name', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($columns['displayed_author_phone']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'displayed_author_phone', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($columns['author_phone']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'author_phone', array('type' => 'string', 'length' => 25, 'default' => "''"));
		if (!isset($columns['publication_start_date']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'publication_start_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['publication_end_date']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'publication_end_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['sources']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'sources', array('type' => 'text', 'length' => 65000));
		if (!isset($columns['carousel']))
			$this->db_utils->add_column(PREFIX . 'smallads', 'carousel', array('type' => 'text', 'length' => 65000));

		$columns = $this->db_utils->desc_table(PREFIX . 'smallads');
		if (!isset($columns['title']['key']) || !$columns['title']['key'])
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `title` (`title`)');
		if (!isset($columns['contents']['key']) || !$columns['contents']['key'])
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `contents` (`contents`)');
		if (!isset($columns['description']['key']) || !$columns['description']['key'])
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `description` (`description`)');
		if (!isset($columns['id_category']['key']) || !$columns['id_category']['key'])
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'smallads ADD FULLTEXT KEY `id_category` (`id_category`)');

		$folder = new Folder(PATH_TO_ROOT . '/smallads/pics/';
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

	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'smallads');
	}

	private function delete_old_files()
	{
		$file = new File(PATH_TO_ROOT . '/smallads/controllers/AdminSmalladsConfigController.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/controllers/SmalladsHomeController.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/fields/SmalladsFormFieldSelectSources.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/lang/english/smallads_french.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/lang/french/smallads_french.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/phpboost/SmalladsModuleMiniMenu.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/templates/smallads.tpl');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/templates/SmalladsModuleMiniMenu.tpl');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/templates/fields/SmalladsFormFieldSelectSources.tpl');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/smallads.class.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/smallads.php');
		$file->delete();

		$file = new File(PATH_TO_ROOT . '/smallads/smallads_begin.php');
		$file->delete();
	}
}
?>
