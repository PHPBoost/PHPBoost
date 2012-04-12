<?php
/*##################################################
 *                       LangsConfigurationKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 26, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class LangsConfigurationKernelUpdateVersion extends KernelUpdateVersion
{
	public function __construct()
	{
		parent::__construct('langs');
	}

	public function execute()
	{
		$results = PersistenceContext::get_querier()->select_rows(PREFIX . 'lang', array('*'));
		foreach ($results as $row)
		{
			$this->insert_to_new_configuration($row['lang'], $this->build_authorizations($row['secure']), (bool)$row['activ']);
		}
		$this->drop_table();
	}
	
	private function build_authorizations($old_auth)
	{
		switch ($old_auth) {
			case -1:
				return array('r-1' => 1, 'r0' => 1, 'r1' => 1);
			break;
			case 0:
				return array('r0' => 1, 'r1' => 1);
			break;
			case 1:
				return array('r1' => 1);
			break;
			case 2:
				return array('r2' => 1);
			break;
			default:
				return array('r-1' => 1, 'r0' => 1, 'r1' => 1);
			break;
		}
	}
	
	private function insert_to_new_configuration($lang_id, $authorizations, $enable)
	{
		try {
			LangManager::install($lang_id, $authorizations, $enable);
		} catch (IOException $e) {
		}
	}
	
	private function drop_table()
	{
		PersistenceContext::get_dbms_utils()->drop(array(PREFIX . 'lang'));
	}
}
?>