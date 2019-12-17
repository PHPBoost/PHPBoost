<?php
/**
 * This class enables you to handle all the operations regarding forms. Indeed, you build a
 * form using object components (fieldsets, fields, buttons) and it's able to display, to retrieve
 * the posted values and also validate the entered data from constraints you define. The validation
 * is done in PHP when the form is received, but also in live thanks to Javascript (each field is
 * validated when it looses the focus and the whole form is validated when the user submits it).
 * @package     Builder
 * @subpackage  Form
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 03
 * @since       PHPBoost 3.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class HTMLForm implements HTMLElement
{
	const HTTP_METHOD_POST = 'post';
	const HTTP_METHOD_GET = 'get';

	const SMALL_CSS_CLASS = 'fieldset-mini';
	const NORMAL_CSS_CLASS = 'fieldset-content';

	private static $instance_id = 0;

	/**
	 * @var FormConstraint[]
	 */
	private $constraints = array();
	/**
	 * @var FormFieldset[]
	 */
	private $fieldsets = array();
	/**
	 * @var FormButton[]
	 */
	private $buttons = array();
	/**
	 * @var string
	 */
	private $html_id = '';
	/**
	 * @var string
	 */
	private $target = '';
	/**
	 * @var string
	 */
	private $method = self::HTTP_METHOD_POST;
	/**
	 * @var string
	 */
	private $css_class = self::NORMAL_CSS_CLASS;
	/**
	 * @var boolean
	 */
	private static $js_already_included = false;
	/**
	 * @var string[]
	 */
	private $validation_error_messages = array();
	/**
	 * @var Template
	 */
	private $template = null;

	private $enable_captcha_protection;

	protected $captcha_fieldset_css_class = '';

	protected $submit_button_class = 'FormFieldsetSubmit';

	/**
	 * Constructs a HTMLForm object
	 * @param string $html_id The HTML name of the form
	 * @param string $target The url where the form sends data
	 * @param bool $enable_captcha_protection True if the CAPTCHA is enabled
	 */
	public function __construct($html_id, $target = '', $enable_captcha_protection = true)
	{
		$this->enable_captcha_protection = $enable_captcha_protection;
		$this->set_html_id($html_id);
		$this->set_target($target);

		if ($this->enable_captcha_protection)
			$this->add_catpcha_protection(); //Add captcha protection for visitor

		self::$instance_id++;
	}

	private function add_catpcha_protection()
	{
		$captcha_protection_fieldset = new FormFieldsetHTML('captcha_fieldset');
		$captcha_protection_fieldset->add_field(new FormFieldCaptcha());
		$captcha_protection_fieldset->set_css_class($this->captcha_fieldset_css_class);
		$this->add_fieldset($captcha_protection_fieldset);
	}

	public function move_captcha_protection_in_last_position()
	{
		try {
			$this->fieldsets[] = $this->fieldsets[0];
			unset($this->fieldsets[0]);
		} catch (FormBuilderException $e) {
		}
	}

	/**
	 * Adds fieldset in the form
	 * @param FormFieldset The fieldset to add
	 */
	public function add_fieldset(FormFieldset $fieldset)
	{
		$fieldset->set_form_id($this->html_id);
		$this->fieldsets[] = $fieldset;
	}

	/**
	 * Adds a constraint on the form. This kind of constraints are rules regarding several fields.
	 * @param FormConstraint $constraint The constraint to add
	 */
	public function add_constraint(FormConstraint $constraint)
	{
		$this->constraints[] = $constraint;
	}

	/**
	 * Adds a button to the form
	 * @param FormButton $button The button to add
	 */
	public function add_button(FormButton $button)
	{
		$button->set_form_id($this->html_id);
		$this->buttons[] = $button;
	}

	/**
	 * Returns the value of a form field.
	 * @param string $field_id The HTML id of the field and string $default_value The default value
	 * @return mixed The value of the field (the type depends of the field)
	 * @throws FormBuilderException
	 */
	public function get_value($field_id, $default_value = null)
	{
		try {
			$field = $this->get_field_by_id($field_id);

			if ($field->is_disabled() && $default_value !== null)
			{
				$field->set_value($default_value);
			}
			else if ($field->is_disabled() && $default_value == null)
			{
				throw new FormBuilderDisabledFieldException($field->get_id(), $field->get_value());
			}
			return $field->get_value();
		} catch (FormBuilderException $e) {
			return $default_value;
		}
	}

	/**
	 * Returns true if field is disabled
	 * @param string $field_id The HTML id of the field and string $default_value The default value
	 * @return Boolean true if field is disabled
	 */
	public function field_is_disabled($field_id)
	{
		$field = $this->get_field_by_id($field_id);
		if ($field->is_disabled())
		{
			return true;
		}
		return false;
	}

	/**
	 * Returns true if the $field_id is in the form.
	 * @param string $field_id The HTML id of the field
	 * @return mixed true if the $field_id is in the form, false otherwise
	 */
	public function has_field($field_id) {
		try {
			$this->field_is_disabled($field_id);
		} catch (FormBuilderException $ex) {
			return false;
		}
		return true;
	}

	public function get_field_by_id($field_id)
	{
		foreach ($this->fieldsets as $fieldset)
		{
			if ($fieldset->has_field($field_id))
			{
				return $fieldset->get_field($field_id);
			}
		}
		throw new FormBuilderException('The field "' . $field_id .
			'" doesn\'t exists in the "' . $this->html_id . '" form');
	}

	private function get_fieldset_by_id($fieldset_id)
	{
		foreach ($this->fieldsets as $fieldset)
		{
			if ($fieldset->get_id() == $fieldset_id)
			{
				return $fieldset;
			}
		}
		throw new FormBuilderException('The fieldset "' . $fieldset_id .
			'" doesn\'t exists in the "' . $this->html_id . '" form');
	}

	/**
	 * Displays the form
	 * @return Template The template containing all the form elements which is ready to be displayed.
	 */
	public function display()
	{
		if ($this->enable_captcha_protection)
			$this->move_captcha_protection_in_last_position();

		$template = $this->get_template_to_use();

		$template->put_all(array(
			'C_JS_NOT_ALREADY_INCLUDED' => !self::$js_already_included,
			'C_HAS_REQUIRED_FIELDS' => (self::$instance_id == 1) ? $this->has_required_fields() : false,
			'C_TARGET' => !empty($this->target),
			'FORMCLASS' => $this->css_class,
			'TARGET' => $this->target,
			'HTML_ID' => $this->html_id,
			'L_REQUIRED_FIELDS' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
			'C_VALIDATION_ERROR' => count($this->validation_error_messages),
			'TITLE_VALIDATION_ERROR_MESSAGE' => LangLoader::get_message('form.validation_error', 'status-messages-common'),
			'METHOD' => $this->method
		));

		foreach ($this->validation_error_messages as $error_message)
		{
			if (!empty($error_message))
			{
				$template->assign_block_vars('validation_error_messages', array(
					'ERROR_MESSAGE' => $error_message
				));
			}
		}

		self::$js_already_included = true;

		foreach ($this->fieldsets as $fieldset)
		{
			$template->assign_block_vars('fieldsets', array(), array(
				'C_CAPTCHA' => $this->enable_captcha_protection,
				'FIELDSET' => $fieldset->display()
			));

			//Onsubmit constraints
			foreach ($fieldset->get_onsubmit_validations() as $constraints)
			{
				foreach ($constraints as $constraint)
				{
					$template->assign_block_vars('check_constraints', array(
						'ONSUBMIT_CONSTRAINTS' => $constraint
					));
				}
			}
		}

		if (count($this->buttons) > 0)
		{
			$buttons_fieldset = new $this->submit_button_class('fbuttons');
			$buttons_fieldset->set_form_id($this->html_id);
			foreach ($this->buttons as $button)
			{
				$buttons_fieldset->add_element($button);
			}
			$template->assign_block_vars('fieldsets', array(), array(
				'FIELDSET' => $buttons_fieldset->display()
			));
		}

		return $template;
	}

	/**
	 * @return Template
	 */
	private function get_template_to_use()
	{
		if ($this->template !== null)
		{
			return $this->template;
		}
		else
		{
			return new FileTemplate('framework/builder/form/Form.tpl');
		}
	}

	private function has_required_fields()
	{
		foreach ($this->fieldsets as $fieldset)
		{
			foreach($fieldset->get_fields() as $field)
			{
				if ($field->is_required())
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Validates the form from all its constraints. If the constraints are satisfied, the
	 * validation errors will be displayed at the top of the form.
	 * @return boolean true if the form is valid, false otherwise
	 */
	public function validate()
	{
		$this->handle_disabled_fields();

		$validation_result = true;

		foreach ($this->fieldsets as $fieldset)
		{
			if (!$fieldset->validate())
			{
				$validation_error_message = $fieldset->get_validation_error_messages();
				if (!empty($validation_error_message))
				{
					$this->validation_error_messages = array_merge($this->validation_error_messages, (array)$validation_error_message);
				}
				$validation_result = false;
			}
		}
		return $validation_result;
	}

	/**
	 * Sets the form's HTML id
	 * @param string $html_id the HTML id
	 */
	public function set_html_id($html_id)
	{
		$this->html_id = $html_id;
	}

	public function get_html_id()
	{
		return $this->html_id;
	}

	/**
	 * Sets the form's target
	 * @param string $target The URL at which the form will be submited
	 */
	public function set_target($target)
	{
		if ($target instanceof Url)
		{
			$this->target = $target->rel();
		}
		else
		{
			$this->target = $target;
		}
	}

	/**
	 * Sets the form's CSS class
	 * @param string $css_class The CSS class (see the HTMLForm::SMALL_CSS_CLASS and
	 * HTMLForm::NORMAL_CSS_CLASS constants)
	 */
	public function set_css_class($css_class)
	{
		$this->css_class = $css_class;
	}

	/**
	 * Sets the HTTP method with which the form will be submited
	 * @param string $method The method name (HTMLForm::HTTP_METHOD_POST or HTMLForm::HTTP_METHOD_POST).
	 */
	public function set_method($method)
	{
		if ($method == self::HTTP_METHOD_POST)
		{
			$this->method = self::HTTP_METHOD_POST;
		}
		else
		{
			$this->method = self::HTTP_METHOD_GET;
		}
	}

	/**
	 * Sets the template to use to display the form. If this method is not called,
	 * a default template will be used (<code>/template/default/framework/builder/form/Form.tpl</code>).
	 * @param Template $template The template to use
	 */
	public function set_template(Template $template)
	{
		$this->template = $template;
	}

	private function handle_disabled_fields()
	{
		$this->enable_all_fields();
		$request = AppContext::get_request();

		$disabled_fieldsets_str = $request->get_string($this->html_id . '_disabled_fieldsets', '');
		$disabled_fieldsets_str = trim($disabled_fieldsets_str, '|');
		if ($disabled_fieldsets_str != '')
		{
			$disabled_fieldsets = explode('|', $disabled_fieldsets_str);
			foreach ($disabled_fieldsets as $fieldset_id)
			{
				try {
					$fieldset = $this->get_fieldset_by_id(str_replace($this->html_id . '_', '', $fieldset_id));
					$fieldset->disable();
				} catch (FormBuilderException $e) {
				}
			}
		}

		$disabled_fields_str = $request->get_string($this->html_id . '_disabled_fields');
		$disabled_fields_str = trim($disabled_fields_str, '|');
		if ($disabled_fields_str != '')
		{
			$disabled_fields = explode('|', $disabled_fields_str);
			foreach ($disabled_fields as $field_id)
			{
				try {
					$field = $this->get_field_by_id(str_replace($this->html_id . '_', '', $field_id));
					$field->disable();
				} catch (FormBuilderException $e) {
				}
			}
		}
	}

	private function enable_all_fields()
	{
		foreach ($this->fieldsets as $fieldset)
		{
			$fieldset->enable();
		}
	}
}
?>
