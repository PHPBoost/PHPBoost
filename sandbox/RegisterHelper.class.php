<?php
/*##################################################
 *                       RegisterHelper.class.php
 *                            -------------------
 *   begin                : September 13, 2010
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
 
 class RegisterHelper
 {
	public static function registeration_valid($form)
	{
		$user_aprob = UserAccountsConfig::load()->get_member_accounts_validation_method() == 0 ? 1 : 0;
		$activ_mbr = UserAccountsConfig::load()->get_member_accounts_validation_method() == 1 ? substr(strhash(uniqid(rand(), true)), 0, 15) : ''; //clée d'activation!
					
		PersistenceContext::get_sql()->query_inject("
		INSERT INTO " . DB_TABLE_MEMBER . " (login,password,level,user_groups,user_lang,user_theme,user_mail,user_show_mail,user_editor,user_timezone,timestamp,user_avatar,user_msg,user_local,user_msn,user_yahoo,user_web,user_occupation,user_hobbies,user_desc,user_sex,user_born,user_sign,user_pm,user_warning,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob)
		VALUES (
			'" . $form->get_value('login') . "', 
			'" . strhash($form->get_value('password')) . "', 
			0, 
			'', 
			'" . $form->get_value('user_lang')->get_raw_value() . "', 
			'" . $form->get_value('user_theme')->get_raw_value() . "', 
			'" . $form->get_value('mail') . "', 
			'" . $form->get_value('user_hide_mail') . "', 
			'" . $form->get_value('user_editor')->get_raw_value() . "', 
			'" . $form->get_value('user_timezone')->get_raw_value() . "', 
			'" . time() . "', 
			'" . self::upload_avatar($form) . "',
			0, 
			'" . $form->get_value('user_local') . "', 
			'" . $form->get_value('user_msn') . "', 
			'" . $form->get_value('user_yahoo') . "', 
			'" . $form->get_value('user_web') . "', 
			'" . $form->get_value('user_occupation') . "', 
			'" . $form->get_value('user_hobbies') . "', 
			'', 
			'" . $form->get_value('user_sex')->get_raw_value() . "', 
			'" . $form->get_value('user_born')->format(DATE_TIMESTAMP) . "', 
			'" . $form->get_value('user_sign') . "', 
			0, 
			0, 
			'" . time() . "', 
			0, 
			'" . $activ_mbr . "', 
			'', 
			0, 
			'" . $user_aprob . "'
		)", __LINE__, __FILE__);
		
		self::regenerate_cache();
		
		self::register_confirmation($form, $activ_mbr);
	}
	
	private static function register_confirmation($form, $activ_mbr)
	{
		if (UserAccountsConfig::load()->get_member_accounts_validation_method() == 2)
		{
			// Administrator activation
			self::add_administrator_alert();
			$valid = sprintf(LangLoader::get_message('register_valid_email', 'main'), HOST . DIR . '/member/register.php?key=' . $activ_mbr);
		}
		elseif (UserAccountsConfig::load()->get_member_accounts_validation_method() == 1)
		{
			$valid = LangLoader::get_message('register_valid_admin', 'main');
		}
		else
		{
			// Connect user
			PersistenceContext::get_sql()->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET last_connect='" . time() . "' WHERE user_id = '" . self::last_user_id_registered() . "'", __LINE__, __FILE__);
			AppContext::get_session()->start(self::last_user_id_registered(), strhash($form->get_value('password')), 0, REWRITED_SCRIPT, '', LangLoader::get_message('register', 'main'), 1);
			$valid = '';
		}
		
		//Send Mail
		AppContext::get_mail_service()->send_from_properties($form->get_value('mail'), sprintf(LangLoader::get_message('register_title_mail', 'main'), GeneralConfig::load()->get_site_name()), sprintf(LangLoader::get_message('register_mail', 'main'), $form->get_value('login'), GeneralConfig::load()->get_site_name(), GeneralConfig::load()->get_site_name(), stripslashes($form->get_value('login')), $form->get_value('password'), $valid, MailServiceConfig::load()->get_mail_signature()));
	}
	
	private static function regenerate_cache()
	{
		StatsCache::invalidate();
		
		// TODO depreciate, use PHPBoost function
		@unlink('../cache/sex.png');
		@unlink('../cache/theme.png');
	}
	
	private static function last_user_id_registered()
	{
		return PersistenceContext::get_sql()->insert_id("SELECT MAX(id) FROM " . DB_TABLE_MEMBER);
	}
	
	private static function add_administrator_alert()
	{
		$alert = new AdministratorAlert();
		$alert->set_entitled(LangLoader::get_message('member_registered_to_approbate', 'main'));
		$alert->set_fixing_url('admin/admin_members.php?id=' . self::last_user_id_registered());
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_id_in_module(self::last_user_id_registered());
		$alert->set_type('member_account_to_approbate');
		
		AdministratorAlertService::save_alert($alert);
	}
	
	private static function upload_avatar($form)
	{
		if ($form->get_value('user_avatar'))
		{
			return $form->get_value('user_avatar');
		}
		
		if (UserAccountsConfig::load()->is_avatar_upload_enabled())
		{
			$user_accounts_config = UserAccountsConfig::load();
			$dir = '../images/avatars/';
			
			$avatar = $form->get_value('avatar');
			if ($user_accounts_config->is_avatar_auto_resizing_enabled() && !empty($avatar))
			{
				import('io/image/Image');
				import('io/image/ImageResizer');
				
				$avatar = $form->get_value('avatar');
				$name_image = $avatar['name'];
				$image = new Image($avatar['tmp_name']);
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