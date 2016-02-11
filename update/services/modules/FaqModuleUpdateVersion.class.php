<?php
/*##################################################
 *                       FaqModuleUpdateVersion.class.php
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

class FaqModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('faq');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'faq', $tables))
			$this->update_faq_table();
		if (in_array(PREFIX . 'faq_cats', $tables))
			$this->update_cats_table();
		
		$this->delete_old_files();
	}
	
	private function update_faq_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'faq');
		
		$rows_change = array(
			'idcat' => 'id_category INT(11)',
			'user_id' => 'author_user_id INT(11)',
			'timestamp' => 'creation_date INT(11)'
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			if (isset($columns[$old_name]))
				$this->querier->inject('ALTER TABLE ' . PREFIX . 'faq CHANGE ' . $old_name . ' ' . $new_name);
		}
		
		if (!isset($columns['approved']))
			$this->db_utils->add_column(PREFIX . 'faq', 'approved', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		
		if (isset($columns['idcat']))
		{
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'faq DROP KEY `question`');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'faq DROP KEY `answer`');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'faq ADD KEY `id_category` (`id_category`)');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'faq ADD FULLTEXT KEY `title` (`question`)');
			$this->querier->inject('ALTER TABLE ' . PREFIX . 'faq ADD FULLTEXT KEY `contents` (`answer`)');
		}
		
		$result = $this->querier->select_rows(PREFIX . 'faq', array('id'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'faq', array(
				'approved' => 1
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function update_cats_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'faq_cats');
		
		if (isset($columns['visible']))
			$this->db_utils->drop_column(PREFIX . 'faq_cats', 'visible');
		if (isset($columns['display_mode']))
			$this->db_utils->drop_column(PREFIX . 'faq_cats', 'display_mode');
		if (isset($columns['num_questions']))
			$this->db_utils->drop_column(PREFIX . 'faq_cats', 'num_questions');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX . 'faq_cats', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['special_authorizations']))
			$this->db_utils->add_column(PREFIX . 'faq_cats', 'special_authorizations', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		
		$result = $this->querier->select_rows(PREFIX . 'faq_cats', array('id', 'name', 'auth', 'image'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'faq_cats', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'special_authorizations' => (int)!empty($row['auth']),
				'image' => $row['image'] == 'faq.png' ? '/faq/faq.png' : $row['image']
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/FaqUrlBuilder.class.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/action.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_faq.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_faq_cats.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/faq.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/faq_begin.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/faq_bread_crumb.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/management.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/xmlhttprequest_cats.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/faq_config.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/faq_english.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/faq_config.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/faq_french.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/phpboost/FaqCats.class.php'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_faq.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_faq_cats.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_faq_questions.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/faq.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/faq_mini.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/management.tpl'));
		$file->delete();
		
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/search_result.tpl'));
		$file->delete();
	}
}
?>