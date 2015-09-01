<?php
/*##################################################
 *                       ShoutboxModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class ShoutboxModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('shoutbox');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'shoutbox', $tables))
		{
			$columns = $this->db_utils->desc_table(PREFIX . 'shoutbox');
			
			if (isset($columns['level']))
				$this->db_utils->drop_column(PREFIX . 'shoutbox', 'level');
		}
		
		$this->delete_old_files();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/english/' . $this->module_id . '_english.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/lang/french/' . $this->module_id . '_french.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/admin_shoutbox.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/shoutbox.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/shoutbox_begin.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/xmlhttprequest.php'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/admin_shoutbox_config.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/shoutbox.tpl'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/shoutbox_mini.tpl'));
		$file->delete();
	}
}
?>