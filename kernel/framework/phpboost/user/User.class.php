<?php
/*##################################################
 *                       User.class.php
 *                            -------------------
 *   begin                : March 31, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

define('RANK_TYPE', 1);
define('GROUP_TYPE', 2);
define('USER_TYPE', 3);

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class represente a user
 * @package {@package}
 */
class User
{
	const VISITOR_LEVEL = -1;
	const MEMBER_LEVEL = 0;
	const MODERATOR_LEVEL = 1;
	const ADMIN_LEVEL = 2;
	
	protected $id = -1;
	protected $pseudo = '';
	protected $level = -1;
	protected $groups = array();

	protected $email;
	protected $show_email = false;
	protected $approbation = false;
	
	protected $locale;
	protected $theme;
	protected $timezone;
	protected $editor;
	
	protected $delay_banned = 0;
	protected $delay_readonly = 0;
	protected $warning_percentage = 0;
	
	protected $approbation_pass = '';

	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_pseudo($pseudo)
	{
		$this->pseudo = $pseudo;
	}
	
	public function get_pseudo()
	{
		return $this->pseudo;
	}
	
	public function set_level($level)
	{
		$this->level = $level;
	}
	
	public function get_level()
	{
		if (!empty($this->level))
		{
			return $this->level;
		}
		return User::MEMBER_LEVEL;
	}
	
	public function is_admin()
	{
		return $this->level == self::ADMIN_LEVEL;
	}

	public function set_groups($groups)
	{
		if (!is_array($groups))
			$groups = explode('|', $groups);
		
		$this->groups = $groups;
	}
	
	public function get_groups()
	{
		return $this->groups;
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
		return $this->show_email;
	}
	
	public function set_approbation($approbation)
	{
		$this->approbation = $approbation;
	}
	
	public function get_approbation()
	{
		return $this->approbation;
	}
	
	public function set_locale($locale)
	{
		$this->locale = $locale;
	}
	
	public function get_locale()
	{
		if (!empty($this->locale))
		{
			return $this->locale;
		}
		return UserAccountsConfig::load()->get_default_lang();
	}
	
	public function set_theme($theme)
	{
		$this->theme = $theme;
	}
	
	public function get_theme()
	{
		if (!empty($this->theme))
		{
			return $this->theme;
		}
		return UserAccountsConfig::load()->get_default_theme();
	}
	
	public function set_timezone($timezone)
	{
		$this->timezone = $timezone;
	}
	
	public function get_timezone()
	{
		if ($this->timezone !== null)
		{
			return $this->timezone;
		}
		return GeneralConfig::load()->get_site_timezone();
	}
	
	public function set_editor($editor)
	{
		$this->editor = $editor;
	}
	
	public function get_editor()
	{
		if (!empty($this->editor))
		{
			return $this->editor;
		}
		return ContentFormattingConfig::load()->get_default_editor();
	}
	
	public function set_warning_percentage($warning_percentage)
	{
		$this->warning_percentage = $warning_percentage;
	}
	
	public function get_warning_percentage()
	{
		return $this->warning_percentage;
	}
	
	public function set_delay_banned($delay_banned)
	{
		$this->delay_banned = $delay_banned;
	}
	
	public function get_delay_banned()
	{
		return $this->delay_banned;
	}
	
	public function is_banned()
	{
		return (time() - $this->is_banned) <= 0 || $this->warning_percentage >= '100';
	}
	
	public function set_delay_readonly($delay_readonly)
	{
		$this->delay_readonly = $delay_readonly;
	}
	
	public function get_delay_readonly()
	{
		return $this->delay_readonly;
	}
	
	public function is_readonly()
	{
		return (time() - $this->delay_readonly) <= 0;
	}
	
	public function set_approbation_pass($approbation_pass)
	{
		$this->approbation_pass = $approbation_pass;
	}
	
	public function get_approbation_pass()
	{
		return $this->approbation_pass;
	}
	
	public static function get_group_color($user_groups, $level = 0, $is_array = false)
	{
		if (!$is_array)
			$user_groups = explode('|', $user_groups);
			
		$i = 0;
		$group_color = '';
		$groups_cache = GroupsCache::load();

		foreach ($user_groups as $idgroup) //Récupération du premier groupe.
		{
			if ($groups_cache->group_exists($idgroup))
			{
				$group = $groups_cache->get_group($idgroup);
				$group_color = (!empty($group['color']) && $level == 0) ? '#' . $group['color'] : '';
			}
		}
		return $group_color;
	}
	
	public function set_properties(array $properties)
	{
		$this->id = $properties['user_id'];
		$this->level = $properties['level'];
		
		$this->email = $properties['user_mail'];
		$this->show_email = $properties['user_show_mail'];
		$this->approbation = $properties['user_aprob'];
		$this->locale = $properties['user_lang'];
		$this->theme = $properties['user_theme'];
		$this->timezone = $properties['user_timezone'];
		$this->editor = $properties['user_editor'];
		
		$this->delay_banned = $properties['user_ban'];
		$this->delay_readonly = $properties['user_readonly'];
		$this->warning_percentage = $properties['user_warning'];
		
		$this->pseudo = $properties['login'];
		$this->set_groups($properties['user_groups']);
	}
}
?>