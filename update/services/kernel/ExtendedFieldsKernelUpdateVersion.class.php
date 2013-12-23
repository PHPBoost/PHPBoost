<?php
/*##################################################
 *                       ExtendedFieldsKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 27, 2012
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

class ExtendedFieldsKernelUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	
	public function __construct()
	{
		parent::__construct('extended_fields');
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function execute()
	{
		$this->rename_tables();
		$this->change_rows();
		$this->convert_fields_type();
	}
	
	private function rename_tables()
	{
		$this->querier->inject('RENAME TABLE 
			'. PREFIX .'member_extend' .' TO '. PREFIX .'member_extended_fields' .', 
			'. PREFIX .'member_extend_cat' .' TO '. PREFIX .'member_extended_fields_list'
		);
	}
	
	private function change_rows()
	{
		$this->rename_rows();
		$this->add_authorizations_row();
	}
	
	private function rename_rows()
	{
		$rows_change = array(
			'class' => 'position INT(11) NOT NULL DEFAULT \'0\'',
			'contents' => 'description TEXT NOT NULL',
			'field' => 'field_type VARCHAR(255) NOT NULL DEFAULT  \'\''
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'member_extended_fields_list' .' CHANGE '. $old_name .' '. $new_name);
		}
	}
	
	private function add_authorizations_row()
	{
		$db_utils = PersistenceContext::get_dbms_utils();
		$db_utils->add_column(PREFIX .'member_extended_fields_list', 'auth', array('type' => 'text', 'length' => 65000));
		$db_utils->add_column(PREFIX .'member_extended_fields_list', 'freeze', array('type' => 'boolean', 'length' => 1, 'notnull' => 1, 'default' => 0));
		$this->querier->update(PREFIX .'member_extended_fields_list', array('auth' => serialize(array('r1' => 3, 'r0' => 3, 'r-1' => 1))), 'WHERE 1');
	}
	
	private function convert_fields_type()
	{
		$result = $this->querier->select_rows(PREFIX .'member_extended_fields_list', array('id', 'field_type'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX .'member_extended_fields_list', array(
					'field_type' => $this->convert_field_type($row['field_type']),
				), 'WHERE id=:id', array('id' => $row['id']));
		}
	}
	
	private function convert_field_type($old_field_type)
	{
		switch ($old_field_type) {
			case '0':
				return 'MemberHiddenExtendedField';
			break;
			case '1':
				return 'MemberShortTextExtendedField';
			break;
			case '2':
				return 'MemberLongTextExtendedField';
			break;
			case '3':
				return 'MemberSimpleSelectExtendedField';
			break;
			case '4':
				return 'MemberMultipleSelectExtendedField';
			break;
			case '5':
				return 'MemberSimpleChoiceExtendedField';
			break;
			case '6':
				return 'MemberMultipleChoiceExtendedField';
			break;
			default:
				return 'MemberHiddenExtendedField';
			break;
		}
	}
}
?>