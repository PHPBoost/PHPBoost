<?php
/*##################################################
 *                       MemberSanction.class.php
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
class MemberSanction extends AdminController
{
	private $sql_querier;
	private $lang;
	private $user_id;
	private $send_mp = false;
	private $send_mail = false;
	private $content_to_send = '';
	private $duration_punish = 0;
	private $level_punish = 0;
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	private function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_send_mp(Bool $send_mp)
	{
		$this->send_mp = $send_mp;
	}
	
	public function set_send_mail(Bool $send_mail)
	{
		$this->send_mail = $send_mail;
	}
	
	private function get_send_mp()
	{
		return $this->send_mp;
	}
	
	private function get_send_mail()
	{
		return $this->send_mail;
	}
	
	public function set_content_to_send($content_to_send)
	{
		$this->content_to_send = $content_to_send;
	}
	
	private function get_content_to_send()
	{
		return $this->content_to_send;
	}
	
	public function set_punish_duration($punish_duration)
	{
		$this->punish_duration = $punish_duration;
	}
	
	private function get_punish_duration()
	{
		return time() + $this->punish_duration;
	}
	
	public function set_level_punish($level_punish)
	{
		$this->level_punish = $level_punish;
	}
	
	private function get_level_punish()
	{
		return $this->level_punish;
	}
	
	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_querier();
		$this->lang = LangLoader::get('main');
	}
	
	/*
	 * This function request settters set_user_id, set_punish_duration, set_content_to_send. Use setters set_send_mp and set_send_mail for sending message personel or mail to confirm the sanction
	 */
	public function put_read_only()
	{
		if ($this->verificate_user_id())
		{
			$this->sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_readonly = :user_readonly,
				WHERE user_id = :user_id"
				, array(
					'user_readonly' => $this->get_punish_duration(),
					'user_id' => $this->get_user_id()
			));
			
			if ($this->get_send_mp() && !empty($this->content_to_send))
			{
				$this->send_mp($this->lang['read_only_title'], $this->get_content_to_send());
			}
			if ($this->get_send_mail() && !empty($this->content_to_send))
			{
				$this->send_mail($this->lang['read_only_title'], $this->get_content_to_send());
			}
		}
	}
	
	/*
	 * This function request settters set_user_id, set_punish_duration, set_content_to_send. 
	 */
	public function put_banish()
	{
		if ($this->verificate_user_id())
		{
			$this->sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_ban = :user_ban,
				WHERE user_id = :user_id"
				, array(
					'user_ban' => $this->get_punish_duration(),
					'user_id' => $this->get_user_id()
			));
			
			if ($this->get_send_mail() && !empty($this->content_to_send))
			{
				$this->send_mail($this->lang['ban_title_mail'], $this->lang['ban_mail']);
			}
		}
	}
	
	/*
	 * This function request settters set_user_id, set_level_punish, set_content_to_send. Use setters set_send_mp and set_send_mail for sending message personel or mail to confirm the sanction
	 */
	public function put_warning()
	{
		if ($this->verificate_user_id())
		{
			$this->sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_warning = :user_warning,
				WHERE user_id = :user_id"
				, array(
					'user_warning' => $this->get_level_punish(),
					'user_id' => $this->get_user_id()
			));
			
			if ($this->get_level_punish() == 100)
			{
				$this->sql_querier->inject("DELETE " . DB_TABLE_SESSIONS . " WHERE user_id = :user_id", array('user_id' => $this->get_user_id()));

				$this->send_mail($this->lang['ban_title_mail'], $this->lang['ban_mail']);
			}
			else
			{
				if ($this->get_send_mp() && !empty($this->content_to_send))
				{
					$this->send_mp($this->lang['warning_title'], $this->get_content_to_send());
				}
				if ($this->get_send_mail() && !empty($this->content_to_send))
				{
					$this->send_mail($this->lang['warning_title'], $this->get_content_to_send());
				}
			}
		}
	}
	
	/*
	 * This function request settters set_user_id.
	 */
	public function put_quit_read_only()
	{
		if ($this->verificate_user_id())
		{
			$this->sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_readonly = :user_readonly,
				WHERE user_id = :user_id"
				, array(
					'user_readonly' => 0,
					'user_id' => $this->get_user_id()
			));
		}
	}
	
	/*
	 * This function request settters set_user_id.
	 */
	public function put_quit_banish()
	{
		if ($this->verificate_user_id())
		{
			$this->sql_querier->inject(
				"UPDATE " . DB_TABLE_MEMBER . " SET 
				user_ban = :user_ban,
				WHERE user_id = :user_id"
				, array(
					'user_ban' => 0,
					'user_id' => $this->get_user_id()
			));
				
			$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_warning'), "WHERE user_id = '" . $this->get_user_id() . "'");
			if ($row['user_warning'] == 100)
			{
				$this->sql_querier->inject("UPDATE " . DB_TABLE_MEMBER . " SET user_warning = 90 WHERE user_id = :user_id", array('user_id' => $this->get_user_id()));
			}
		}
	}
	
	private function send_mp($title, $content)
	{
		PrivateMsg::start_conversation($this->get_user_id(), addslashes($title), $content, '-1', PrivateMsg::SYSTEM_PM);
	}
	
	private function send_mail($title, $content)
	{
		AppContext::get_mail_service()->send_from_properties($this->mail_member(), addslashes($title), sprintf(addslashes($content), GeneralConfig::load()->get_site_name(), addslashes(MailServiceConfig::load()->get_mail_signature())));
	}
	
	private function verificate_user_id()
	{
		return $this->sql_querier->count(DB_TABLE_MEMBER, "WHERE user_id = '" . $this->get_user_id() . "'") > 0 ? true : false;
	}
	
	private function mail_member()
	{
		$row = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('user_mail'), "WHERE user_id = '" . $this->get_user_id() . "'");
		return $row['user_mail'];
	}
}

?>