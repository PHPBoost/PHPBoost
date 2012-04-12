<?php
/*##################################################
 *                       UserLostPasswordService.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserLostPasswordService
{
	const LOST_PASSWORD_BY_EMAIL = 'email';
	const LOST_PASSWORD_BY_LOGIN = 'login';
	
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	public static function change_password_pass_exists($change_password_pass)
	{
		return self::$querier->count(DB_TABLE_MEMBER, "WHERE change_password_pass = :change_password_pass",
		array('change_password_pass' => $change_password_pass)) > 0 ? true : false;
	}
	
	public static function connect_user($user_id, $password)
	{
		AppContext::get_session()->start($user_id, $password, 0, SCRIPT, QUERY_STRING, '', true);
	}
	
	public static function clear_activation_key($user_id)
	{
		self::$querier->update(DB_TABLE_MEMBER, array('change_password_pass' => ''), 'WHERE user_id = :id', array('id' => $user_id));
	}
	
	public static function send_mail($email, $subject, $content)
	{
		$mail = new Mail();
		$mail->add_recipient($email);
		$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), GeneralConfig::load()->get_site_name());
		$mail->set_subject($subject);
		$mail->set_content($content);
		AppContext::get_mail_service()->try_to_send($mail);
	}
	
	public static function update_change_password_pass($change_password_pass, $email)
	{
		self::$querier->update(DB_TABLE_MEMBER, array('change_password_pass' => $change_password_pass), 'WHERE user_mail = :email', array('email' => $email));
	}
	
	public static function get_email_by_login($login)
	{
		return self::$querier->get_column_value(DB_TABLE_MEMBER, 'user_mail', 'WHERE login = :login', array('login' => $login));
	}
}
?>