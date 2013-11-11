<?php
/*##################################################
 *                         FormFieldConstraintAntiFlood.class.php
 *                            -------------------
 *   begin                : March 13, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormFieldConstraintAntiFlood extends AbstractFormFieldConstraint
{
	private $content_management_config;
	private $last_posted_timestamp;
	private $anti_flood_duration;
	
	/**
	 * @param string $last_posted_timestamp Timestamp
	 * @param string $anti_flood_duration seconde
	 * @param string $error_message
	 */
	public function __construct($last_posted_timestamp, $anti_flood_duration = '', $error_message = '')
	{
		$this->content_management_config = ContentManagementConfig::load();
		
		$this->last_posted_timestamp = $last_posted_timestamp;
		
		if (empty($anti_flood_duration))
		{
			$anti_flood_duration = $this->content_management_config->get_anti_flood_duration();
		}
		$this->anti_flood_duration = $anti_flood_duration;
		
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('e_flood', 'errors');
		}
		
		$this->set_validation_error_message($error_message);
	}
 
	public function validate(FormField $field)
	{
		if ($this->content_management_config->is_anti_flood_enabled())
		{
			return !$this->flooding($field);
		}
		return true;
	}
 
	public function get_js_validation(FormField $field)
	{
		return '';
	}
	
	public function flooding($field)
	{
		if ($this->last_posted_timestamp >= (time() - $this->anti_flood_duration))
		{
			return true;
		}
		return false;
	}
}
?>