<?php
/**
 * This class represents a search user ajax completer field
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 4.0 - 2013 01 26
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldAjaxSearchUserAutoComplete extends FormFieldAjaxCompleter
{
	protected bool $display_html_in_suggestions = true;
	// Input not filled on click on suggestions
	protected string $preserve_input = 'true';
	// Show results if no suggestion
	protected string $no_suggestion_notice = 'true';

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
	 * @param mixed $value Default value
	 * @param array $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct(string $id, string $label, mixed $value, array $field_options = [], array $constraints = [])
	{
		$field_options['file'] = TPL_PATH_TO_ROOT . '/kernel/framework/ajax/dispatcher.php?url=/search_users_autocomplete&token='. AppContext::get_session()->get_token();
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}
}
?>
