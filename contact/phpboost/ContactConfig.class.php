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
	const TRACKING_NUMBER_ENABLED = 'tracking_number_enabled';
	const DATE_IN_TRACKING_NUMBER_ENABLED = 'date_in_tracking_number_enabled';
	const SENDER_ACKNOWLEDGMENT_ENABLED = 'sender_acknowledgment_enabled';
	const LAST_TRACKING_NUMBER = 'last_tracking_number';
	const FIELDS = 'fields';
	const AUTHORIZATIONS = 'authorizations';
	
	const LEFT = 'left';
	const TOP = 'top';
	const RIGHT = 'right';
	const BOTTOM = 'bottom';
	
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
	
	public function are_informations_enabled()
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
	
	public function are_informations_left()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::LEFT;
	}
	
	public function are_informations_top()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::TOP;
	}
	
	public function are_informations_right()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::RIGHT;
	}
	
	public function are_informations_bottom()
	{
		return $this->get_property(self::INFORMATIONS_POSITION) == self::BOTTOM;
	}
	
	public function enable_tracking_number()
	{
		$this->set_property(self::TRACKING_NUMBER_ENABLED, true);
	}
	
	public function disable_tracking_number()
	{
		$this->set_property(self::TRACKING_NUMBER_ENABLED, false);
	}
	
	public function is_tracking_number_enabled()
	{
		return $this->get_property(self::TRACKING_NUMBER_ENABLED);
	}
	
	public function enable_date_in_tracking_number()
	{
		$this->set_property(self::DATE_IN_TRACKING_NUMBER_ENABLED, true);
	}
	
	public function disable_date_in_tracking_number()
	{
		$this->set_property(self::DATE_IN_TRACKING_NUMBER_ENABLED, false);
	}
	
	public function is_date_in_tracking_number_enabled()
	{
		return $this->get_property(self::DATE_IN_TRACKING_NUMBER_ENABLED);
	}
	
	public function enable_sender_acknowledgment()
	{
		$this->set_property(self::SENDER_ACKNOWLEDGMENT_ENABLED, true);
	}
	
	public function disable_sender_acknowledgment()
	{
		$this->set_property(self::SENDER_ACKNOWLEDGMENT_ENABLED, false);
	}
	
	public function is_sender_acknowledgment_enabled()
	{
		return $this->get_property(self::SENDER_ACKNOWLEDGMENT_ENABLED);
	}
	
	public function get_last_tracking_number()
	{
		return $this->get_property(self::LAST_TRACKING_NUMBER);
	}
	
	public function set_last_tracking_number($value)
	{
		$this->set_property(self::LAST_TRACKING_NUMBER, $value);
	}
	
	public function get_fields()
	{
		return $this->get_property(self::FIELDS);
	}
	
	public function set_fields(Array $array)
	{
		$this->set_property(self::FIELDS, $array);
	}
	
	public function get_field_id_by_name($name)
	{
		$id = null;
		foreach (self::get_fields() as $key => $field)
		{
			if ($field['field_name'] == $name)
				$id = $key;
		}
		return $id;
	}
	
	/**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	 /**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	private function init_fields_array()
	{
		$fields = array();
		
		$lang = LangLoader::get('config', 'contact');
		
		$field = new ContactField();
		$field->set_name($lang['mail_address']);
		$field->set_field_name('f_sender_mail');
		$field->set_description($lang['mail_address_explain']);
		$field->set_field_type('ContactShortTextField');
		$field->set_regex(4);
		$field->readonly();
		$field->required();
		$field->not_deletable();
		
		$fields[1] = $field->get_properties();
		
		$field = new ContactField();
		$field->set_name($lang['contact_subject']);
		$field->set_field_name('f_subject');
		$field->set_description($lang['contact_subject_explain']);
		$field->set_field_type('ContactShortTextField');
		$field->not_deletable();
		
		$fields[2] = $field->get_properties();
		
		$field = new ContactField();
		$field->set_name($lang['contact_recipients']);
		$field->set_field_name('f_recipients');
		$field->set_field_type('ContactSimpleSelectField');
		$field->set_possible_values(array('admins' => array('is_default' => true, 'title' => $lang['contact_recipients_admins'], 'email' => '')));
		$field->not_deletable();
		$field->not_displayed();
		
		$fields[3] = $field->get_properties();
		
		$field = new ContactField();
		$field->set_name($lang['message']);
		$field->set_field_name('f_message');
		$field->set_field_type('ContactHalfLongTextField');
		$field->readonly();
		$field->required();
		$field->not_deletable();
		
		$fields[4] = $field->get_properties();
		
		return $fields;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::TITLE => LangLoader::get_message('contact_fieldset_title', 'config', 'contact'),
			self::INFORMATIONS_ENABLED => false,
			self::INFORMATIONS => '',
			self::INFORMATIONS_POSITION => self::TOP,
			self::TRACKING_NUMBER_ENABLED => false,
			self::DATE_IN_TRACKING_NUMBER_ENABLED => true,
			self::SENDER_ACKNOWLEDGMENT_ENABLED => false,
			self::LAST_TRACKING_NUMBER => 0,
			self::FIELDS => self::init_fields_array(),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 1, 'r1' => 1)
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
