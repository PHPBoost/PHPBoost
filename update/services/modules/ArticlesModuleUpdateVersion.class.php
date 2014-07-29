<?php
/*##################################################
 *                       ArticlesModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 17, 2014
 *   copyright            : (C) 2014 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class ArticlesModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('articles');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'articles', $tables))
			$this->update_articles_table();
		if (in_array(PREFIX . 'articles_cats', $tables))
			$this->update_cats_table();
		
		$this->update_comments();
		$this->delete_old_files();
	}
	
	private function update_articles_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'articles');
		
		$rows_change = array(
			'idcat' => 'id_category INT(11)',
			'views' => 'number_view INT(11)',
			'timestamp' => 'date_created INT(11)',
			'visible' => 'published INT(11)',
			'start' => 'publishing_start_date INT(11)',
			'end' => 'publishing_end_date INT(11)',
			'icon' => 'picture_url VARCHAR(250)',
			'user_id' => 'author_user_id INT(11)',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE '. PREFIX .'articles' .' CHANGE '. $old_name .' '. $new_name);
		}
		
		$this->querier->inject('ALTER TABLE '. PREFIX .'articles ADD FULLTEXT KEY `description` (`description`)');
		
		if (!isset($columns['rewrited_title']))
			$this->db_utils->add_column(PREFIX .'articles', 'rewrited_title', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['author_name_displayed']))
			$this->db_utils->add_column(PREFIX .'articles', 'author_name_displayed', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		if (!isset($columns['date_updated']))
			$this->db_utils->add_column(PREFIX .'articles', 'date_updated', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['notation_enabled']))
			$this->db_utils->add_column(PREFIX .'articles', 'notation_enabled', array('type' => 'boolean', 'notnull' => 1, 'default' => 1));
		
		$result = $this->querier->select_rows(PREFIX .'articles', array('id', 'title'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'articles', array(
				'rewrited_title' => Url::encode_rewrite($row['title'])
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
	
	private function update_cats_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'articles_cats');
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX .'articles_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		
		$result = $this->querier->select_rows(PREFIX .'articles_cats', array('id', 'name'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'articles_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name'])
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT article.id, article.rewrited_title, article.id_category, cat.rewrited_name AS cat_rewrited_name
		FROM ' . PREFIX . 'articles article
		JOIN ' . PREFIX . 'articles_cats cat ON cat.id = article.id_category
		JOIN ' . PREFIX . 'comments_topic com ON com.id_in_module = article.id
		WHERE com.module_id = \'articles\'
		ORDER BY article.id ASC');
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'comments_topic',
				array('path' => '/articles/?url=/'.$row['id_category'].'-'.$row['cat_rewrited_name'].'/'.$row['id'].'-'.$row['rewrited_title']), 
				'WHERE id_in_module=:id_in_module AND module_id=:module_id',
				array('id_in_module' => $row['id'], 'module_id' => 'articles')
			);
		}
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/' . $this->module_id . '_english.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/' . $this->module_id . '_french.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/phpboost/ArticlesCats.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/articles.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/contentbg.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/folder.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_top_row.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/notes.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/views.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_cat.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_config.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_management.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_menu.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/' . $this->module_id . '.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/' . $this->module_id . '_cat.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/management.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '_cat.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '_menu.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '_begin.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '_constants.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/carousel.js'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/management.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/print.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/xmlhttprequest.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/xmlhttprequest_cats.php'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/framework'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>