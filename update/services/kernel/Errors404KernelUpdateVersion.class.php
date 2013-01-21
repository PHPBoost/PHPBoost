<?php
/*##################################################
 *                       Errors404KernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 11, 2012
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

class Errors404KernelUpdateVersion extends KernelUpdateVersion
{
	public function __construct()
	{
		parent::__construct('errors-404-table');
	}
	
	public function execute()
	{
		$this->create_errors_404_table();
	}
	
	private function create_errors_404_table()
	{
		$fields = array(
			'id' => array('type' => 'integer', 'length' => 11, 'autoincrement' => true, 'notnull' => 1),
			'requested_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'from_url' => array('type' => 'string', 'length' => 255, 'notnull' => 1),
			'times' => array('type' => 'integer', 'length' => 11, 'notnull' => 1)
		);
		$options = array(
			'primary' => array('id'),
			'indexes' => array(
				'unique' => array('type' => 'unique', 'fields' => array('requested_url', 'from_url'))
		));
		PersistenceContext::get_dbms_utils()->create_table(PREFIX . 'errors_404', $fields, $options);
	}
}
?>