<?php
/*##################################################
 *                       BugtrackerConstraintStatusChanged.class.php
 *                            -------------------
 *   begin                : February 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class BugtrackerConstraintStatusChanged extends AbstractFormFieldConstraint
{
	private $bug_id = 0;
	private $bug_status = Bug::NEW_BUG;
	private $error_message;
	
	public function __construct($bug_id = 0, $bug_status = '', $error_message = '')
	{
		if (!empty($bug_id))
		{
			$this->bug_id = $bug_id;
		}
		
		if (!empty($bug_status))
		{
			$this->bug_status = $bug_status;
		}
		
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('error.e_status_not_changed', 'common', 'bugtracker');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}
	
	public function validate(FormField $field)
	{
		return (!empty($this->bug_id) && $this->bug_status != $field->get_value()->get_raw_value());
	}
	
	public function get_js_validation(FormField $field)
	{
		return 'BugtrackerStatusChangedValidator(' . $this->error_message . ', ' . $this->bug_id . ', \'' . $this->bug_status . '\')';
	}
}
?>
