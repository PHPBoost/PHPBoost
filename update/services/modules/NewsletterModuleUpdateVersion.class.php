<?php
/*##################################################
 *                       NewsletterModuleUpdateVersion.class.php
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

class NewsletterModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('newsletter');
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'newsletter_streams', $tables))
			$this->update_newsletter_streams_table();
		
		$this->delete_old_files();
	}
	
	private function update_newsletter_streams_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'newsletter_streams');
		
		if (isset($columns['picture']))
			$this->querier->inject('ALTER TABLE '. PREFIX .'newsletter_streams CHANGE picture image VARCHAR(255)');
		
		if (isset($columns['visible']))
			$this->db_utils->drop_column(PREFIX .'newsletter_streams', 'visible');
		
		if (!isset($columns['rewrited_name']))
			$this->db_utils->add_column(PREFIX .'newsletter_streams', 'rewrited_name', array('type' => 'string', 'length' => 250, 'default' => "''"));
		if (!isset($columns['c_order']))
			$this->db_utils->add_column(PREFIX .'newsletter_streams', 'c_order', array('type' => 'integer', 'length' => 11, 'unsigned' => 1, 'notnull' => 1, 'default' => 0));
		if (!isset($columns['id_parent']))
			$this->db_utils->add_column(PREFIX .'newsletter_streams', 'id_parent', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0));
		
		$i = 1;
		$result = $this->querier->select_rows(PREFIX .'newsletter_streams', array('id', 'name'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'newsletter_streams', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'c_order' => $i,
				'id_parent' => 0
			), 'WHERE id=:id', array('id' => $row['id']));
			$i++;
		}
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/controllers/AdminNewsletterAddStreamController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/controllers/AdminNewsletterDeleteStreamController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/controllers/AdminNewsletterEditStreamController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/controllers/AdminNewsletterStreamsListController.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/' . $this->module_id . '_common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/' . $this->module_id . '_common.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/phpboost/NewsletterModuleHomePage.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/phpboost/NewsletterStreamsCache.class.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/AdminNewsletterStreamsListController.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/index.php'));
		$file->delete();
		
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/ajax'));
		if ($folder->exists())
			$folder->delete();
		$folder = new Folder(Url::to_rel('/' . $this->module_id . '/templates/images'));
		if ($folder->exists())
			$folder->delete();
	}
}
?>