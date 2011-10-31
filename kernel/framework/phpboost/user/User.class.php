<?php
/*##################################################
 *                               User.class.php
 *                            -------------------
 *   begin                : October 31, 2011
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
 * @package {@package}
 */
class User
{
	const MEMBER_LEVEL = 0;
	const MODERATOR_LEVEL = 1;
	const ADMIN_LEVEL = 2;

	protected $id = -1;
	protected $level = -1;
	protected $groups = array();

	protected $display_name;
	protected $email;
	protected $show_email = false;
	protected $unread_pm = 0;
	protected $timestamp;

	protected $warning_percentage = 0;
	protected $is_banned;
	protected $is_readonly;

	protected $locale;
	protected $theme;
	protected $timezone;
	protected $editor;
	
	public function __construct()
	{
		//Default values
		$user_accounts_config = UserAccountsConfig::load();
		$this->locale = $user_accounts_config->get_default_lang();
		$this->theme = $user_accounts_config->get_default_theme();
		$this->timezone = GeneralConfig::load()->get_site_timezone();
		$this->editor = ContentFormattingConfig::load()->get_default_editor();
	}

	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_level($level)
	{
		$this->level = $level;
	}
	
	public function get_level()
	{
		return $this->level;
	}
	
	public function is_admin()
	{
		return $this->level == self::ADMIN_LEVEL;
	}
	
	public function set_groups(Array $groups)
	{
		$this->groups = $groups;
	}
	
	public function get_groups()
	{
		return $this->groups;
	}
	
	public function set_display_name($display_name)
	{
		$this->display_name = $display_name;
	}
	
	public function get_display_name()
	{
		return $this->display_name;
	}
	
	public function set_email($email)
	{
		$this->email = $email;
	}
	
	public function get_email()
	{
		return $this->email;
	}
	
	public function set_show_email($show_email)
	{
		$this->show_email = $show_email;
	}
	
	public function get_show_email()
	{
		return (int) $this->show_email;
	}
	
	public function set_unread_pm($unread_pm)
	{
		$this->unread_pm = $unread_pm;
	}
	
	public function get_unread_pm()
	{
		return $this->unread_pm;
	}
	
	public function set_timestamp($timestamp)
	{
		$this->timestamp = $timestamp;
	}
	
	public function get_timestamp()
	{
		return $this->timestamp;
	}
	
	public function set_warning_percentage($warning_percentage)
	{
		$this->warning_percentage = $warning_percentage;
	}
	
	public function get_warning_percentage()
	{
		return $this->warning_percentage;
	}
	
	public function set_is_banned($is_banned)
	{
		$this->is_banned = $is_banned;
	}
	
	public function get_is_banned()
	{
		return $this->is_banned;
	}
	
	public function set_is_readonly($is_readonly)
	{
		$this->is_readonly = $is_readonly;
	}
	
	public function get_is_readonly()
	{
		return $this->is_readonly;
	}
	
	public function set_locale($locale)
	{
		$this->locale = $locale;
	}
	
	public function get_locale()
	{
		return $this->locale;
	}
	
	public function set_theme($theme)
	{
		$this->theme = $theme;
	}
	
	public function get_theme()
	{
		return $this->theme;
	}
	
	public function set_timezone($timezone)
	{
		$this->timezone = $timezone;
	}
	
	public function get_timezone()
	{
		return $this->timezone;
	}
	
	public function set_editor($editor)
	{
		$this->editor = $editor;
	}
	
	public function get_editor()
	{
		return $this->editor;
	}
}
?>