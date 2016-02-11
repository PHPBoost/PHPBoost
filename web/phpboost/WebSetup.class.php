<?php
/*##################################################
 *                               WebSetup.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class WebSetup extends DefaultModuleSetup
{
	public static $web_table;
	public static $web_cats_table;
	
	/**
	 * @var string[string] localized messages
	 */
	private $messages;
	
	public static function __static()
	{
		self::$web_table = PREFIX . 'web';
		self::$web_cats_table = PREFIX . 'web_cats';
	}
	
	public function install()
	{
		$this->drop_tables();
		$this->create_tables();
		$this->insert_data();
	}
	
	public function uninstall()
	{
		$this->drop_tables();
		ConfigManager::delete('web', 'config');
		CacheManager::invalidate('module', 'web');
		WebService::get_keywords_manager()->delete_module_relations();
	}
	
	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$web_table, self::$web_cats_table));
	}
	
	private function create_tables()
	{
		$this->create_web_table();
		$this->create_web_cats_table();
	}
	
	private function create_web_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'url' => array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"),
			'contents' => array('type' => 'text', 'length' => 65000),
			'short_contents' => array('type' => 'text', 'length' => 65000),
			'approbation_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0),
			'start_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'end_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'creation_date' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'number_views' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'partner' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'partner_picture' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'privileged_partner' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'name'),
				'contents' => array('type' => 'fulltext', 'fields' => 'contents'),
				'short_contents' => array('type' => 'fulltext', 'fields' => 'short_contents')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$web_table, $fields, $options);
	}
	
	private function create_web_cats_table()
	{
		RichCategory::create_categories_table(self::$web_cats_table);
	}
	
	private function insert_data()
	{
		$this->messages = LangLoader::get('install', 'web');
		$this->insert_web_cats_data();
		$this->insert_web_data();
	}
	
	private function insert_web_cats_data()
	{
		PersistenceContext::get_querier()->insert(self::$web_cats_table, array(
			'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.cat.name']),
			'name' => $this->messages['default.cat.name'],
			'description' => $this->messages['default.cat.description'],
			'image' => '/web/web.png'
		));
	}
	
	private function insert_web_data()
	{
		PersistenceContext::get_querier()->insert(self::$web_table, array(
			'id' => 1,
			'id_category' => 1,
			'name' => $this->messages['default.weblink.name'],
			'rewrited_name' => Url::encode_rewrite($this->messages['default.weblink.name']),
			'url' => 'http://www.phpboost.com',
			'contents' => $this->messages['default.weblink.content'],
			'short_contents' => '',
			'approbation_type' => WebLink::APPROVAL_NOW,
			'start_date' => 0,
			'end_date' => 0,
			'creation_date' => time(),
			'author_user_id' => 1,
			'number_views' => 0,
			'partner' => 1,
			'partner_picture' => '/web/templates/images/phpboost_banner.png'
		));
	}
}
?>
