<?php
/*##################################################
 *                         FormFieldConstraintFileExtension.class.php
 *                            -------------------
 *   begin                : November 17, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
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
class FormFieldConstraintFileExtension extends FormFieldConstraintRegex
{
	public function __construct($extensions, $error_message = '')
	{
		if (is_array($extensions))
			$extensions = implode('|', $extensions);
		
		if (empty($error_message))
		{
			$error_message = StringVars::replace_vars(LangLoader::get_message('form.doesnt_match_authorized_extensions_regex', 'status-messages-common'), array('extensions' => str_replace('|', ', ', $extensions)));
		}
		$this->set_validation_error_message($error_message);
		
		$regex = '/\.(' . $extensions . ')$/iu';
		
		parent::__construct(
			$regex, 
			$regex, 
			$error_message
		);
	}
}

?>