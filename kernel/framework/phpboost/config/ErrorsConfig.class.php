<?php
/*##################################################
 *                      	 ErrorsConfig.class.php
 *                            -------------------
 *   begin                : December 12, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class ErrorsConfig extends AbstractConfigData
{
	const NOT_REQUIRED_LEVEL = 'not_required_level';
	const UNAUTHORIZED_POST = 'unauthorized_post';
	const REGISTERED_TO_POST = 'registered_to_post';
	const READ_ONLY = 'read_only';
	const UNEXISTED_PAGE ='unexisted_page';
	const UNEXISTED_MEMBER = 'unexisted_member';
	
	public function set_not_required_level($error)
	{
		$this->set_property(self::NOT_REQUIRED_LEVEL, $error);
	}
	
	public function get_not_required_level()
	{
		return $this->return_correct_lang_for_country(self::NOT_REQUIRED_LEVEL);
	}
	
	public function set_unauthorized_post($error)
	{
		$this->set_property(self::UNAUTHORIZED_POST, $error);
	}
	
	public function get_unauthorized_post()
	{
		return $this->return_correct_lang_for_country(self::UNAUTHORIZED_POST);
	}
	
	public function set_registered_to_post($error)
	{
		$this->set_property(self::REGISTERED_TO_POST, $error);
	}
	
	public function get_registered_to_post()
	{
		return $this->return_correct_lang_for_country(self::REGISTERED_TO_POST);
	}
	
	public function set_read_only($error)
	{
		$this->set_property(self::READ_ONLY, $error);
	}
	
	public function get_read_only()
	{
		return $this->return_correct_lang_for_country(self::READ_ONLY);
	}
	
	public function set_unexisted_page($error)
	{
		$this->set_property(self::UNEXISTED_PAGE, $error);
	}
	
	public function get_unexisted_page()
	{
		return $this->return_correct_lang_for_country(self::UNEXISTED_PAGE);
	}
	
	public function set_unexisted_member($error)
	{
		$this->set_property(self::UNEXISTED_MEMBER, $error);
	}
	
	public function get_unexisted_member()
	{
		return $this->return_correct_lang_for_country(self::UNEXISTED_MEMBER);
	}
	
	
	public function get_default_values()
	{
		return array(
			self::NOT_REQUIRED_LEVEL => LangLoader::get_message('e_auth', 'errors'),
			self::UNAUTHORIZED_POST => LangLoader::get_message('e_unauthorized', 'errors'),
			self::REGISTERED_TO_POST => LangLoader::get_message('e_auth_post', 'errors'),
			self::READ_ONLY => LangLoader::get_message('e_readonly', 'errors'),
			self::UNEXISTED_PAGE => LangLoader::get_message('e_unexist_page', 'errors'),
			self::UNEXISTED_MEMBER => LangLoader::get_message('e_unexist_member', 'errors'),
		);
	}
	

	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'errors');
	}

	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'errors');
	}
	
	private function return_correct_lang_for_country(Const $const)
	{
		if (AppContext::get_user()->get_attribute('user_lang') == UserAccountsConfig::load()->set_default_lang())
		{
			return $this->get_property($const);
		}
		else
		{
			$default = $this->get_default_values();
			return $default[$const];
		}
	}
}