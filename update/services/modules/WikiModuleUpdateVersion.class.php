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
	
	public function __construct()
	{
		parent::__construct('wiki');
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function execute()
	{
		if (ModulesManager::is_module_installed('wiki'))
		{
			$this->update_content();
		}
		
		$this->delete_old_files();
	}
	
	public function update_content()
	{
		$unparser = new OldBBCodeUnparser();
		$parser = new BBCodeParser();
		
		$result = $this->querier->select('SELECT id_contents, content FROM ' . PREFIX . 'wiki_contents');
		
		$selected_rows = $result->get_rows_count();
		$updated_content = 0;
		
		while($row = $result->fetch())
		{
			$unparser->set_content($row['content']);
			$unparser->parse();
			$parser->set_content($unparser->get_content());
			$parser->parse();
			
			if ($parser->get_content() != $row['content'])
			{
				$this->querier->update(PREFIX . 'wiki_contents', array('content' => $parser->get_content()), 'WHERE id_contents=:id', array('id' => $row['id_contents']));
				$updated_content++;
			}
		}
		$result->dispose();
		
		$object = new UpdateServices('', false);
		$object->add_information_to_file('table ' . PREFIX . 'wiki_contents', ': ' . $updated_content . ' contents updated');
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/formatting/WikiBBCodeParser.class.php'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/formatting'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>
