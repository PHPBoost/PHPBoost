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
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
	}
	
	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$fieldset->add_field(new FormFieldFilePicker('upload_avatar', LangLoader::get_message('upload_avatar', 'main'),
			array('description' => LangLoader::get_message('upload_avatar_where', 'main'))
		));
		$fieldset->add_field(new FormFieldTextEditor('link_avatar', LangLoader::get_message('avatar_link', 'main'), '', 
			array('class' => 'text', 'maxlength' => 255, 'description' => LangLoader::get_message('avatar_link_where', 'main'))
		));
	}
	
	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$value = $member_extended_field->get_value();
		$image = !empty($value) ? '<img src="'. $value .'" alt="" title="" />' : LangLoader::get_message('no_avatar', 'main');
		$fieldset->add_field(new FormFieldFree('current_avatar', LangLoader::get_message('current_avatar', 'main'), $image));
		$fieldset->add_field(new FormFieldFilePicker('upload_avatar', LangLoader::get_message('upload_avatar', 'main'),
			array('description' => LangLoader::get_message('upload_avatar_where', 'main'))
		));
		$fieldset->add_field(new FormFieldTextEditor('link_avatar', LangLoader::get_message('avatar_link', 'main'), '', 
			array('class' => 'text', 'maxlength' => 255, 'description' => LangLoader::get_message('avatar_link_where', 'main'))
		));
		$fieldset->add_field(new FormFieldCheckbox('delete_avatar', LangLoader::get_message('avatar_del', 'main'), FormFieldCheckbox::UNCHECKED));
	}
	
	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();
		
		$user_accounts_config = UserAccountsConfig::load();
		$value = $member_extended_field->get_value();
		if (!is_file($value) || empty($value) && $user_accounts_config->is_default_avatar_enabled())
		{
			$avatar = '<img src="'. $user_accounts_config->get_default_avatar_name() .'" alt="" title="" />';
		}
		elseif (!empty($value))
		{
			$avatar = '<img src="'. $member_extended_field->get_value() .'" alt="" title="" />';
		}
		else
		{
			$value = LangLoader::get_message('no_avatar', 'main');
		}

		$fieldset->add_field(new FormFieldFree($member_extended_field->get_field_name(), $member_extended_field->get_name(), $avatar));
	}
	
	public function return_value(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		$delete = $form->get_value('delete_avatar');
		if ($delete)
		{
			$user_avatar_path = PersistenceContext::get_sql()->query("SELECT user_avatar FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . AppContext::get_user()->get_attribute('user_id') . "'", __LINE__, __FILE__);
			if (!empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9()_-])+\.([a-z]){3,4})`i', $user_avatar_path))
			{
				if (is_file($user_avatar_path))
				{
					@unlink($user_avatar_path);
				}
			}
			return '';
		}
		else
		{
			return $this->upload_avatar($form);
		}
	}
	
	private function upload_avatar($form)
	{
		if ($form->get_value('link_avatar'))
		{
			return $form->get_value('link_avatar');
		}
		elseif(UserAccountsConfig::load()->is_avatar_upload_enabled())
		{
			$user_accounts_config = UserAccountsConfig::load();
			$dir = '../images/avatars/';
			
			$avatar = $form->get_value('upload_avatar', 0);
			if ($user_accounts_config->is_avatar_auto_resizing_enabled() && !empty($avatar))
			{
				import('io/image/Image');
				import('io/image/ImageResizer');
				
				$name_image = $avatar->get_name();
				$image = new Image($avatar->get_temporary_filename());
				$resizer = new ImageResizer();
				$resizer->resize_with_max_values($image, $user_accounts_config->get_max_avatar_height(), $user_accounts_config->get_max_avatar_height(), $dir . $name_image);
				
				return $dir . $name_image;
				
				// TODO gestion des erreurs 
			}
			else
			{
				$Upload = new Upload($dir);
				if ($Upload->get_size() > 0)
				{
					$Upload->file('avatars', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', Upload::UNIQ_NAME, $user_accounts_config->get_max_avatar_weight() * 1024);
					
					$error = $Upload->check_img($user_accounts_config->get_max_avatar_width(), $user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
					if (!empty($error))
					// Error		
					
					return $dir . $Upload->get_filename();
				}
			}
			
		}
	}
}
?>