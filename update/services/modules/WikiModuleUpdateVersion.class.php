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
			
			$array_preg = array(
				'`&lt;h1 class="wiki_paragraph1" id="paragraph_([^"]+)"&gt;(.*)&lt;/h1&gt;`isuU',
				'`&lt;h2 class="wiki_paragraph2" id="paragraph_([^"]+)"&gt;(.*)&lt;/h2&gt;`isuU',
				'`&lt;h3 class="wiki_paragraph3" id="paragraph_([^"]+)"&gt;(.*)&lt;/h3&gt;`isuU',
				'`&lt;h4 class="wiki_paragraph4" id="paragraph_([^"]+)"&gt;(.*)&lt;/h4&gt;`isuU',
				'`&lt;h5 class="wiki_paragraph5" id="paragraph_([^"]+)"&gt;(.*)&lt;/h5&gt;`isuU',
				'`&lt;ol class="wiki_list_([^"]+)"&gt;&lt;li&gt;`isuU',
				'`<a href="#paragraph_`isuU'
			);

			$array_preg_replace = array(
				'<h2 class="formatter-title wiki-paragraph-2" id="paragraph-$1">$2</h2>',
				'<h3 class="formatter-title wiki-paragraph-3" id="paragraph-$1">$2</h3>',
				'<h4 class="formatter-title wiki-paragraph-4" id="paragraph-$1">$2</h4>',
				'<h5 class="formatter-title wiki-paragraph-5" id="paragraph-$1">$2</h5>',
				'<h6 class="formatter-title wiki-paragraph-6" id="paragraph-$1">$2</h6>',
				'<ol class="wiki-list wiki-list-$1"><li>',
				'<a href="#paragraph-'
			);
			
			$content = preg_replace($array_preg, $array_preg_replace, $parser->get_content());
			
			if ($content != $row['content'])
			{
				$this->querier->update(PREFIX . 'wiki_contents', array('content' => $content), 'WHERE id_contents=:id', array('id' => $row['id_contents']));
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
