<?php
/*##################################################
 *                             ArticlesSetup.class.php
 *                            -------------------
 *   begin                : April 25, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class ArticlesSetup extends DefaultModuleSetup
{
	public static $articles_table;
	public static $articles_cats_table;
        public static $articles_keywords_table;
	public static $articles_keywords_relation_table;
        
        /**
	 * @var string[string] localized messages
	 */
	private $messages;

	public static function __static()
	{
		self::$articles_table = PREFIX . 'articles';
		self::$articles_cats_table = PREFIX . 'articles_cats';
                self::$articles_keywords_table = PREFIX . 'news_keywords';
		self::$articles_keywords_relation_table = PREFIX . 'news_keywords_relation';
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
                ConfigManager::delete('articles', 'catogories');
	}

	private function drop_tables()
	{
		PersistenceContext::get_dbms_utils()->drop(array(self::$articles_table, self::$articles_cats_table, self::$articles_keywords_table, self::$articles_keywords_relation_table));
	}

	private function create_tables()
	{
		$this->create_articles_table();
		$this->create_articles_cats_table();
                $this->create_articles_keywords_table();
		$this->create_articles_keywords_relation_table();
	}

	private function create_articles_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'id_category' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'picture_url' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'rewrited_title' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'description' => array('type' => 'text', 'length' => 65000),
			'content' => array('type' => 'text', 'length' => 65000),
			'number_view' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_user_id' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'author_name_visitor' => array('type' => 'string', 'length' => 255, 'default' => "''"),
			'published' => array('type' => 'boolean', 'notnull' => 1, 'default' => 0),
			'publishing_start_date' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'publishing_end_date' => array('type' => 'integer', 'length' => 11, 'default' => 0),
			'date_created' => array('type' => 'integer', 'length' => 11, 'default' => 0),
                        'sources' => array('type' => 'text', 'length' => 65000),
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'id_category' => array('type' => 'key', 'fields' => 'id_category'),
				'title' => array('type' => 'fulltext', 'fields' => 'title'),
				'content' => array('type' => 'fulltext', 'fields' => 'content')
			)
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_table, $fields, $options);
	}

	private function create_articles_cats_table()
	{
                RichCategory::create_categories_table(self::$articles_cats_table);
	}
        
        private function create_articles_keywords_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'name' => array('type' => 'string', 'length' => 100, 'notnull' => 1, 'default' => "''"),
			'rewrited_name' => array('type' => 'string', 'length' => 250, 'default' => "''"),
		);
		$options = array('primary' => array('id'));
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_keywords_table, $fields, $options);
	}
	
	private function create_articles_keywords_relation_table()
	{
		$fields = array(
			'id_articles' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'id_keyword' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
		);
		PersistenceContext::get_dbms_utils()->create_table(self::$articles_keywords_relation_table, $fields);
	}

	private function insert_data()
	{
                $this->messages = LangLoader::get('install', 'articles');
                $this->insert_articles_data();
                $this->insert_articles_cats_data();
	}
        
        private function insert_articles_data()
        {
                
                PersistenceContext::get_querier()->insert(self::$articles_table, array(
                        'id' => 1,
			'id_category' => 1,
			'picture_url' => '',
			'title' => $this->messages['default.article.name'],
			'rewrited_title' => Url::encode_rewrite($this->messages['default.article.name']),
			'description' => $this->messages['default.article.description'],
			'content' => $this->messages['default.article.contents'],
			'number_view' => 0,
			'author_user_id' => 1,
			'author_name_visitor' => '',
			'published' => Articles::PUBLISHED_NOW,
			'publishing_start_date' => 0,
			'publishing_end_date' => 0,
			'date_created' => time(),
                        'sources' => serialize(array())
                ));
        }
        
        private function insert_articles_cats_data()
        {
                PersistenceContext::get_querier()->insert(self::$articles_cats_table, array(
                        'id' => 1,
			'id_parent' => 0,
			'c_order' => 1,
			'auth' => '',
			'rewrited_name' => Url::encode_rewrite($this->messages['default.category.name']),
			'name' => $this->messages['default.category.name'],
			'description' => $this->messages['default.category.description'],
			'image' => '/articles/articles.png'
                ));
        }
}

?>