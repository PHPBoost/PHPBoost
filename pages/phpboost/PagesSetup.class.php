<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 08 27
 * @since       PHPBoost 3.0 - 2010 01 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PagesSetup extends DefaultModuleSetup
{
	public static $pages_table;
	public static $pages_cats_table;
	/**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$pages_table = PREFIX . 'pages';
		self::$pages_cats_table = PREFIX . 'pages_cats';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_configuration();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$pages_table, self::$pages_cats_table));
	}

	private function delete_configuration()
	{
		ConfigManager::delete('pages', 'config');
	}

	private function create_tables()
	{
		$this->create_pages_table();
		$this->create_pages_cats_table();
	}

	private function create_pages_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'encoded_title' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 16777215),
			'auth' => array('type' => 'text', 'length' => 65000),
			'is_cat' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'id_cat' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'hits' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'count_hits' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'timestamp' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'display_print_link' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'activ_com' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'redirect' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$pages_table, $fields, $options);
	}

	private function create_pages_cats_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_page' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_parent' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
		);

		PersistenceContext::get_dbms_utils()->create_table(self::$pages_cats_table, $fields, $options);
	}
}
?>
