<?php
/*##################################################
 *                         FormFieldConstraintPHPBoostAuthLoginExists.class.php
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
class FormFieldConstraintPHPBoostAuthLoginExists extends AbstractFormFieldConstraint
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
			$error_message = LangLoader::get_message('e_pseudo_auth', 'errors');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}
 
	public function validate(FormField $field)
	{
		return !$this->login_exists($field);
	}
 
	public function login_exists(FormField $field)
	{
		if (!empty($this->user_id))
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE login=:login AND user_id != :user_id', array(
				'login' => $field->get_value(), 
				'user_id' => $this->user_id
			));
		}
		else if ($field->get_value())
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE login=:login', array(
				'login' => $field->get_value()
			));
		}
		return false;
	}
 
	public function get_js_validation(FormField $field)
	{
		return 'LoginExistValidator(' . TextHelper::to_js_string($field->get_id()) .', '. $this->error_message . ', ' . $this->user_id . ')';
	}
}
 
?>