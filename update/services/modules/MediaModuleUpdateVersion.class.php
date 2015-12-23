<?php
/*##################################################
 *                       MediaModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : May 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class MediaModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('media');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (!in_array(PREFIX . 'media_cats', $tables))
			$this->update_cats_table();
		
		$this->delete_old_files();
	}
	
	private function update_cats_table()
	{
		$this->querier->inject('RENAME TABLE ' . PREFIX . 'media_cat' . ' TO ' . PREFIX . 'media_cats');
		
		$columns = $this->db_utils->desc_table(PREFIX . 'media_cats');
		
		$this->querier->inject('ALTER TABLE ' . PREFIX . 'media_cats CHANGE mime_type content_type INT(11)');
		
		if (isset($columns['visible']))
			$this->db_utils->drop_column(PREFIX . 'media_cats', 'visible');
		if (isset($columns['active']))
			$this->db_utils->drop_column(PREFIX . 'media_cats', 'active');
		if (isset($columns['num_media']))
			$this->db_utils->drop_column(PREFIX . 'media_cats', 'num_media');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX . 'media_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['special_authorizations']))
			$this->db_utils->add_column(PREFIX . 'media_cats', 'special_authorizations', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		
		$result = $this->querier->select_rows(PREFIX . 'media_cats', array('id', 'name', 'auth'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'media_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'special_authorizations' => (int)!empty($row['auth'])
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_media_cats.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_media_config.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_media_menu.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/xmlhttprequest_cats.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/phpboost/MediaCats.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_media_cats.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_media_config.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_media_menu.tpl'));
		$file->delete();
	}
}
?>