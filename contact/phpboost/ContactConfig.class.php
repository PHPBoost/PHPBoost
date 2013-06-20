<?php
/*##################################################
 *		                  ContactConfig.class.php
 *                            -------------------
 *   begin                : May 2, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * This class contains the configuration of the contact module.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class ContactConfig extends AbstractConfigData
{
	const TITLE = 'title';
	const INFORMATIONS_ENABLED = 'informations_enabled';
	const INFORMATIONS = 'informations';
	const INFORMATIONS_POSITION = 'informations_position';
	const CAPTCHA_ENABLED = 'captcha_enabled';
	const SUBJECT_FIELD_MANDATORY = 'subject_field_mandatory';
	const SUBJECT_FIELD_TYPE = 'subject_field_type';
	const SUBJECT_FIELD_POSSIBLE_VALUES = 'subject_field_possible_values';
	const SUBJECT_FIELD_DEFAULT_VALUE = 'subject_field_default_value';
	const CAPTCHA_DIFFICULTY_LEVEL = 'captcha_difficulty_level';
	const DISPLAY_SUBJECT_FIELD = 'display_subject_field';
	
	const LEFT = 'left';
	const TOP = 'top';
	const RIGHT = 'right';
	const BOTTOM = 'bottom';
	
	const TEXT_TYPE = 'text';
	const SELECT_TYPE = 'select';
	
	public function get_title()
	{
		return $this->get_property(self::TITLE);
	}
	
	public function set_title($value) 
	{
		$this->set_property(self::TITLE, $value);
	}
	
	public function enable_informations()
	{
		$this->set_property(self::INFORMATIONS_ENABLED, true);
	}
	
	public function disable_informations()
	{
		$this->set_property(self::INFORMATIONS_ENABLED, false);
	}
	
	public function is_informations_enabled()
	{
		return $this->get_property(self::INFORMATIONS_ENABLED);
	}
	
	public function get_informations()
	{
		return $this->get_property(self::INFORMATIONS);
	}
	
	public function set_informations($value) 
	{
		$this->set_property(self::INFORMATIONS, $value);
	}
	
	public function get_informations_position()
	{
		return $this->get_property(self::INFORMATIONS_POSITION);
	}
	
	public function set_informations_position($value) 
	{
		$this->set_property(self::INFORMATIONS_POSITION, $value);
	}
	
	public function is_informations_left()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::LEFT ? true : false;
	}
	
	public function is_informations_top()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::TOP ? true : false;
	}
	
	public function is_informations_right()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::RIGHT ? true : false;
	}
	
	public function is_informations_bottom()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::BOTTOM ? true : false;
	}
	
	public function subject_field_mandatory()
	{
		$this->set_property(self::SUBJECT_FIELD_MANDATORY, true);
	}
	
	public function not_subject_field_mandatory()
	{
		$this->set_property(self::SUBJECT_FIELD_MANDATORY, false);
	}
	
	public function is_subject_field_mandatory()
	{
		return $this->get_property(self::SUBJECT_FIELD_MANDATORY);
	}
	
	public function display_subject_field()
	{
		$this->set_property(self::DISPLAY_SUBJECT_FIELD, true);
	}
	
	public function not_display_subject_field()
	{
		$this->set_property(self::DISPLAY_SUBJECT_FIELD, false);
	}
	
	public function is_subject_field_displayed()
	{
		return $this->get_property(self::DISPLAY_SUBJECT_FIELD);
	}
	
	public function is_subject_field_text()
	{
		return $this->get_property(self::SUBJECT_FIELD_TYPE) == self::TEXT_TYPE ? true : false;
	}
	
	public function is_subject_field_select()
	{
		return $this->get_property(self::SUBJECT_FIELD_TYPE) == self::SELECT_TYPE ? true : false;
	}
	
	public function get_subject_field_type()
	{
		return $this->get_property(self::SUBJECT_FIELD_TYPE);
	}
	
	public function set_subject_field_type($value) 
	{
		$this->set_property(self::SUBJECT_FIELD_TYPE, $value);
	}
	
	public function get_subject_field_possible_values()
	{
		return $this->get_property(self::SUBJECT_FIELD_POSSIBLE_VALUES);
	}
	
	public function set_subject_field_possible_values($values) 
	{
		$this->set_property(self::SUBJECT_FIELD_POSSIBLE_VALUES, $values);
	}
	
	public function get_subject_field_default_value()
	{
		return $this->get_property(self::SUBJECT_FIELD_DEFAULT_VALUE);
	}
	
	public function set_subject_field_default_value($value) 
	{
		$this->set_property(self::SUBJECT_FIELD_DEFAULT_VALUE, $value);
	}
	
	public function enable_captcha()
	{
		$this->set_property(self::CAPTCHA_ENABLED, true);
	}
	
	public function disable_captcha()
	{
		$this->set_property(self::CAPTCHA_ENABLED, false);
	}
	
	public function is_captcha_enabled()
	{
		return $this->get_property(self::CAPTCHA_ENABLED);
	}
	
	public function get_captcha_difficulty_level()
	{
		return $this->get_property(self::CAPTCHA_DIFFICULTY_LEVEL);
	}
	
	public function set_captcha_difficulty_level($difficulty) 
	{
		$this->set_property(self::CAPTCHA_DIFFICULTY_LEVEL, $difficulty);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::TITLE => LangLoader::get_message('contact_title', 'contact_common', 'contact'),
			self::INFORMATIONS_ENABLED => false,
			self::INFORMATIONS => '',
			self::INFORMATIONS_POSITION => self::TOP,
			self::DISPLAY_SUBJECT_FIELD => true,
			self::SUBJECT_FIELD_MANDATORY => true,
			self::SUBJECT_FIELD_TYPE => self::TEXT_TYPE,
			self::SUBJECT_FIELD_POSSIBLE_VALUES => '',
			self::SUBJECT_FIELD_DEFAULT_VALUE => '',
			self::CAPTCHA_ENABLED => true,
			self::CAPTCHA_DIFFICULTY_LEVEL => 2
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ContactConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'contact', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('contact', self::load(), 'config');
	}
}
?>