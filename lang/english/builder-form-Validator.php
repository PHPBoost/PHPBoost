<?php
/*##################################################
 *                        builder-form-Validator.php
 *                            -------------------
 *   begin                : December 20, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
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

 ####################################################
#                     English                       #
 ####################################################

$lang = array(
    'doesnt_match_regex' => 'The entered value does not fit the proper format',
	'doesnt_match_url_regex' => 'The entered value has to be a valid url',
	'doesnt_match_mail_regex' => 'The entered value has to be a valid mail',
    'doesnt_match_length_intervall' => 'The entered value does not fit the specified length',
    'doesnt_match_integer_intervall' => 'The entered value does not fit the specified interval (:lower_bound <= value <= :upper_bound)',
    'has_to_be_filled' => 'This field has to be filled',
	'captcha_validation_error' => 'The visual confirmation field has not been properly filled !',
	'validation_error' => 'Please, correct the form errors',
	'fields_must_be_equal' => 'Fields ":field1" and ":field2" must ne equal',
	'fields_must_not_be_equal' => 'Fields ":field1" and ":field2" must have different values'
);

?>