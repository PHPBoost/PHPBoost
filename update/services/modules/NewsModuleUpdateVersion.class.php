<?php
/*##################################################
 *                       NewsModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class NewsModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('news');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'news', $tables))
			$this->update_news_table();
		if (in_array(PREFIX . 'news_cat', $tables))
			$this->update_cats_table();
		
		$this->update();
		$this->delete_old_files();
	}
	
	private function update_news_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'news');
		
		$rows_change = array(
			'idcat' => 'id_category INT(11)',
			'title' => 'name VARCHAR(100)',
			'extend_contents' => 'short_contents TEXT',
			'timestamp' => 'creation_date INT(11)',
			'visible' => 'approbation_type INT(11)',
			'start' => 'start_date INT(11)',
			'end' => 'end_date INT(11)',
			'img' => 'picture_url VARCHAR(250)',
			'user_id' => 'author_user_id INT(11)',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE '. PREFIX .'news' .' CHANGE '. $old_name .' '. $new_name);
		}
		
		if (isset($columns['alt']))
			$this->db_utils->drop_column(PREFIX .'news', 'alt');
		if (isset($columns['compt']))
			$this->db_utils->drop_column(PREFIX .'news', 'compt');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX .'news', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['updated_date']))
			$this->db_utils->add_column(PREFIX .'news', 'updated_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['top_list_enabled']))
			$this->db_utils->add_column(PREFIX .'news', 'top_list_enabled', array('type' => 'boolean', 'notnull' => 1, 'notnull' => 1, 'default' => 0));
		
		$result = $this->querier->select_rows(PREFIX .'news', array('id', 'name', 'sources'));
		while ($row = $result->fetch())
		{
			$old_sources = unserialize($row['sources']);
			$new_sources = array();
			
			foreach ($old_sources as $id => $source)
			{
				$new_sources[$source['sources']] = $source['url'];
			}
			
			$this->querier->update(PREFIX . 'news', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'sources' => serialize($new_sources)
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
	
	private function update_cats_table()
	{
		$this->querier->inject('RENAME TABLE '. PREFIX .'news_cat' .' TO '. PREFIX .'news_cats');
		
		$columns = $this->db_utils->desc_table(PREFIX . 'news_cats');
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX .'news_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
	
		$result = $this->querier->select_rows(PREFIX .'news_cats', array('id', 'name'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'news_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name'])
			), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
	
	private function update()
	{
		$result = $this->querier->select('SELECT news.id, news.rewrited_name, news.id_category, news.contents, news.short_contents, cat.rewrited_name AS cat_rewrited_name
		FROM ' . PREFIX . 'news news
		JOIN ' . PREFIX . 'news_cats cat ON cat.id = news.id_category
		JOIN ' . PREFIX . 'comments_topic com ON com.id_in_module = news.id
		WHERE com.module_id = \'news\'
		ORDER BY news.id ASC');
		while ($row = $result->fetch())
		{
			if (!empty($row['short_contents']))
			{
				$this->querier->update(PREFIX . 'news',
					array(
						'contents' => $row['short_contents'],
						'short_contents' => $row['contents']
					), 
					'WHERE id = :id',
					array('id' => $row['id'])
				);
			}
			
			$this->querier->update(PREFIX . 'comments_topic',
				array('path' => '/news/?url=/'.$row['id_category'].'-'.$row['cat_rewrited_name'].'/'.$row['id'].'-'.$row['rewrited_name']), 
				'WHERE id_in_module=:id_in_module AND module_id=:module_id',
				array('id_in_module' => $row['id'], 'module_id' => 'news')
			);
		}
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/' . $this->module_id . '_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/' . $this->module_id . '_english.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/' . $this->module_id . '_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/' . $this->module_id . '_french.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/phpboost/NewsCats.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_cat.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_config.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_' . $this->module_id . '_menu.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/management.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/' . $this->module_id . '.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/' . $this->module_id . '_block.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/' . $this->module_id . '_list.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '_cat.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_' . $this->module_id . '_menu.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/management.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '_begin.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/' . $this->module_id . '_constants.php'));
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
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/images'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>