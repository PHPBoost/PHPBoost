<?php
/**
 * The class FormFieldCheckBox represents a checkbox field in a form. It corresponds to a boolean.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 12
 * @since       PHPBoost 3.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldCheckbox extends AbstractFormField
{
	const CHECKED = true;
	const UNCHECKED = false;

	/**
	 * Constructs a FormFieldCheckbox.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param bool $value FormFieldCheckbox::CHECKED if it's checked by default or FormFieldCheckbox::UNCHECKED if not checked.
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value = self::UNCHECKED, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-checkbox');
	}

	/**
	 * {@inheritdoc}
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->put_all(array(
			'C_REQUIRED_AND_HAS_VALUE' => $this->is_required() && $this->get_value(),
			'C_CHECKED' => $this->is_checked()
		));

		return $template;
	}

	/**
	 * Tells whether the checkbox is checked
	 * @return bool
	 */
	public function is_checked()
	{
		return $this->get_value() == self::CHECKED;
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()) && $request->get_value($this->get_html_id()) == 'on')
			$this->set_value(1);
		else
			$this->set_value(0);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldCheckbox.tpl');
	}
}
?>
