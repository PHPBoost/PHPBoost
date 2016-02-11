<?php
/*##################################################
 *                         FormFieldConstraintDisplayNameExists.class.php
 *                            -------------------
 *   begin                : July 120 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormFieldConstraintDisplayNameExists extends AbstractFormFieldConstraint
{
	private $user_id = 0;
	private $error_message;
 
	public function __construct($user_id = 0, $error_message = '')
	{
		if (!empty($user_id))
		{
			$this->user_id = $user_id;
		}
		
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('e_display_name_auth', 'errors');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}
 
	public function validate(FormField $field)
	{
		return !$this->display_name_exists($field);
	}
 
	public function display_name_exists(FormField $field)
	{
		if (!empty($this->user_id))
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER, 'WHERE display_name=:display_name AND user_id != :user_id', array(
				'display_name' => $field->get_value(), 
				'user_id' => $this->user_id
			));
		}
		else if ($field->get_value())
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER, 'WHERE display_name=:display_name', array(
				'display_name' => $field->get_value()
			));
		}
		return false;
	}
 
	public function get_js_validation(FormField $field)
	{
		return 'DisplayNameExistValidator(' . TextHelper::to_js_string($field->get_id()) .', '. $this->error_message . ', ' . $this->user_id . ')';
	}
}
 
?>