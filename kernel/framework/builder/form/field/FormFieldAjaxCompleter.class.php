<?php
/**
 * This class represents a ajax completer field
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 03
 * @since       PHPBoost 3.0 - 2010 01 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldAjaxCompleter extends FormFieldTextEditor
{
	private $method = 'post';
	private $file;
	private $name_parameter = 'value';

	protected $display_html_in_suggestions = false;

	// Fill input on click on suggestions
	protected $preserve_input = 'false';

	// Show results if no suggestion
	protected $no_suggestion_notice = 'false';

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
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$field = new FileTemplate('framework/builder/form/fieldelements/FormFieldAjaxCompleter.tpl');

		if (empty($this->file))
		{
			throw new Exception('Add file options containing file url');
		}

		$field->put_all(array(
			'SIZE' => $this->size,
			'MAX_LENGTH' => $this->maxlength,
			'NAME' => $this->get_html_id(),
			'FILE' =>  $this->file,
			'METHOD' =>  $this->method,
			'NAME_PARAMETER' =>  $this->name_parameter,
			'C_DISPLAY_HTML_IN_SUGGESTIONS' => $this->display_html_in_suggestions,
			'PRESERVE_INPUT' => $this->preserve_input,
			'NO_SUGGESTION_NOTICE' => $this->no_suggestion_notice,
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled()
		));

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field->render()
		));

		return $template;
	}

	protected function compute_options(array &$field_options)
	{
		foreach ($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'method' :
					$this->method = $value;
					unset($field_options['method']);
					break;
				case 'file' :
					$this->file = $value;
					unset($field_options['file']);
					break;
				case 'name_parameter' :
					$this->name_parameter = $value;
					unset($field_options['name_parameter']);
					break;
				case 'display_html_in_suggestions' :
					$this->display_html_in_suggestions = (bool)$value;
					unset($field_options['display_html_in_suggestions']);
					break;
				case 'no_suggestion_notice' :
					$this->no_suggestion_notice = $value ? 'true' : 'false';
					unset($field_options['no_suggestion_notice']);
					break;
			}
		}
		parent::compute_options($field_options);
	}
}
?>
