<?php
/*##################################################
 *                       MemberSanctionManager.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

  /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @desc This class is responsible a punish member
 * @package {@package}
 */
class MemberSanctionManager extends AdminController
{
	private static $sql_querier;
	private static $lang;
	const NO_SEND_CONFIRMATION;
	const SEND_MAIL;
	const SEND_MP;
	const SEND_MP_AND_MP;
	
	public function __construct()
	{
		self::$sql_querier = PersistenceContext::get_querier();
		self::$lang = LangLoader::get('main');
	}
	
	/*
	 * This function request settters set_user_id, set_punish_duration, set_content_to_send. Use setters set_send_mp and set_send_mail for sending message personel or mail to confirm the sanction
	 */
	public function remove_write_permissions($user_id, $punish_duration, $send_confirmation = SEND_MP, $content_to_send = '')
	{
		if ($this->verificate_user_id($user_id))
		{
			self::$sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_readonly = :user_readonly,
				WHERE user_id = :user_id"
				, array(
					'user_readonly' => $punish_duration,
					'user_id' => $user_id
			));
			
			if ($send_confirmation == SEND_MP || $send_confirmation == SEND_MP_AND_MP && !empty($content_to_send))
			{
				self::send_mp($user_id, self::$lang['read_only_title'], $content_to_send);
			}
			if ($send_confirmation == SEND_MAIL || $send_confirmation == SEND_MP_AND_MP && !empty($content_to_send))
			{
				self::send_mail(self::$lang['read_only_title'], $content_to_send);
			}
		}
	}
	
	/*
	 * This function request settters set_user_id, set_punish_duration, set_content_to_send.
	 */
	public function banish($user_id, $punish_duration, $send_confirmation = SEND_MAIL, $content_to_send = '')
	{
		if ($this->verificate_user_id($user_id))
		{
			self::$sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_ban = :user_ban,
				WHERE user_id = :user_id"
				, array(
					'user_ban' => $punish_duration,
					'user_id' => $user_id
			));
			
			if ($send_confirmation == SEND_MAIL || $send_confirmation == SEND_MP_AND_MP && !empty($content_to_send))
			{
				$content = !empty($content_to_send) ? $content_to_send : self::$lang['ban_mail'];
				self::send_mail(self::$lang['ban_title_mail'], $content);
			}
		}
	}
	
	/*
	 * This function request settters set_user_id, set_level_punish, set_content_to_send. Use setters set_send_mp and set_send_mail for sending message personel or mail to confirm the sanction
	 */
	public function caution($user_id, $level_punish, $send_confirmation = SEND_MP, $content_to_send = '')
	{
		if ($this->verificate_user_id($user_id))
		{
			self::$sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_warning = :user_warning,
				WHERE user_id = :user_id"
				, array(
					'user_warning' => $level_punish,
					'user_id' => $user_id
			));
			
			if ($level_punish == 100)
			{
				self::$sql_querier->inject("DELETE " . DB_TABLE_SESSIONS . " WHERE user_id = :user_id", array('user_id' => $user_id));

				self::send_mail($this->lang['ban_title_mail'], self::$lang['ban_mail']);
			}
			else
			{
				if ($send_confirmation == SEND_MP || $send_confirmation == SEND_MP_AND_MP && !empty($content_to_send))
				{
					self::send_mp($user_id, self::$lang['warning_title'], $content_to_send);
				}
				if ($send_confirmation == SEND_MAIL || $send_confirmation == SEND_MP_AND_MP && !empty($content_to_send))
				{
					self::send_mail(self::$lang['warning_title'], $content_to_send);
				}
			}
		}
	}
	
	/*
	 * This function request settters set_user_id.
	 */
	public function restore_write_permissions($user_id)
	{
		if ($this->verificate_user_id($user_id))
		{
			self::$sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_readonly = :user_readonly,
				WHERE user_id = :user_id"
				, array(
					'user_readonly' => 0,
					'user_id' => $user_id
			));
		}
	}
	
	/*
	 * This function request settters set_user_id.
	 */
	public function cancel_banishment($user_id)
	{
		if ($this->verificate_user_id($user_id))
		{
			self::$sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_ban = :user_ban,
				WHERE user_id = :user_id"
				, array(
					'user_ban' => 0,
					'user_id' => $user_id
			));
				
			$row = self::$sql_querier->select_single_row(DB_TABLE_MEMBER, array('user_warning'), "WHERE user_id = '" . $user_id . "'");
			if ($row['user_warning'] == 100)
			{
				self::$sql_querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET user_warning = 90 WHERE user_id = :user_id", array('user_id' => $user_id));
			}
		}
	}
	
	private static function send_mp($user_id, $title, $content)
	{
		PrivateMsg::start_conversation($user_id, addslashes($title), $content, '-1', PrivateMsg::SYSTEM_PM);
	}
	
	private static function send_mail($title, $content)
	{
		AppContext::get_mail_service()->send_from_properties(self::get_member_mail(), addslashes($title), sprintf(addslashes($content), GeneralConfig::load()->get_site_name(), addslashes(MailServiceConfig::load()->get_mail_signature())));
	}
	
	private static function verificate_user_id($user_id)
	{
		return self::$sql_querier->count(DB_TABLE_MEMBER, "WHERE user_id = '" . $user_id . "'") > 0 ? true : false;
	}
	
	private static function get_member_mail($user_id)
	{
		$row = self::$sql_querier->select_single_row(DB_TABLE_MEMBER, array('user_mail'), "WHERE user_id = '" . $user_id . "'");
		return $row['user_mail'];
	}
}

?>