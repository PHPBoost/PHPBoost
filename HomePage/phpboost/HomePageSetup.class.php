<?php
class HomePageSetup extends DefaultModuleSetup
{
	public static $home_page_table;

	public static function __static()
	{
		self::$home_page_table = PREFIX . 'home_page';
	}

	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->create_field_member();
	}

	public function uninstall()
	{
		$this->drop_tables();
		$this->delete_field_member();
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$home_page_table));
	}

	private function create_tables()
	{
		$this->create_home_page_table();
	}
	
	private function create_home_page_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'title' => array('type' => 'string', 'length' => 128, 'notnull' => 1),
			'object' => array('type' => 'text', 'length' => 16777215),
			'class' => array('type' => 'string', 'length' => 67, 'notnull' => 1),
			'enabled' => array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'block' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0),
			'position' => array('type' => 'boolean', 'length' => 2, 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'block' => array('type' => 'key', 'fields' => 'block'),
				'class' => array('type' => 'key', 'fields' => 'class'),
				'enabled' => array('type' => 'key', 'fields' => 'enabled')
		));
		PersistenceContext::get_dbms_utils()->create_table(self::$home_page_table, $fields, $options);}
}
?>