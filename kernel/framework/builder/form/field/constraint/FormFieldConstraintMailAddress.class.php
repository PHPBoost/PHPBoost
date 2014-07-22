<?php
/*##################################################
 *                         FormFieldConstraintMailAddress.class.php
 *                            -------------------
 *   begin                : Februar 07, 2010
 *   copyright            : (C) 2010 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc
 * @package {@package}
 */
class FormFieldConstraintMailAddress extends FormFieldConstraintRegex
{
	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.doesnt_match_mail_regex', 'status-messages-common');
		}
		$this->set_validation_error_message($error_message);
		$mail_service = AppContext::get_mail_service();
		$regex = $mail_service->get_mail_checking_regex();
		
		parent::__construct(
			$regex, 
			$regex, 
			$error_message
		);
	}
}

?>