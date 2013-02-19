<?php
/*##################################################
 *                               MemberUserAvatarExtendedFieldType.class.php
 *                            -------------------
 *   begin                : December 09, 2010
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
 
class MemberUserAvatarExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_values', 'description'));
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
		$image = !empty($value) ? '<img src="'. Url::to_rel($value) .'" alt="" title="" />' : $this->lang['no_avatar'];
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
			$avatar = '<img src="'. Url::to_rel('/templates/'. get_utheme() .'/images/'. $user_accounts_config->get_default_avatar_name()) .'" alt="" title="" />';
		}
		elseif (!empty($value))
		{
			$avatar = '<img src="'. Url::to_rel($value) .'" alt="" title="" />';
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
			$this->delete_old_avatar($member_extended_field);
			return '';
		}
		else
		{
			return $this->upload_avatar($form, $member_extended_field);
		}
	}
	
	private function upload_avatar($form, $member_extended_field)
	{
		$avatar = $form->get_value('upload_avatar');
		$user_accounts_config = UserAccountsConfig::load();
		if ($form->get_value('link_avatar'))
		{
			if (preg_match('`([A-Za-z0-9()_-])+\.(jpg|gif|png)+$`i', $form->get_value('link_avatar')))
			{
				$image = new Image($form->get_value('link_avatar'));
	
				if ($image->get_width() > $user_accounts_config->get_max_avatar_width() || $image->get_height() > $user_accounts_config->get_max_avatar_height())
				{
					if ($user_accounts_config->is_avatar_auto_resizing_enabled())
					{
						$directory = '/images/avatars/' . Url::encode_rewrite($image->get_name() . '_' . $this->key_hash()) . '.' . $image->get_extension();
						
						$resizer = new ImageResizer();
						$resizer->resize_with_max_values($image, $user_accounts_config->get_max_avatar_height(), $user_accounts_config->get_max_avatar_height(), PATH_TO_ROOT . $directory);
						$this->delete_old_avatar($member_extended_field);
						return $directory;
					}
					throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('e_upload_max_dimension', 'errors'));
				}
				$this->delete_old_avatar($member_extended_field);
				return $form->get_value('link_avatar');
			}
			else
			{
				throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('e_upload_invalid_format', 'errors'));
			}
		}
		elseif (!empty($avatar))
		{
			if(UserAccountsConfig::load()->is_avatar_upload_enabled())
			{
				$dir = '/images/avatars/';
			
				if ($user_accounts_config->is_avatar_auto_resizing_enabled())
				{			
					$image = new Image($avatar->get_temporary_filename());
					$resizer = new ImageResizer();
			
					$explode = explode('.', $avatar->get_name());
					$extension = array_pop($explode);
			
					$explode = explode('.', $avatar->get_name());
					$name = $explode[0];
			
					$directory = $dir . Url::encode_rewrite($name . '_' . $this->key_hash()) . '.' . $extension;
			
					try {
						$resizer->resize_with_max_values($image, $user_accounts_config->get_max_avatar_height(), $user_accounts_config->get_max_avatar_height(), PATH_TO_ROOT . $directory);
						$this->delete_old_avatar($member_extended_field);
						return $directory;
					} catch (MimeTypeNotSupportedException $e) {
						throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('e_upload_invalid_format', 'errors'));
					}
				}
				else
				{
					$Upload = new Upload(PATH_TO_ROOT . $dir);
			
					$Upload->file($form->get_html_id() . '_upload_avatar', '`([A-Za-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', Upload::UNIQ_NAME, $user_accounts_config->get_max_avatar_weight() * 1024);
					$upload_error = $Upload->get_error();
					if (!empty($upload_error))
						throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message($upload_error, 'errors'));
			
					$error = $Upload->check_img($user_accounts_config->get_max_avatar_width(), $user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
					if (!empty($error))
						throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message($error, 'errors'));
					else
					{
						$this->delete_old_avatar($member_extended_field);
						return $dir . $Upload->get_filename();
					}
				}
			}
		}
		else
		{
			return MemberExtendedFieldsService::return_field_member($member_extended_field->get_field_name(), $member_extended_field->get_user_id());
		}
	}
	
	private function key_hash()
	{
		return KeyGenerator::generate_key(5);
	}
	
	private function delete_old_avatar($member_extended_field)
	{
		$value = MemberExtendedFieldsService::return_field_member($member_extended_field->get_field_name(), $member_extended_field->get_user_id());

		if (!empty($value) && strpos($value, '/images/avatars/') !== false && is_file(PATH_TO_ROOT . $value))
		{
			@unlink(PATH_TO_ROOT . $value);
		}
	}
}
?>