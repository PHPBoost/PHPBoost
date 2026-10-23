<?php
/**
 * This class manage single-line text fields.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 2.0 - 2009 04 28
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldTextEditor extends AbstractFormField
{
	protected string $type = 'text';
	protected int $size = 30;
	protected int $maxlength = 255;

	/**
	 * Constructs a FormFieldTextEditor.
	 * It has these options in addition to the AbstractFormField ones:
	 * <ul>
	 * 	<li>size: The size (width) of the HTML field</li>
	 * 	<li>maxlength: The maximum length for the field</li>
	 * </ul>
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param mixed $value Default value
	 * @param array $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct(string $id, string $label, mixed $value, array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-text');
	}

	public function display(): Template
	{
		$template = $this->get_template_to_use();

		$field = new FileTemplate('framework/builder/form/fieldelements/FormFieldTextEditor.tpl');

		$field->put_all([
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
			'C_PATTERN' => $this->has_pattern(),
			'PATTERN' => $this->pattern,
			'C_PLACEHOLDER' => $this->has_placeholder(),
			'PLACEHOLDER' => $this->placeholder
		]);

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $field->render()
		]);

		return $template;
	}

	protected function compute_options(array &$field_options): void
	{
		foreach ($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'size':
					$this->size = $value;
					unset($field_options['size']);
					break;
				case 'maxlength':
					$this->maxlength = $value;
					unset($field_options['maxlength']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	public function get_value(): mixed
	{
		return TextHelper::substr($this->value, 0, $this->maxlength);
	}

	protected function get_default_template(): FileTemplate
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
