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
	public static function send_email_confirmation($user_id, $email, $pseudo, $login, $password, $registration_pass)
	{
		$lang = LangLoader::get('user-common');
		$user_accounts_config = UserAccountsConfig::load();
		$general_config = GeneralConfig::load();
		$site_name = $general_config->get_site_name();
		$subject = StringVars::replace_vars($lang['registration.subject-mail'], array('site_name' => $site_name));

		switch ($user_accounts_config->get_member_accounts_validation_method())
		{
			case UserAccountsConfig::AUTOMATIC_USER_ACCOUNTS_VALIDATION:
				$parameters = array(
					'pseudo' => $pseudo,
					'site_name' => $site_name,
					'host' => $general_config->get_complete_site_url(),
					'login' => $login,
					'password' => $password,
					'accounts_validation_explain' => $lang['registration.email.automatic-validation'],
					'signature' => MailServiceConfig::load()->get_mail_signature()
				);
				$content = StringVars::replace_vars($lang['registration.content-mail'], $parameters);
				AppContext::get_mail_service()->send_from_properties($email, $subject, $content);
				break;
			case UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION:
				$parameters = array(
					'pseudo' => $pseudo,
					'site_name' => $site_name,
					'host' => $general_config->get_complete_site_url(),
					'login' => $login,
					'password' => $password,
					'accounts_validation_explain' => 
				StringVars::replace_vars(
				$lang['registration.email.mail-validation'],
				array('validation_link' => UserUrlBuilder::confirm_registration($registration_pass)->absolute())
				),
					'signature' => MailServiceConfig::load()->get_mail_signature()
				);
				$content = StringVars::replace_vars($lang['registration.content-mail'], $parameters);
				AppContext::get_mail_service()->send_from_properties($email, $subject, $content);
				break;
			case UserAccountsConfig::ADMINISTRATOR_USER_ACCOUNTS_VALIDATION:
				
				$alert = new AdministratorAlert();
				$alert->set_entitled($lang['registration.pending-approval']);
				$alert->set_fixing_url(UserUrlBuilder::edit_profile($user_id)->relative());
				$alert->set_priority(AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY);
				$alert->set_id_in_module($user_id);
				$alert->set_type('member_account_to_approbate');
				AdministratorAlertService::save_alert($alert);

				$parameters = array(
					'pseudo' => $pseudo,
					'site_name' => $site_name,
					'host' => $general_config->get_complete_site_url(),
					'login' => $login,
					'password' => $password,
					'accounts_validation_explain' => $lang['registration.email.administrator-validation'],
					'signature' => MailServiceConfig::load()->get_mail_signature()
				);
				$content = StringVars::replace_vars($lang['registration.content-mail'], $parameters);
				AppContext::get_mail_service()->send_from_properties($email, $subject, $content);
				break;
		}
	}
}
?>