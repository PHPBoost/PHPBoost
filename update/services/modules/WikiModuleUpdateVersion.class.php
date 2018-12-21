<?php
/*##################################################
 *                       WikiModuleUpdateVersion.class.php
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

class WikiModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('wiki');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		if (ModulesManager::is_module_installed('wiki'))
		{
			$tables = $this->db_utils->list_tables(true);
			
			if (in_array(PREFIX . 'wiki_contents', $tables))
				$this->update_wiki_contents_table();
			
			$this->update_content();
			
			$this->update_content_titles();
		}
		
		$this->delete_old_files();
	}
	
	private function update_wiki_contents_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'wiki_contents');
		
		$this->querier->inject('ALTER TABLE ' . PREFIX . 'wiki_contents CHANGE content content MEDIUMTEXT');
		
		if (!isset($columns['change_reason']))
			$this->db_utils->add_column(PREFIX . 'wiki_contents', 'change_reason', array('type' => 'text', 'length' => 100, 'notnull' => 0));
	}
	
	private function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'wiki_contents', 'content', 'id_contents');
	}
	
	private function update_content_titles()
	{
		$result = $this->querier->select('SELECT id_contents, content
			FROM ' . PREFIX . 'wiki_contents'
		);
		
		$selected_rows = $result->get_rows_count();
		$updated_content = 0;
		
		while($row = $result->fetch())
		{
			$array_preg = array();
			$array_preg_replace = array();
			
			for ($i = 2 ; $i <= 6 ; $i++)
			{
				array_push($array_preg, '`&lt;h' . $i . ' class="formatter-title wiki-paragraph-' . $i . '" id="([^"]+)"&gt;(.+)&lt;/h' . $i . '&gt;`isuU');
				array_push($array_preg_replace, '<h' . $i . ' class="formatter-title wiki-paragraph-' . $i . '" id="$1">$2</h' . $i . '>');
			}
			
			$new_content = preg_replace($array_preg, $array_preg_replace, $row['content']);
			
			if ($new_content != $row['content'])
			{
				$this->querier->update(PREFIX . 'wiki_contents', array('content' => $new_content), 'WHERE id_contents=:id', array('id' => $row['id_contents']));
				$updated_content++;
			}
		}
		$result->dispose();
		
		$object = new UpdateServices('', false);
		$object->add_information_to_file('table ' . PREFIX . 'wiki_contents', ': ' . $updated_content . ' contents titles updated');
	}
	
	private function delete_old_files()
	{
		$file = new File(PATH_TO_ROOT . '/' . $this->module_id . '/phpboost/WikiNewContent.class.php');
		$file->delete();
	}
}
?>
