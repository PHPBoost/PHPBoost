<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 04 09
 * @since   	PHPBoost 4.0 - 2014 05 22
*/

class WikiModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('wiki');
		
		$this->delete_old_files_list = array('/phpboost/WikiNewContent.class.php');
	}

	public function execute()
	{
		parent::execute();
		if (ModulesManager::is_module_installed('wiki'))
		{
			$tables = $this->db_utils->list_tables(true);

			if (in_array(PREFIX . 'wiki_contents', $tables))
				$this->update_wiki_contents_table();

			$this->update_content_titles();
		}
	}

	private function update_wiki_contents_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'wiki_contents');

		$this->querier->inject('ALTER TABLE ' . PREFIX . 'wiki_contents CHANGE content content MEDIUMTEXT');

		if (!isset($columns['change_reason']))
			$this->db_utils->add_column(PREFIX . 'wiki_contents', 'change_reason', array('type' => 'text', 'length' => 100, 'notnull' => 0));
	}

	protected function update_content()
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
}
?>
