<?php
/*##################################################
 *                       MembersKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 05, 2012
 *   copyright            : (C) 2012 Kévin MASSY
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

class MembersKernelUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('members');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$this->add_columns_member_extended_fields();
		$this->move_member_data();
		$this->rename_member_rows();
	}
	
	private function add_columns_member_extended_fields()
	{
		$columns = array('user_sex', 'user_born', 'user_location', 'user_website', 'user_job', 'user_entertainement', 'user_sign', 'user_biography', 'user_msn', 'user_yahoo', 'user_avatar');
		foreach ($columns as $column)
		{
			$this->db_utils->add_column(PREFIX .'member_extended_fields', $column, array('type' => 'text', 'length' => 65000, 'notnull' => 1));
		}
	}
	
	private function move_member_data()
	{
		$result = $this->querier->select_rows(PREFIX .'member', array('user_id', 'user_avatar', 'user_local', 'user_msn', 'user_yahoo', 'user_web', 'user_occupation', 'user_hobbies', 'user_desc', 'user_sex', 'user_born', 'user_sign'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX .'member_extended_fields', array(
				'user_sex' => $row['user_sex'], 
				'user_born' => $row['user_born'], 
				'user_location' => $row['user_local'], 
				'user_website' => $row['user_web'],  
				'user_job' => $row['user_occupation'],  
				'user_entertainement' => $row['user_hobbies'], 
				'user_sign' => $row['user_sign'], 
				'user_biography' => $row['user_desc'], 
				'user_msn' => $row['user_msn'], 
				'user_yahoo' => $row['user_yahoo'],  
				'user_avatar' => $row['user_avatar'], 
			), 'WHERE user_id=:user_id', array('user_id' => $row['user_id']));
		}
	}
	
	private function rename_member_rows()
	{
		$rows_change = array(
			'new_pass' => 'change_password_pass VARCHAR(64) NOT NULL DEFAULT  \'\'',
			'activ_pass' => 'approbation_pass VARCHAR(30) NOT NULL DEFAULT  \'0\''
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'member' .' CHANGE '. $old_name .' '. $new_name);
		}
	}
}
?>