<?php
/*##################################################
 *                               MemberUserAvatarExtendedFieldType.class.php
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
 
class MemberUserAvatarExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_values'));
		$this->set_name(LangLoader::get_message('type.avatar','admin-extended-fields-common'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		if (UserAccountsConfig::load()->is_avatar_upload_enabled())
		{
			$fieldset->add_field(new FormFieldFilePicker('upload_avatar', $this->lang['upload_avatar'],
				array('description' => LangLoader::get_message('upload_avatar_where', 'main'))
			));
		}
		$fieldset->add_field(new FormFieldTextEditor('link_avatar', $this->lang['avatar_link'], '', 
			array('class' => 'text', 'maxlength' => 255, 'description' => $this->lang['avatar_link_where'], 'required' =>(bool)$member_extended_field->get_required())
		));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$value = $member_extended_field->get_value();
		$image = !empty($value) ? '<img src="'. $value .'" alt="" title="" />' : $this->lang['no_avatar'];
		$fieldset->add_field(new FormFieldFree('current_avatar', $this->lang['current_avatar'], $image));
		
		if (UserAccountsConfig::load()->is_avatar_upload_enabled())
		{
			$fieldset->add_field(new FormFieldFilePicker('upload_avatar', $this->lang['upload_avatar'],
				array('description' => $this->lang['upload_avatar_where'])
			));
		}
		
		$fieldset->add_field(new FormFieldTextEditor('link_avatar', $this->lang['avatar_link'], '', 
			array('class' => 'text', 'maxlength' => 255, 'description' => $this->lang['avatar_link_where'], 'required' =>(bool)$member_extended_field->get_required())
		));
		$fieldset->add_field(new FormFieldCheckbox('delete_avatar', $this->lang['avatar_del'], FormFieldCheckbox::UNCHECKED));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$user_accounts_config = UserAccountsConfig::load();
		$value = $member_extended_field->get_value();
		if (empty($value) && $user_accounts_config->is_default_avatar_enabled())
		{
			$avatar = '<img src="'. PATH_TO_ROOT .'/templates/'. get_utheme() .'/images/'. $user_accounts_config->get_default_avatar_name() .'" alt="" title="" />';
		}
		elseif (!empty($value))
		{
			$avatar = '<img src="'. $value .'" alt="" title="" />';
		}
		else
		{
			$avatar = LangLoader::get_message('no_avatar', 'main');
		}
		
		if (!empty($avatar))
		{
			$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $avatar));
		}
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$delete = $form->get_value('delete_avatar');
		if ($delete)
		{
			$value = MemberExtendedFieldsService::return_field_member($member_extended_field);
			if (!empty($value) && preg_match('`\.\./images/avatars/(([a-z0-9()_-])+\.([a-z]){3,4})`i', $value) && is_file($value))
			{
				@unlink($value);
			}
			return '';
		}
		else
		{
			return $this->upload_avatar($form, $member_extended_field);
		}
	}
	
	private function upload_avatar($form, $member_extended_field)
	{
		if ($form->get_value('link_avatar'))
		{
			return $form->get_value('link_avatar');
		}
		elseif(UserAccountsConfig::load()->is_avatar_upload_enabled())
		{
			$user_accounts_config = UserAccountsConfig::load();
			$dir = PATH_TO_ROOT .'/images/avatars/';
			
			$avatar = $form->get_value('upload_avatar', 0);
			$former_avatar = MemberExtendedFieldsService::return_field_member($member_extended_field);
			if ($former_avatar !== $avatar)
			{
				@unlink($former_avatar);
			}
			if ($user_accounts_config->is_avatar_auto_resizing_enabled() && !empty($avatar))
			{
				import('io/image/Image');
				import('io/image/ImageResizer');
				
				$image = new Image($avatar->get_temporary_filename());
				$resizer = new ImageResizer();
				
				$explode = explode('.', $avatar->get_name());
				$extension = array_pop($explode);
				
				$explode = explode('.', $avatar->get_name());
				$name = $explode[0];
				
				$directory = $dir . $name. '_' . $this->key_hash() . '.' . $extension;
				$resizer->resize_with_max_values($image, $user_accounts_config->get_max_avatar_height(), $user_accounts_config->get_max_avatar_height(), $directory);
				
				return $directory;
				
				// TODO gestion des erreurs 
			}
			else
			{
				$Upload = new Upload($dir);
				if ($Upload->get_size() > 0)
				{
					$Upload->file('upload_avatar', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', Upload::UNIQ_NAME, $user_accounts_config->get_max_avatar_weight() * 1024);
					
					$error = $Upload->check_img($user_accounts_config->get_max_avatar_width(), $user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
					if (!empty($error))
					// Error		
					
					return $dir . $Upload->get_filename();
				}
			}
			
		}
	}
	
	private function key_hash()
	{
		return KeyGenerator::generate_key(5);
	}
}
?>