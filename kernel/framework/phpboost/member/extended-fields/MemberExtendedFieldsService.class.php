<?php
/*##################################################
 *                               MemberExtendedFieldsService.class.php
 *                            -------------------
 *   begin                : December 10, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @desc This class is responsible for updated, displayed and registed of member extended fields.
 * @package {@package}
 */
class MemberExtendedFieldsService
{	

	/**
	 * @desc This function displayed fields form
	 * @param required object DisplayMemberExtendedField containing user_id and Template. If user is not registered, use object MemberExtendedField, and define user_id of null.
	 */
	public static function display_form_fields(MemberExtendedField $member_extended_field)
	{
		// TODO Change for a new function verification if the fields is activate
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
		
		if (count($extend_fields_cache) > 0)
		{
			$template = $member_extended_field->get_template();
			
			$fieldset = new FormFieldsetHTML('other', LangLoader::get_message('other', 'main'));
			$member_extended_field->set_fieldset($fieldset);
			$template->add_fieldset($fieldset);
			
			$user_id = $member_extended_field->get_user_id();
			if($user_id !== null)
			{
				self::display_create_form($member_extended_field);
			}
			else
			{
				self::display_update_form($member_extended_field);
			}
		}
	}

	/**
	 * @desc This function displayed fields profile
	 * @param required object DisplayMemberExtendedField containing user_id, Template and field_type.
	 */
	public static function display_profile_fields(MemberExtendedField $member_extended_field)
	{
		// TODO Change for a new function verification if the fields is activate
		$extend_fields_cache = ExtendFieldsCache::load()->get_extend_fields();

		if (count($extend_fields_cache) > 0)
		{
			$template = $member_extended_field->get_template();
			
			$fieldset = new FormFieldsetHTML('other', LangLoader::get_message('other', 'main'));
			$member_extended_field->set_fieldset($fieldset);
			$template->add_fieldset($fieldset);
			
			$user_id = $member_extended_field->get_user_id();
			if($user_id !== null)
			{
				$user_id = $display_extended_field->get_user_id();
				$result = PersistenceContext::get_sql()->query_while("SELECT exc.name, exc.contents, exc.field, exc.required, exc.field_name, exc.possible_values, exc.default_values, exc.auth, ex.*
				FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
				LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $user_id . "'
				WHERE exc.display = 1
				ORDER BY exc.class", __LINE__, __FILE__);
				while ($extended_field = PersistenceContext::get_sql()->fetch_assoc($result))
				{
					$display_extended_field->set_name($extended_field['name']);
					$display_extended_field->set_field_name($extended_field['field_name']);
					$display_extended_field->set_description($extended_field['contents']);
					$display_extended_field->set_field_type($extended_field['field']);
					$display_extended_field->set_possible_values($extended_field['possible_values']);
					$display_extended_field->set_default_values($extended_field['default_values']);
					$display_extended_field->set_required($extended_field['required']);
					
					if (AppContext::get_user()->check_auth($extended_field['auth'], ExtendedField::AUTHORIZATION))
					{
						MemberExtendedFieldsFactory::display_field_profile($member_extended_field);
					}
				}
				PersistenceContext::get_sql()->query_close($result);
			}
		}
	}

	/**
	 * @desc This function register fields
	 * @param required instance of HTMLForm and user id.
	 */
	public static function register_fields(HTMLForm $form, $user_id)
	{
		if(!empty($user_id))
		{
			$extended_fields_cache = ExtendFieldsCache::load()->get_extend_fields();
			
			// Not null fields
			if (count($extended_fields_cache) > 0)
			{
				$member_extended_fields_dao = new MemberExtendedFieldsDAO();
				foreach ($extended_fields_cache as $id => $extended_field)
				{
					if ($extended_field['display'] == 1 && AppContext::get_user()->check_auth($extended_field['auth'], ExtendedField::AUTHORIZATION))
					{
						$member_extended_field = new MemberExtendedField();
						
						$member_extended_field->set_field_type($extended_field['field']);
						$member_extended_field->set_field_name($extended_field['field_name']);
						$member_extended_field->set_required($extended_field['required']);
						$member_extended_field->set_regex_type($extended_field['regex']);
						$member_extended_field->set_regex($member_extended_field->rewrite_regex($extended_field['regex']));
						$member_extended_field->set_default_values($extended_field['default_values']);
						$member_extended_field->set_possible_values($extended_field['possible_values']);
						
						$value = MemberExtendedFieldsFactory::return_value($form, $member_extended_field);
						$value_rewrite = MemberExtendedFieldsFactory::rewrite($member_extended_field, $value);
						$member_extended_field->set_field_value($value_rewrite);
						MemberExtendedFieldsFactory::register($member_extended_field, $member_extended_fields_dao);
					}
				}
				$member_extended_fields_dao->get_request($user_id);
			}
		}
	}
	
	/**
	 * @desc This private function display form create
	 * @param required instance of MemberExtendedField.
	 */
	private static function display_create_form(MemberExtendedField $member_extended_field)
	{
		$extended_fields_cache = ExtendFieldsCache::load()->get_extend_fields();	
		foreach ($extended_fields_cache as $id => $extended_field)
		{
			if ($extended_field['display'] == 1)
			{
				$member_extended_field->set_name($extended_field['name']);
				$member_extended_field->set_field_name($extended_field['field_name']);
				$member_extended_field->set_description($extended_field['contents']);
				$member_extended_field->set_field_type($extended_field['field']);
				$member_extended_field->set_possible_values($extended_field['possible_values']);
				$member_extended_field->set_default_values($extended_field['default_values']);
				$member_extended_field->set_required($extended_field['required']);
				$member_extended_field->set_regex($extended_field['regex']);
				
				MemberExtendedFieldsFactory::display_field_create($member_extended_field);
			}
		}
	}
	
	/**
	 * @desc This private function display form update
	 * @param required instance of MemberExtendedField.
	 */
	private static function display_update_form(MemberExtendedField $member_extended_field)
	{
		$user_id = $member_extended_field->get_user_id();
		$result = PersistenceContext::get_sql()->query_while("SELECT exc.name, exc.contents, exc.field, exc.required, exc.field_name, exc.possible_values, exc.default_values, exc.auth, exc.regex, ex.*
		FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
		LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $user_id . "'
		WHERE exc.display = 1
		ORDER BY exc.class", __LINE__, __FILE__);
		while ($extended_field = PersistenceContext::get_sql()->fetch_assoc($result))
		{
			$member_extended_field->set_name($extended_field['name']);
			$member_extended_field->set_field_name($extended_field['field_name']);
			$member_extended_field->set_description($extended_field['contents']);
			$member_extended_field->set_field_type($extended_field['field']);
			$member_extended_field->set_possible_values($extended_field['possible_values']);
			$member_extended_field->set_default_values($extended_field['default_values']);
			$member_extended_field->set_required($extended_field['required']);
			$member_extended_field->set_regex($extended_field['regex']);

			MemberExtendedFieldsFactory::display_field_update($member_extended_field);
		}
		PersistenceContext::get_sql()->query_close($result);
	}
	
}
?>