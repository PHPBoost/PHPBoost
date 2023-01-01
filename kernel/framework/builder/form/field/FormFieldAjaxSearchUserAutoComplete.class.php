<?php
/**
 * This class represents a search user ajax completer field
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 06 03
 * @since       PHPBoost 4.0 - 2013 01 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldAjaxSearchUserAutoComplete extends FormFieldAjaxCompleter
{
	protected $display_html_in_suggestions = true;

	// Input not filled on click on suggestions
	protected $preserve_input = 'true';

	// Show results if no suggestion
	protected $no_suggestion_notice = 'true';

	/**
	 * Constructs a FormFieldAjaxCompleter.
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
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		$field_options['file'] = TPL_PATH_TO_ROOT . '/kernel/framework/ajax/dispatcher.php?url=/search_users_autocomplete&token='. AppContext::get_session()->get_token();
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}
}
?>
