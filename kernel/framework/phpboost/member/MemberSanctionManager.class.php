<?php
/**
 * This class is responsible a punish member
 * @package     PHPBoost
 * @subpackage  Member
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 05
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class MemberSanctionManager
{
	private static $db_querier;
	private static $lang;
	const NO_SEND_CONFIRMATION = 'no_send_confirmation';
	const SEND_MAIL = 'send_mail';
	const SEND_MP = 'send_mp';
	const SEND_MP_AND_MAIL = 'send_mp_and_mail';

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
		self::$lang = LangLoader::get('main');
	}

	/*
	 * This function request settters set_user_id, set_punish_duration, set_content_to_send. Use setters set_send_mp and set_send_mail for sending message personel or mail to confirm the sanction
	 */
	public static function remove_write_permissions($user_id, $punish_duration, $send_confirmation = self::SEND_MP, $content_to_send = '')
	{
		if (self::verificate_user_id($user_id))
		{
			self::$db_querier->update(DB_TABLE_MEMBER, array('delay_readonly' => $punish_duration), 'WHERE user_id = :user_id', array('user_id' => $user_id));

			if ($send_confirmation == self::SEND_MP || $send_confirmation == self::SEND_MP_AND_MAIL && !empty($content_to_send))
			{
				self::send_mp($user_id, self::$lang['read_only_title'], $content_to_send);
			}
			if ($send_confirmation == self::SEND_MAIL || $send_confirmation == self::SEND_MP_AND_MAIL && !empty($content_to_send))
			{
				self::send_mail($user_id, self::$lang['read_only_title'], $content_to_send);
			}
		}
	}

	/*
	 * This function request settters set_user_id, set_punish_duration, set_content_to_send.
	 */
	public static function banish($user_id, $punish_duration, $send_confirmation = self::SEND_MAIL, $content_to_send = '')
	{
		if (self::verificate_user_id($user_id))
		{
			self::$db_querier->update(DB_TABLE_MEMBER, array('delay_banned' => $punish_duration), 'WHERE user_id = :user_id', array('user_id' => $user_id));

			self::$db_querier->delete(DB_TABLE_SESSIONS, 'WHERE user_id=:user_id', array('user_id' => $user_id));
			self::$db_querier->update(DB_TABLE_MEMBER, array('autoconnect_key' => ''), 'WHERE user_id=:user_id', array('user_id' => $user_id));

			if ($send_confirmation == self::SEND_MAIL)
			{
				$content = !empty($content_to_send) ? $content_to_send : self::$lang['ban_mail'];
				self::send_mail($user_id, self::$lang['ban_title_mail'], $content);
			}
		}
	}

	/*
	 * This function request settters set_user_id, set_level_punish, set_content_to_send. Use setters set_send_mp and set_send_mail for sending message personel or mail to confirm the sanction
	 */
	public static function caution($user_id, $level_punish, $send_confirmation = self::SEND_MP, $content_to_send = '')
	{
		if (self::verificate_user_id($user_id))
		{
			self::$db_querier->update(DB_TABLE_MEMBER, array('warning_percentage' => $level_punish), 'WHERE user_id = :user_id', array('user_id' => $user_id));

			if ($level_punish == 100)
			{
				self::$db_querier->delete(DB_TABLE_SESSIONS, 'WHERE user_id=:user_id', array('user_id' => $user_id));
				self::$db_querier->update(DB_TABLE_MEMBER, array('autoconnect_key' => ''), 'WHERE user_id=:user_id', array('user_id' => $user_id));

				self::send_mail($user_id, self::$lang['ban_title_mail'], self::$lang['ban_mail']);
			}
			else
			{
				if ($send_confirmation == self::SEND_MP || $send_confirmation == self::SEND_MP_AND_MAIL && !empty($content_to_send))
				{
					self::send_mp($user_id, self::$lang['warning_title'], $content_to_send);
				}
				if ($send_confirmation == self::SEND_MAIL || $send_confirmation == self::SEND_MP_AND_MAIL && !empty($content_to_send))
				{
					self::send_mail($user_id, self::$lang['warning_title'], $content_to_send);
				}
			}
		}
	}

	/*
	 * This function request settters set_user_id.
	 */
	public static function cancel_caution($user_id)
	{
		if (self::verificate_user_id($user_id))
		{
			self::$db_querier->update(DB_TABLE_MEMBER, array('warning_percentage' => 0), 'WHERE user_id = :user_id', array('user_id' => $user_id));
		}
	}

	/*
	 * This function request settters set_user_id.
	 */
	public static function restore_write_permissions($user_id)
	{
		if (self::verificate_user_id($user_id))
		{
			self::$db_querier->update(DB_TABLE_MEMBER, array('delay_readonly' => 0), 'WHERE user_id = :user_id', array('user_id' => $user_id));
		}
	}

	/*
	 * This function request settters set_user_id.
	 */
	public static function cancel_banishment($user_id)
	{
		if (self::verificate_user_id($user_id))
		{
			self::$db_querier->update(DB_TABLE_MEMBER, array('delay_banned' => 0), 'WHERE user_id = :user_id', array('user_id' => $user_id));

			$row = self::$db_querier->select_single_row(DB_TABLE_MEMBER, array('warning_percentage'), "WHERE user_id = '" . $user_id . "'");
			if ($row['warning_percentage'] == 100)
			{
				self::$db_querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET warning_percentage = 90 WHERE user_id = :user_id", array('user_id' => $user_id));
			}
		}
	}

	private static function send_mp($user_id, $title, $content)
	{
		PrivateMsg::start_conversation($user_id, addslashes($title), nl2br($content), '-1', PrivateMsg::SYSTEM_PM);
	}

	private static function send_mail($user_id, $title, $content)
	{
		AppContext::get_mail_service()->send_from_properties(self::get_member_mail($user_id), $title, sprintf($content, GeneralConfig::load()->get_site_name(), MailServiceConfig::load()->get_mail_signature()));
	}

	private static function verificate_user_id($user_id)
	{
		return self::$db_querier->count(DB_TABLE_MEMBER, "WHERE user_id = '" . $user_id . "'") > 0 ? true : false;
	}

	private static function get_member_mail($user_id)
	{
		return self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'email', 'WHERE user_id=:user_id', array('user_id' => $user_id));
	}
}
?>
