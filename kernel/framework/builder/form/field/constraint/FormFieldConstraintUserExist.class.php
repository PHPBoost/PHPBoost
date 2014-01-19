<?php
/*##################################################
 *                         FormFieldConstraintUserExist.class.php
 *                            -------------------
 *   begin                : January 19, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormFieldConstraintUserExist extends AbstractFormFieldConstraint
{
	private $error_message;
 
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('e_unexist_member', 'errors');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}
 
	public function validate(FormField $field)
	{
		return $field->get_value() ? $this->user_exists($field) : true;
	}
 
	public function user_exists(FormField $field)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, 'WHERE login=:login', array(
			'login' => $field->get_value()
		)) > 0;
	}
 
	public function get_js_validation(FormField $field)
	{
		return 'UserExistValidator(' . TextHelper::to_js_string($field->get_id()) .', '. $this->error_message . ')';
	}
}
 
?>