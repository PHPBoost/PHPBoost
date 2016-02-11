<?php
/*##################################################
 *                       GalleryModuleUpdateVersion.class.php
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

class GalleryModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('gallery');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'gallery_cats', $tables))
			$this->update_cats_table();
		
		$this->delete_old_files();
	}
	
	private function update_cats_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'gallery_cats');
		
		if (isset($columns['contents']))
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'gallery_cats CHANGE contents description TEXT');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX . 'gallery_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['special_authorizations']))
			$this->db_utils->add_column(PREFIX . 'gallery_cats', 'special_authorizations', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($columns['c_order']))
			$this->db_utils->add_column(PREFIX . 'gallery_cats', 'c_order', array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['id_parent']))
			$this->db_utils->add_column(PREFIX . 'gallery_cats', 'id_parent', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['image']))
			$this->db_utils->add_column(PREFIX . 'gallery_cats', 'image', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"));
		
		if (isset($columns['level']))
		{
			// réorganisation des catégories
			$categories_tree = $previous_parent = array();
			$previous_parent[0] = 0;
			
			$c_order = 1;
			$previous_level = $last_id = 0;
			
			$result = $this->querier->select_rows(PREFIX . 'gallery_cats', array('id', 'level'), 'ORDER BY id_left');
			while ($row = $result->fetch())
			{
				if ($row['level'] > $previous_level)
				{
					$id_parent = $last_id;
					$previous_parent[$row['level']] = $last_id;
				}
				else if ($row['level'] == 0)
				{
					$id_parent = 0;
					$previous_parent[$row['level']] = $row['id'];
				}
				else if ($row['level'] < $previous_level || $row['level'] == $previous_level)
				{
					$id_parent = $previous_parent[$row['level']];
				}
				else
					$id_parent = 0;
				
				$categories_tree[$row['id']] = array(
					'id_parent' => $id_parent,
					'c_order' => $c_order,
				);
				
				$c_order++;
				$last_id = $row['id'];
				$previous_level = $row['level'];
			}
			$result->dispose();
		}
		
		if (isset($columns['id_left']) && $columns['id_left']['key'])
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'gallery_cats DROP KEY `id_left`');
		
		if (isset($columns['id_left']))
			$this->db_utils->drop_column(PREFIX . 'gallery_cats', 'id_left');
		if (isset($columns['id_right']))
			$this->db_utils->drop_column(PREFIX . 'gallery_cats', 'id_right');
		if (isset($columns['level']))
			$this->db_utils->drop_column(PREFIX . 'gallery_cats', 'level');
		if (isset($columns['nbr_pics_aprob']))
			$this->db_utils->drop_column(PREFIX . 'gallery_cats', 'nbr_pics_aprob');
		if (isset($columns['nbr_pics_unaprob']))
			$this->db_utils->drop_column(PREFIX . 'gallery_cats', 'nbr_pics_unaprob');
		if (isset($columns['status']))
			$this->db_utils->drop_column(PREFIX . 'gallery_cats', 'status');
		if (isset($columns['aprob']))
			$this->db_utils->drop_column(PREFIX . 'gallery_cats', 'aprob');
		
		if (isset($columns['level']))
		{
			$result = $this->querier->select_rows(PREFIX . 'gallery_cats', array('id', 'name', 'auth'));
			while ($row = $result->fetch())
			{
				$this->querier->update(PREFIX . 'gallery_cats', array(
					'id_parent' => $categories_tree[$row['id']]['id_parent'],
					'c_order' => $categories_tree[$row['id']]['c_order'],
					'rewrited_name' => Url::encode_rewrite($row['name']),
					'special_authorizations' => (int)!empty($row['auth'])
				), 'WHERE id = :id', array('id' => $row['id']));
			}
			$result->dispose();
		}
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/GalleryUrlBuilder.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_gallery_cat.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_gallery_cat_add.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_xmlhttprequest.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/pics/index.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/pics/thumbnails/index.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_gallery_cat.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_gallery_cat_add.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_gallery_cat_del.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_gallery_cat_edit.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_gallery_cat_edit2.tpl'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/js'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>