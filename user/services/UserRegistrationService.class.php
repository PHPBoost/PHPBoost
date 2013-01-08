<?php
/*##################################################
 *                       UserRegistrationService.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
 
class UserRegistrationService
{
	private static $lang;
	
	public static function __static()
	{
		self::$lang = LangLoader::get('user-common');
	}
	
 	public static function user_registration($login, $password, $level, $email, $locale, $timezone, $theme, $editor, $show_email = '1', $activation_key = '', $user_aprobation = '1')
 	{
 		return UserService::create($login, $password, $level, $email, $locale, $timezone, $theme, $editor, $show_email, $activation_key, $user_aprobation);
 	}
 	
	public static function connect_user($user_id, $password)
	{
		AppContext::get_session()->start($user_id, $password, 0, SCRIPT, QUERY_STRING, self::$lang['registration'], 1, true);
	}
	
	public static function send_email_confirmation($user_id, $email, $pseudo, $login, $password, $activation_key)
	{
		$user_accounts_config = UserAccountsConfig::load();
		$site_name = GeneralConfig::load()->get_site_name();
		$subject = StringVars::replace_vars(self::$lang['registration.subject-mail'], array('site_name' => $site_name));
		switch ($user_accounts_config->get_member_accounts_validation_method())
		{
			case UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION:
				$parameters = array(
					'pseudo' => $pseudo,
					'site_name' => $site_name,
					'login' => $login,
					'password' => $password,
					'accounts_validation_explain' => self::$lang['registration.email.automatic-validation'],
					'signature' => MailServiceConfig::load()->get_mail_signature()
				);
				$content = StringVars::replace_vars(self::$lang['registration.content-mail'], $parameters);
				self::send_email_user($email, $login, $subject, $content);
			break;
			case UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION:
				$parameters = array(
					'pseudo' => $pseudo,
					'site_name' => $site_name,
					'login' => $login,
					'password' => $password,
					'accounts_validation_explain' => 
						StringVars::replace_vars(
							self::$lang['registration.email.mail-validation'], 
							array('validation_link' => UserUrlBuilder::confirm_registration($activation_key)->absolute())
						),
					'signature' => MailServiceConfig::load()->get_mail_signature()
				);
				$content = StringVars::replace_vars(self::$lang['registration.content-mail'], $parameters);
				self::send_email_user($email, $login, $subject, $content);
			break;
			case UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION:
				self::add_administrator_alert($user_id);
				
				$parameters = array(
					'pseudo' => $pseudo,
					'site_name' => $site_name,
					'login' => $login,
					'password' => $password,
					'accounts_validation_explain' => self::$lang['registration.email.administrator-validation'],
					'signature' => MailServiceConfig::load()->get_mail_signature()
				);
				$content = StringVars::replace_vars(self::$lang['registration.content-mail'], $parameters);
				self::send_email_user($email, $login, $subject, $content);
			break;
		}
	}

	private static function send_email_user($email, $login, $subject, $content)
	{
		$mail = new Mail();
		$mail->add_recipient($email, $login);
		$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender());
		$mail->set_subject($subject);
		$mail->set_content($content);
		AppContext::get_mail_service()->try_to_send($mail);
	}
	
	private static function add_administrator_alert($user_id)
	{
		$alert = new AdministratorAlert();
		$alert->set_entitled(self::$lang['registration.pending-approval']);
		$alert->set_fixing_url(AdminMembersUrlBuilder::edit($user_id)->relative());
		$alert->set_priority(AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
		$alert->set_id_in_module($user_id);
		$alert->set_type('member_account_to_approbate');
		AdministratorAlertService::save_alert($alert);
	}
}
?>