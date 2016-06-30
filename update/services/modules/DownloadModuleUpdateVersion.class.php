<?php
/*##################################################
 *                       DownloadModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : May 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class DownloadModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('download');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'download', $tables))
			$this->update_download_table();
		if (!in_array(PREFIX . 'download_cats', $tables))
			$this->update_cats_table();
		
		$this->update_comments();
		
		$this->delete_old_files();
	}
	
	private function update_download_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'download');
		
		$rows_change = array(
			'idcat' => 'id_category INT(11)',
			'title' => 'name VARCHAR(255)',
			'user_id' => 'author_user_id INT(11)',
			'url' => 'url VARCHAR(255)',
			'image' => 'picture_url VARCHAR(255)',
			'size' => 'size BIGINT(18)',
			'timestamp' => 'creation_date INT(11)',
			'release_timestamp' => 'updated_date INT(11)',
			'start' => 'start_date INT(11)',
			'end' => 'end_date INT(11)',
			'visible' => 'approbation_type INT(11)',
			'count' => 'number_downloads INT(11)'
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE ' . PREFIX . 'download CHANGE ' . $old_name . ' ' . $new_name);
		}
		
		if (isset($columns['approved']))
			$this->db_utils->drop_column(PREFIX . 'download', 'approved');
		if (isset($columns['force_download']))
			$this->db_utils->drop_column(PREFIX . 'download', 'force_download');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX . 'download', 'rewrited_name', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($columns['author_display_name']))
			$this->db_utils->add_column(PREFIX . 'download', 'author_display_name', array('type' =>  'string', 'length' => 255, 'default' => "''"));
		
		if (isset($columns['idcat']))
		{
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'download DROP KEY `idcat`');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'download DROP KEY `title`');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'download ADD KEY `id_category` (`id_category`)');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'download ADD FULLTEXT KEY `title` (`name`)');
		}
		
		$result = $this->querier->select_rows(PREFIX . 'download', array('id', 'name', 'url', 'size'));
		while ($row = $result->fetch())
		{
			$file_size = Url::get_url_file_size($row['url']);
			$file_size = (empty($file_size) && $row['size']) ? $row['size'] * pow(1024, 2) : $file_size;
			
			$this->querier->update(PREFIX . 'download', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'size' => $file_size
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function update_cats_table()
	{
		$this->querier->inject('RENAME TABLE ' . PREFIX . 'download_cat' . ' TO ' . PREFIX . 'download_cats');
		
		$columns = $this->db_utils->desc_table(PREFIX . 'download_cats');
		
		$rows_change = array(
			'name' => 'name VARCHAR(255)',
			'contents' => 'description TEXT',
			'icon' => 'image VARCHAR(255)',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE ' . PREFIX . 'download_cats CHANGE ' . $old_name . ' ' . $new_name);
		}
		
		if (isset($columns['visible']))
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'download_cats DROP KEY `class`');
		
		if (isset($columns['visible']))
			$this->db_utils->drop_column(PREFIX . 'download_cats', 'visible');
		if (isset($columns['num_files']))
			$this->db_utils->drop_column(PREFIX . 'download_cats', 'num_files');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX . 'download_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['special_authorizations']))
			$this->db_utils->add_column(PREFIX . 'download_cats', 'special_authorizations', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		
		$result = $this->querier->select_rows(PREFIX . 'download_cats', array('id', 'name', 'auth', 'image'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'download_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'special_authorizations' => (int)!empty($row['auth']),
				'image' => $row['image'] == 'download.png' ? '/download/download.png' : $row['image']
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function update_comments()
	{
		$result = $this->querier->select('SELECT download.id, download.rewrited_name, download.id_category, cat.rewrited_name AS cat_rewrited_name, id_topic
		FROM ' . DownloadSetup::$download_table . ' download
		JOIN ' . DownloadSetup::$download_cats_table . ' cat ON cat.id = download.id_category
		JOIN ' . PREFIX . 'comments_topic com ON com.id_in_module = download.id
		WHERE com.module_id = \'download\'
		ORDER BY download.id ASC');
		
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'comments_topic',
				array('path' => '/download/?url=/'.$row['id_category'].'-'.$row['cat_rewrited_name'].'/'.$row['id'].'-'.$row['rewrited_name']),
				'WHERE id_topic=:id_topic',
				array('id_topic' => $row['id_topic'])
			);
		}
		$result->dispose();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_download.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_download_cat.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_download_config.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_download_menu.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/count.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/download.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/download_auth.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/download_begin.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/management.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/xmlhttprequest_cats.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/download_english.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/download_french.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/phpboost/DownloadCats.class.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_download_cat.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_download_cat_edition.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_download_cat_remove.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_download_config.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_download_management.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_download_menu.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/download.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/download_generic_results.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/file_management.tpl'));
		$file->delete();
	}
}
?>
