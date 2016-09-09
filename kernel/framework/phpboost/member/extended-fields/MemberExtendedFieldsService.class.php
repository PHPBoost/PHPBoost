<?php
/*##################################################
 *                               MemberExtendedFieldsService.class.php
 *                            -------------------
 *   begin                : December 10, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

 /**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class is responsible for updated, displayed and registed of member extended fields.
 * @package {@package}
 */
class MemberExtendedFieldsService
{
	private $form;
	
	public function __construct(HTMLForm $form)
	{
		$this->form = $form;
	}
	
	/**
	 * @desc This function displayed fields form
	 * @param object $member_extended_field MemberExtendedField containing user_id and Template. If user is not registered, use object MemberExtendedField, and define user_id of null.
	 */
	public function display_form_fields($user_id = null)
	{
		$extended_fields_displayed = PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, 'WHERE display=1');
        if ($extended_fields_displayed)
		{
			$fieldset = new FormFieldsetHTML('other', LangLoader::get_message('other', 'main'));
			$this->form->add_fieldset($fieldset);

			if ($user_id == null)
			{
				$this->display_create_form($fieldset);
			}
			else
			{
				$this->display_update_form($fieldset, $user_id);
			}
		}
	}

	/**
	 * @desc This function displayed fields profile
	 * @param object $member_extended_field MemberExtendedField containing user_id, Template and field_type.
	 */
	public static function display_profile_fields(HTMLForm $form, $user_id)
	{
		$extended_fields_displayed = PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, 'WHERE display=1');
        if ($extended_fields_displayed)
		{
			$fieldset = new FormFieldsetHTML('other', LangLoader::get_message('other', 'main'));
			
			$nbr_field = 0;
			$result = PersistenceContext::get_querier()->select("SELECT exc.name, exc.description, exc.field_type, exc.required, exc.field_name, exc.possible_values, exc.default_value, exc.auth, exc.regex, ex.*
			FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " exc
			LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ex ON ex.user_id = :user_id
			WHERE exc.display = 1
			ORDER BY exc.position", array(
				'user_id' => $user_id
			));
			while ($extended_field = $result->fetch())
			{
				if (AppContext::get_current_user()->check_auth(unserialize($extended_field['auth']), ExtendedField::READ_PROFILE_AUTHORIZATION))
				{
					$value = !empty($extended_field[$extended_field['field_name']]) ? $extended_field[$extended_field['field_name']] : $extended_field['default_value'];
					$extended_field['value'] = $value;
				
					$member_extended_field = new MemberExtendedField();
					$member_extended_field->set_user_id($user_id);
					$member_extended_field->set_fieldset($fieldset);
					$member_extended_field->set_properties($extended_field);

					$member_extended_field->get_instance()->display_field_profile($member_extended_field);
					$nbr_field++;
				}
			}
			
			if ($nbr_field > 0)
			{
				$form->add_fieldset($fieldset);
			}
			
			$result->dispose();
		}
	}

	/**
	 * @desc This function register fields
	 * @param object $member_extended_field MemberExtendedField
	 */
	public function get_data($user_id)
	{
		$has_error = $error = false;
		$data = array();
		$extended_fields_displayed = PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, 'WHERE display=1');
		if ($extended_fields_displayed)
		{
			$extended_fields_cache = ExtendedFieldsCache::load()->get_extended_fields();
			foreach ($extended_fields_cache as $id => $extended_field)
			{
				if ($extended_field['display'] == 1 && AppContext::get_current_user()->check_auth($extended_field['auth'], ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
				{
					$extended_field['value'] = '';
					$member_extended_field = new MemberExtendedField();
					$member_extended_field->set_properties($extended_field);
					$member_extended_field->set_user_id($user_id);
					
					try {
						$data[$extended_field['field_name']] = $member_extended_field->get_instance()->get_data($this->form, $member_extended_field);
					} catch (MemberExtendedFieldErrorsMessageException $e) {
						$has_error = true;
						$error = $e->getMessage();
					}
				}
			}
		}
		
		if (!$has_error)
			return $data;
		else
			throw new MemberExtendedFieldErrorsMessageException($error);
	}
	
	/**
	 * @desc This private function display form create
	 * @param object $member_extended_field MemberExtendedField
	 */
	private function display_create_form(FormFieldset $fieldset)
	{
		$extended_fields_cache = ExtendedFieldsCache::load()->get_extended_fields();
		foreach ($extended_fields_cache as $id => $extended_field)
		{
			if ($extended_field['display'] == 1 && AppContext::get_current_user()->check_auth($extended_field['auth'], ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
			{
				$extended_field['value'] = '';
				$member_extended_field = new MemberExtendedField();
				$member_extended_field->set_fieldset($fieldset);
				$member_extended_field->set_properties($extended_field);
			
				$member_extended_field->get_instance()->display_field_create($member_extended_field);
			}
		}
	}
	
	/**
	 * @desc This private function display form update
	 * @param object $member_extended_field MemberExtendedField
	 */
	private function display_update_form(FormFieldset $fieldset, $user_id)
	{
		$result = PersistenceContext::get_querier()->select("SELECT exc.name, exc.description, exc.field_type, exc.required, exc.field_name, exc.possible_values, exc.default_value, exc.auth, exc.regex, ex.*
		FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ex ON ex.user_id = :user_id
		WHERE exc.display = 1
		ORDER BY exc.position", array(
			'user_id' => $user_id
		));
		while ($extended_field = $result->fetch())
		{
			if (AppContext::get_current_user()->check_auth(unserialize($extended_field['auth']), ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
			{
				$value = !empty($extended_field[$extended_field['field_name']]) ? $extended_field[$extended_field['field_name']] : $extended_field['default_value'];
				$extended_field['value'] = $value;
				$member_extended_field = new MemberExtendedField();
				$member_extended_field->set_user_id($user_id);
				$member_extended_field->set_fieldset($fieldset);
				$member_extended_field->set_properties($extended_field);
				
				$member_extended_field->get_instance()->display_field_update($member_extended_field);
			}
		}
		$result->dispose();
	}

	/**
	 * @desc This function deletes the extended fields of the user
	 * @param int $user_id id of the user.
	 */
	public static function delete_user_fields($user_id)
	{
		$result = PersistenceContext::get_querier()->select("SELECT exc.name, exc.description, exc.field_type, exc.required, exc.field_name, exc.possible_values, exc.default_value, exc.auth, exc.regex, ex.*
		FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ex ON ex.user_id = :user_id", array(
			'user_id' => $user_id
		));
		while ($extended_field = $result->fetch())
		{
			$value = !empty($extended_field[$extended_field['field_name']]) ? $extended_field[$extended_field['field_name']] : $extended_field['default_value'];
			$extended_field['value'] = $value;
			$member_extended_field = new MemberExtendedField();
			$member_extended_field->set_user_id($user_id);
			$member_extended_field->set_properties($extended_field);
			$member_extended_field->get_instance()->delete_field($member_extended_field);
		}
	}
	
	/**
	 * @desc This public function return the data sent by the user depending field_name
	 */
	public static function return_field_member($field_name, $user_id, $rewrite = false)
	{
		if ($rewrite)
		{
			$field_name = 'f_' . $field_name;
		}

		try {
			return PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER_EXTENDED_FIELDS, $field_name, 'WHERE user_id =:user_id', array('user_id' => $user_id));
		} catch (RowNotFoundException $e) {
			return '';
		}
	}
}
?>