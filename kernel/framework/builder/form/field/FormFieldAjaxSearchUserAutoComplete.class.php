<?php
/*##################################################
 *                          FormFieldAjaxSearchUserAutoComplete.class.php
 *                            -------------------
 *   begin                : June 26, 2013
 *   copyright            : (C) 2013 j1.seth
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
 * @desc This class represents a search user ajax completer field
 * @package {@package}
 */
class FormFieldAjaxSearchUserAutoComplete extends FormFieldAjaxCompleter
{
	protected $display_html_in_suggestions = true;
	
	// Input not filled on click on suggestions
	protected $preserve_input = 'true';
	
	// Show results if no suggestion
	protected $no_suggestion_notice = 'true';
	
	 /**
	 * @desc Constructs a FormFieldAjaxCompleter.
	 * It has these options in addition to the AbstractFormField ones:
	 * <ul>
	 * 	<li>size: the number of size of the field</li>
	 * 	<li>maxlength: the number of maxlength of the field</li>
	 *  <li>method: the string method send request : post or get</li>
	 *  <li>file: the string file url</li>
	 *  <li>parameter: the string parameter name variable send for request</li>
	 * </ul>
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, $field_options = array(), array $constraints = array())
	{
		$field_options['file'] = TPL_PATH_TO_ROOT . '/kernel/framework/ajax/dispatcher.php?url=/search_users_autocomplete&token='. AppContext::get_session()->get_token();
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}
}
?>