<?php
/*##################################################
 *                               MemberUserLangExtendedFieldType.class.php
 *                            -------------------
 *   begin                : December 09, 2010
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
 
class MemberUserLangExtendedField extends AbstractMemberExtendedField
{
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), UserAccountsConfig::load()->get_default_lang(),
			$this->list_langs()
		));	
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldSelectChoice($member_extended_field->get_field_name(), $member_extended_field->get_name(), UserAccountsConfig::load()->get_default_lang(),
			$this->list_langs()
		));	
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$field_name = $member_extended_field->get_field_name();
		return $form->get_value($field_name)->get_raw_value();
	}
	
	private function list_langs()
	{
		$array = array();
		$langs_cache = LangsCache::load();
		foreach($langs_cache->get_installed_langs() as $lang => $properties)
		{
			if ($properties['auth'] == -1)
			{
				$info_lang = load_ini_file('../lang/', $lang);

				$array[] = new FormFieldSelectChoiceOption($info_lang['name'], $lang);
			}
			
		}
		return $array;
	}
}
?>