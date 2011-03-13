<?php
/*##################################################
 *                         FormFieldConstraintLoginExist.class.php
 *                            -------------------
 *   begin                : March 13, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @desc
 * @package {@package}
 */
class FormFieldConstraintLoginExist implements FormFieldConstraint
{
	private $error_message;
 
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('doesnt_match_regex', 'builder-form-Validator');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}
 
	public function validate(FormField $field)
	{
		$exist = $this->exist_login($field);
		return $exist;
	}
 
	public function exist_login(FormField $field)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE login = '" . $field->get_value() . "'") > 0 ? false : true;
	}
 
	public function get_js_validation(FormField $field)
	{
		return 'LoginExistValidator(' . TextHelper::to_js_string($field->get_id()) .', '. $this->error_message . ')';
	}
}
 
?>