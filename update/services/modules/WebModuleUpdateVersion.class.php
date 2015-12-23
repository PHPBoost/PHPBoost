<?php
/*##################################################
 *                       WebModuleUpdateVersion.class.php
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

class WebModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('web');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'web', $tables))
			$this->update_web_table();
		if (!in_array(PREFIX . 'web_cats', $tables))
			$this->update_cats_table();
		
		$this->delete_old_files();
	}
	
	private function update_web_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'web');
		
		$rows_change = array(
			'idcat' => 'id_category INT(11)',
			'title' => 'name VARCHAR(255)',
			'url' => 'url VARCHAR(255)',
			'compt' => 'number_views INT(11)',
			'aprob' => 'approbation_type INT(11)',
			'timestamp' => 'creation_date INT(11)'
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE ' . PREFIX . 'web CHANGE ' . $old_name . ' ' . $new_name);
		}
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX . 'web', 'rewrited_name', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($columns['short_contents']))
			$this->db_utils->add_column(PREFIX . 'web', 'short_contents', array('type' => 'text', 'length' => 65000));
		if (!isset($columns['start_date']))
			$this->db_utils->add_column(PREFIX . 'web', 'start_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['end_date']))
			$this->db_utils->add_column(PREFIX . 'web', 'end_date', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['author_user_id']))
			$this->db_utils->add_column(PREFIX . 'web', 'author_user_id', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['partner']))
			$this->db_utils->add_column(PREFIX . 'web', 'partner', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($columns['partner_picture']))
			$this->db_utils->add_column(PREFIX . 'web', 'partner_picture', array('type' => 'string', 'length' => 255, 'default' => "''"));
		if (!isset($columns['privileged_partner']))
			$this->db_utils->add_column(PREFIX . 'web', 'privileged_partner', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		
		if (isset($columns['idcat']))
		{
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'web DROP KEY `idcat`');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'web ADD KEY `id_category` (`id_category`)');
		}
		if ((isset($columns['title']) && !$columns['title']['key']) || !isset($columns['name']))
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'web ADD FULLTEXT KEY `title` (`name`)');
		if ((isset($columns['contents']) && !$columns['contents']['key']) || !isset($columns['contents']))
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'web ADD FULLTEXT KEY `contents` (`contents`)');
		if ((isset($columns['short_contents']) && !$columns['short_contents']['key']) || !isset($columns['short_contents']))
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'web ADD FULLTEXT KEY `short_contents` (`short_contents`)');
		
		$result = $this->querier->select_rows(PREFIX . 'web', array('id', 'name'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'web', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'author_user_id' => 1
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function update_cats_table()
	{
		$this->querier->inject('RENAME TABLE ' . PREFIX . 'web_cat' . ' TO ' . PREFIX . 'web_cats');
		
		$columns = $this->db_utils->desc_table(PREFIX . 'web_cats');
		
		$rows_change = array(
			'name' => 'name VARCHAR(255)',
			'contents' => 'description TEXT',
			'icon' => 'image VARCHAR(255)',
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE ' . PREFIX . 'web_cats CHANGE ' . $old_name . ' ' . $new_name);
		}
		
		if (isset($columns['class']))
			$this->db_utils->drop_column(PREFIX . 'web_cats', 'class');
		if (isset($columns['aprob']))
			$this->db_utils->drop_column(PREFIX . 'web_cats', 'aprob');
		if (isset($columns['secure']))
			$this->db_utils->drop_column(PREFIX . 'web_cats', 'secure');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX . 'web_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['c_order']))
			$this->db_utils->add_column(PREFIX . 'web_cats', 'c_order', array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['special_authorizations']))
			$this->db_utils->add_column(PREFIX . 'web_cats', 'special_authorizations', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		if (!isset($columns['auth']))
			$this->db_utils->add_column(PREFIX . 'web_cats', 'auth', array('type' => 'text', 'length' => 65000));
		if (!isset($columns['id_parent']))
			$this->db_utils->add_column(PREFIX . 'web_cats', 'id_parent', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		
		$result = $this->querier->select_rows(PREFIX . 'web_cats', array('id', 'name', 'image'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'web_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'id_parent' => 0,
				'c_order' => 0,
				'special_authorizations' => 0,
				'image' => $row['image'] == 'web.png' ? '/web/web.png' : $row['image']
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_web.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_web_add.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_web_cat.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_web_config.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/count.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/web.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/web_begin.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/web_english.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/web_french.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_web_add.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_web_cat.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_web_config.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_web_management.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_web_management2.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/web.tpl'));
		$file->delete();
	}
}
?>