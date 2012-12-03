<?php
/*##################################################
 *                       MembersKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 05, 2012
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
		$this->add_extended_fields();
		$this->move_member_data();
		$this->rename_member_rows();
		$this->drop_member_columns();
	}
	
	private function move_member_data()
	{
		$result = $this->querier->select_rows(PREFIX .'member', array('user_id', 'user_avatar', 'user_local', 'user_msn', 'user_yahoo', 'user_web', 'user_occupation', 'user_hobbies', 'user_desc', 'user_sex', 'user_born', 'user_sign'));
		while ($row = $result->fetch())
		{
			$date = new Date(DATE_FROM_STRING, TIMEZONE_SYSTEM, str_replace('-', '/', $row['user_born']), 'y/m/d');
			try {
				$this->querier->insert(PREFIX .'member_extended_fields', array(
					'user_id' => $row['user_id'],
					'user_sex' => $row['user_sex'], 
					'user_born' => $date->get_timestamp(), 
					'user_location' => $row['user_local'], 
					'user_website' => $row['user_web'],  
					'user_job' => $row['user_occupation'],  
					'user_entertainement' => $row['user_hobbies'], 
					'user_sign' => $row['user_sign'], 
					'user_biography' => $row['user_desc'], 
					'user_msn' => $row['user_msn'], 
					'user_yahoo' => $row['user_yahoo'],  
					'user_avatar' => $row['user_avatar'], 
				));
			} catch (Exception $e) {
				$this->querier->update(PREFIX .'member_extended_fields', array(
					'user_sex' => $row['user_sex'], 
					'user_born' => $date->get_timestamp(), 
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

			$this->querier->update(PREFIX .'member', array(
					'user_theme' => 'base',
					'user_editor' => $row['user_editor'] == 'bbcode' ? 'BBCode' : 'TinyMCE'
			), 'WHERE user_id=:user_id', array('user_id' => $row['user_id']));
		}
	}
	
	private function drop_member_columns()
	{
		$columns_drop = array('user_avatar', 'user_local', 'user_msn', 'user_yahoo', 'user_web', 'user_occupation', 'user_hobbies', 'user_desc', 'user_sex', 'user_born', 'user_sign');
		foreach ($columns_drop as $column_name)
		{
			$this->db_utils->drop_column(PREFIX .'member', $column_name);
		}
	}
	
	private function rename_member_rows()
	{
		$rows_change = array(
			'new_pass' => 'change_password_pass VARCHAR(64) NOT NULL DEFAULT \'\'',
			'activ_pass' => 'approbation_pass VARCHAR(30) NOT NULL DEFAULT \'0\''
		);
		
		foreach ($rows_change as $old_name => $new_name)
		{
			$this->querier->inject('ALTER TABLE '. PREFIX .'member' .' CHANGE '. $old_name .' '. $new_name);
		}
	}
	
	private function add_extended_fields()
	{
		$lang = LangLoader::get('admin-extended-fields-common');
		
		//Sex
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.sex']);
		$extended_field->set_field_name('user_sex');
		$extended_field->set_description($lang['field-install.sex-explain']);
		$extended_field->set_field_type('MemberUserSexExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Date Birth
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.date-birth']);
		$extended_field->set_field_name('user_born');
		$extended_field->set_description($lang['field-install.date-birth-explain']);
		$extended_field->set_field_type('MemberUserBornExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Location
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.location']);
		$extended_field->set_field_name('user_location');
		$extended_field->set_description($lang['field-install.location-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Website
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.website']);
		$extended_field->set_field_name('user_website');
		$extended_field->set_description($lang['field-install.website-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(5);
		ExtendedFieldsService::add($extended_field);
		
		//Job
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.job']);
		$extended_field->set_field_name('user_job');
		$extended_field->set_description($lang['field-install.job-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Entertainement
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.entertainement']);
		$extended_field->set_field_name('user_entertainement');
		$extended_field->set_description($lang['field-install.entertainement-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Sign
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.signing']);
		$extended_field->set_field_name('user_sign');
		$extended_field->set_description($lang['field-install.signing-explain']);
		$extended_field->set_field_type('MemberLongTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//Biography
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.biography']);
		$extended_field->set_field_name('user_biography');
		$extended_field->set_description($lang['field-install.biography-explain']);
		$extended_field->set_field_type('MemberLongTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
		
		//MSN
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.msn']);
		$extended_field->set_field_name('user_msn');
		$extended_field->set_description($lang['field-install.msn-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(4);
		ExtendedFieldsService::add($extended_field);
		
		//Yahoo
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.yahoo']);
		$extended_field->set_field_name('user_yahoo');
		$extended_field->set_description($lang['field-install.yahoo-explain']);
		$extended_field->set_field_type('MemberShortTextExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		$extended_field->set_regex(4);
		ExtendedFieldsService::add($extended_field);
		
		//Avatar
		$extended_field = new ExtendedField();
		$extended_field->set_name($lang['field-install.avatar']);
		$extended_field->set_field_name('user_avatar');
		$extended_field->set_description($lang['field-install.avatar-explain']);
		$extended_field->set_field_type('MemberUserAvatarExtendedField');
		$extended_field->set_is_required(false);
		$extended_field->set_display(true);
		$extended_field->set_is_freeze(true);
		ExtendedFieldsService::add($extended_field);
	}
}
?>