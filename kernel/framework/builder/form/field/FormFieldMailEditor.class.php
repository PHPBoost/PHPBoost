<?php
/**
 * This class manage  a mail address.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 26
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldMailEditor extends FormFieldTextEditor
{
	protected $type = 'email';
	/**
	 * @var boolean
	 */
	private $multiple = false;

	/**
	 * Constructs a FormFieldMailEditor.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		if (isset($field_options['multiple']))
		{
			$simple_regex = AppContext::get_mail_service()->get_mail_checking_raw_regex();
			$constraints[] = new FormFieldConstraintRegex('`^' . $simple_regex . '(?:,' . $simple_regex . ')*$`iu');
		}
		else
		{
			$constraints[] = new FormFieldConstraintMailAddress();
			$constraints[] = new FormFieldConstraintForbiddenMailDomains();
		}
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-email');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$field = new FileTemplate('framework/builder/form/fieldelements/FormFieldMailEditor.tpl');

		$field->put_all(array(
			'SIZE' => $this->size,
			'MAX_LENGTH' => $this->maxlength,
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'TYPE' => $this->type,
			'VALUE' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_DISABLED' => $this->is_disabled(),
			'C_READONLY' => $this->is_readonly(),
			'C_MULTIPLE' => $this->is_multiple(),
			'C_PLACEHOLDER' => $this->has_placeholder(),
			'PLACEHOLDER' => $this->placeholder
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
				case 'multiple':
					$this->multiple = (bool)$value;
					unset($field_options['multiple']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	/**
	 * Tells whether the field is multiple
	 * @return true if it is, false otherwise
	 */
	public function is_multiple()
	{
		return $this->multiple;
	}

	/**
	 * Changes the fact that the field is multiple or not.
	 * @param bool $multiple true if it's multiple, false otherwise
	 */
	public function set_multiple($multiple)
	{
		$this->multiple = $multiple;
	}
}
?>
