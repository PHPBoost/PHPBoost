<?php
/*##################################################
 *                       MemberRegisterHelper.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
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
 
 class MemberRegisterHelper
 {
	public static function registeration($form)
	{
		$activation_key = self::generate_activation_key();

		// Write to DataBase
		self::register_member_request($form, $activation_key);
		
		self::regenerate_cache();
		
		self::send_confirmation($form, $activation_key);
	}
	
	private static function register_member_request($form, $activation_key)
	{
		$user_aprobation = UserAccountsConfig::load()->get_member_accounts_validation_method() == 0 ? 1 : 0;
		
		PersistenceContext::get_querier()->inject(
			"INSERT INTO " . DB_TABLE_MEMBER . " (login,password,level,user_groups,user_mail,user_show_mail,timestamp,user_pm,user_warning,last_connect,test_connect,activ_pass,new_pass,user_ban,user_aprob)
			VALUES (:login, :password, 0, '', :user_mail, :user_show_mail, :timestamp, 0, 0, :last_connect, 0, :activ_pass, '', 0, :user_aprob)", array(
                'login' => $form->get_value('login'),
                'password' => strhash($form->get_value('password')),
				'user_mail' => $form->get_value('mail'),
				'user_show_mail' => (string)$form->get_value('user_hide_mail'),
				'timestamp' => time(),
				'last_connect' => time(),
				'activ_pass' => $activation_key,
				'user_aprob' => $user_aprobation
		));
		
		MemberExtendedFieldsService::register_fields($form, self::last_user_id_registered());
	}
	
	private static function generate_activation_key()
	{
		return UserAccountsConfig::load()->get_member_accounts_validation_method() == 1 ? substr(strhash(uniqid(rand(), true)), 0, 15) : '';
	}
	
	private static function send_confirmation($form, $activation_key)
	{
		if (UserAccountsConfig::load()->get_member_accounts_validation_method() == 2)
		{
			// Administrator alert
			self::add_administrator_alert();
			$valid = sprintf(LangLoader::get_message('register_valid_email', 'main'), DispatchManager::get_url('/member', '/confirm/'.$activation_key.'')->absolute());
		}
		elseif (UserAccountsConfig::load()->get_member_accounts_validation_method() == 1)
		{
			$valid = LangLoader::get_message('register_valid_admin', 'main');
		}
		else
		{
			// Connect user
			self::connect_user($form);
			
			// redirect to confirm registered member
			
			$valid = '';
		}
		self::send_mail_confirmation($form, $valid);
		
	}
	
	private static function connect_user($form)
	{
		PersistenceContext::get_querier()->inject(
			"UPDATE " . DB_TABLE_MEMBER . " SET last_connect = :last_connect  WHERE user_id = :user_id"
			, array(
				'last_connect' => time(),
				'user_id' => self::last_user_id_registered()
		));
		AppContext::get_session()->start(self::last_user_id_registered(), strhash($form->get_value('password')), 0, SCRIPT, QUERY_STRING, LangLoader::get_message('register', 'main'), 1, true);
	}
	
	private static function send_mail_confirmation($form, $valid)
	{	
		$subject = sprintf(LangLoader::get_message('register_title_mail', 'main'), GeneralConfig::load()->get_site_name());
		$content = sprintf(LangLoader::get_message('register_mail', 'main'), $form->get_value('login'), GeneralConfig::load()->get_site_name(), GeneralConfig::load()->get_site_name(), stripslashes($form->get_value('login')), $form->get_value('password'), $valid, MailServiceConfig::load()->get_mail_signature());
		
		$mail = new Mail();
		$mail->add_recipient($form->get_value('mail'), $form->get_value('login'));
		$mail->set_sender(MailServiceConfig::load()get_default_mail_sender(), GeneralConfig::load()->get_site_name());
		$mail->set_subject($subject);
		$mail->set_content($content);
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
		return PersistenceContext::get_sql()->query("SELECT MAX(user_id) FROM " . DB_TABLE_MEMBER);
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
 }
 ?>