<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 03 10
 * @since       PHPBoost 3.0 - 2010 12 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MemberUserAvatarExtendedField extends AbstractMemberExtendedField
{
	private $user_accounts_config;
	private $authorized_pictures_extensions;

	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('regex', 'possible_values', 'default_value', 'description'));
		$this->set_name(LangLoader::get_message('user.field.type.avatar','user-lang'));
		$this->field_used_once = true;
		$this->field_used_phpboost_config = true;
		$this->user_accounts_config = UserAccountsConfig::load();
		$this->authorized_pictures_extensions = FileUploadConfig::load()->get_authorized_picture_extensions();
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		if (UserAccountsConfig::load()->is_avatar_upload_enabled())
		{
			$fieldset->add_field(new FormFieldFilePicker('upload_avatar', $this->lang['user.extended.field.upload.avatar'],
				array('description' => $this->lang['user.extended.field.upload.avatar.clue'], 'max_file_size' => $this->user_accounts_config->get_max_avatar_weight_in_kb(), 'authorized_extensions' => implode('|', $this->authorized_pictures_extensions)),
				array(new FormFieldConstraintPictureFile())
			));
		}
		$fieldset->add_field(new FormFieldTextEditor('link_avatar', $this->lang['user.extended.field.avatar.link'], '',
			array('description' => $this->lang['user.extended.field.avatar.link.clue'], 'required' =>(bool)$member_extended_field->get_required())
		));
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		$fieldset = $member_extended_field->get_fieldset();

		$value = $member_extended_field->get_value();
		$image = !empty($value) ? '<img src="'. Url::to_rel($value) .'" alt="' . $this->lang['user.extended.field.avatar'] . '" />' : $this->lang['user.extended.field.no.avatar'];
		$fieldset->add_field(new FormFieldFree('current_avatar', $this->lang['user.extended.field.current.avatar'], $image));

		if (UserAccountsConfig::load()->is_avatar_upload_enabled())
		{
			$fieldset->add_field(new FormFieldFilePicker('upload_avatar', $this->lang['user.extended.field.upload.avatar'],
				array('description' => $this->lang['user.extended.field.upload.avatar.clue'], 'max_file_size' => $this->user_accounts_config->get_max_avatar_weight_in_kb(), 'authorized_extensions' => implode('|', $this->authorized_pictures_extensions)),
				array(new FormFieldConstraintPictureFile())
			));
		}

		$fieldset->add_field(new FormFieldTextEditor('link_avatar', $this->lang['user.extended.field.avatar.link'], '',
			array('description' => $this->lang['user.extended.field.avatar.link.clue'], 'required' =>(bool)$member_extended_field->get_required())
		));
		$fieldset->add_field(new FormFieldCheckbox('delete_avatar', $this->lang['user.extended.field.avatar.delete'], FormFieldCheckbox::UNCHECKED));
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		$value = $member_extended_field->get_value();
		if (empty($value) && $this->user_accounts_config->is_default_avatar_enabled())
		{
			$avatar = '<img src="' . $this->user_accounts_config->get_default_avatar() . '" alt="' . $this->lang['user.extended.field.avatar'] . '" />';
		}
		elseif (!empty($value))
		{
			$avatar = '<img src="'. Url::to_rel($value) .'" alt="' . $this->lang['user.extended.field.avatar'] . '" />';
		}
		else
		{
			$avatar = $this->lang['user.extended.field.no.avatar'];
		}

		if (!empty($avatar))
		{
			return array('name' => $member_extended_field->get_name(), 'field_name' => $member_extended_field->get_field_name(), 'value' => $avatar);
		}
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
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

		if (!$this->authorized_pictures_extensions)
		{
			throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('warning.file.invalid.format', 'warning-lang'));
		}

		if ($form->get_value('link_avatar'))
		{
			if (preg_match('`([A-Za-z0-9()_-])+\.(' . implode('|', array_map('preg_quote', $this->authorized_pictures_extensions)) . ')+$`iu', $form->get_value('link_avatar')))
			{
				$image = new Image($form->get_value('link_avatar'));

				if ($image->get_width() > $this->user_accounts_config->get_max_avatar_width() || $image->get_height() > $this->user_accounts_config->get_max_avatar_height())
				{
					if ($this->user_accounts_config->is_avatar_auto_resizing_enabled())
					{
						$directory = '/images/avatars/' . Url::encode_rewrite($image->get_name() . '_' . $this->key_hash()) . '.' . $image->get_extension();

						$resizer = new ImageResizer();
						$resizer->resize_with_max_values($image, $this->user_accounts_config->get_max_avatar_width(), $this->user_accounts_config->get_max_avatar_height(), PATH_TO_ROOT . $directory);
						$this->delete_old_avatar($member_extended_field);
						return $directory;
					}
					throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('warning.file.max.dimension', 'warning-lang'));
				}
				$this->delete_old_avatar($member_extended_field);
				return $form->get_value('link_avatar');
			}
			else
			{
				throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('warning.file.invalid.format', 'warning-lang'));
			}
		}
		elseif (!empty($avatar))
		{
			if ($this->user_accounts_config->is_avatar_upload_enabled())
			{
				$dir = '/images/avatars/';

				if ($this->user_accounts_config->is_avatar_auto_resizing_enabled())
				{
					$image = new Image($avatar->get_temporary_filename());
					$resizer = new ImageResizer();

					$explode = explode('.', $avatar->get_name());
					$extension = array_pop($explode);

					if (!preg_match('`(' . implode('|', array_map('preg_quote', $this->authorized_pictures_extensions)) . ')+$`iu', $extension))
						throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('warning.file.max.dimension', 'warning-lang'));

					$explode = explode('.', $avatar->get_name());
					$name = $explode[0];

					$directory = $dir . Url::encode_rewrite($name . '_' . $this->key_hash()) . '.' . $extension;

					try {
						$resizer->resize_with_max_values($image, $this->user_accounts_config->get_max_avatar_width(), $this->user_accounts_config->get_max_avatar_height(), PATH_TO_ROOT . $directory);
						$this->delete_old_avatar($member_extended_field);
						return $directory;
					} catch (UnsupportedOperationException $e) {
						throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message('warning.file.max.dimension', 'warning-lang'));
					}
				}
				else
				{
					$Upload = new Upload(PATH_TO_ROOT . $dir);

					$Upload->file($form->get_html_id() . '_upload_avatar', '`\.(' . implode('|', array_map('preg_quote', $this->authorized_pictures_extensions)) . ')+$`iu', Upload::UNIQ_NAME, $this->user_accounts_config->get_max_avatar_weight_in_kb());
					$upload_error = $Upload->get_error();

					if (!empty($upload_error))
						throw new MemberExtendedFieldErrorsMessageException(LangLoader::get_message($upload_error, 'errors'));

					$error = $Upload->check_img($this->user_accounts_config->get_max_avatar_width(), $this->user_accounts_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
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

		if (!empty($value) && TextHelper::strpos($value, '/images/avatars/') !== false && @is_file(PATH_TO_ROOT . $value))
		{
			@unlink(PATH_TO_ROOT . $value);
		}
	}
}
?>
