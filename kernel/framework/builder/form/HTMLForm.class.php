<?php
/**
 * This class enables you to handle all the operations regarding forms. Indeed, you build a
 * form using object components (fieldsets, fields, buttons) and it's able to display, to retrieve
 * the posted values and also validate the entered data from constraints you define. The validation
 * is done in PHP when the form is received, but also in live thanks to Javascript (each field is
 * validated when it looses the focus and the whole form is validated when the user submits it).
 * @package     Builder
 * @subpackage  Form
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2009 04 28
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class HTMLForm implements HTMLElement
{
    const HTTP_METHOD_POST = 'post';
    const HTTP_METHOD_GET = 'get';

    private static int $instance_id = 0;

    private array|FormConstraint $constraints = [];
    private array|FormFieldset $fieldsets = [];
    private array|FormButton $buttons = [];
    private string $html_id = '';
    private mixed $target = null;
    private string $method = self::HTTP_METHOD_POST;
    private string $css_class = '';
    private string $layout_title = '';
    private static bool $js_already_included = false;
    private array $validation_error_messages = [];
    private ?Template $template = null;
    private mixed $enable_captcha_protection;
    protected string $captcha_fieldset_css_class = '';
    protected string $submit_button_class = 'FormFieldsetSubmit';

    /**
     * Constructs a HTMLForm object
     * @param string $html_id The HTML name of the form
     * @param mixed $target The url where the form sends data
     * @param bool $enable_captcha_protection True if the CAPTCHA is enabled
     */
    public function __construct(string $html_id, mixed $target = null, bool $enable_captcha_protection = true)
    {
        $this->enable_captcha_protection = $enable_captcha_protection;
        $this->set_html_id($html_id);
        $this->set_target($target);

        if ($this->enable_captcha_protection)
            $this->add_catpcha_protection(); //Add captcha protection for visitor

        self::$instance_id++;
    }

    private function add_catpcha_protection(): void
    {
        $captcha_protection_fieldset = new FormFieldsetHTML('captcha_fieldset');
        $captcha_protection_fieldset->add_field(new FormFieldCaptcha());
        $captcha_protection_fieldset->set_css_class($this->captcha_fieldset_css_class);
        $this->add_fieldset($captcha_protection_fieldset);
    }

    public function move_captcha_protection_in_last_position(): void
    {
        try {
            $this->fieldsets[] = $this->fieldsets[0];
            unset($this->fieldsets[0]);
        } catch (FormBuilderException $e) {
        }
    }

    /**
     * Adds fieldset in the form
     * @param FormFieldset $fieldset the fieldset to add
     */
    public function add_fieldset(FormFieldset $fieldset): void
    {
        $fieldset->set_form_id($this->html_id);
        $this->fieldsets[] = $fieldset;
    }

    /**
     * Adds a constraint on the form. This kind of constraints are rules regarding several fields.
     * @param FormConstraint $constraint The constraint to add
     */
    public function add_constraint(FormConstraint $constraint): void
    {
        $this->constraints[] = $constraint;
    }

    /**
     * Adds a button to the form
     * @param FormButton $button The button to add
     */
    public function add_button(FormButton $button): void
    {
        $button->set_form_id($this->html_id);
        $this->buttons[] = $button;
    }

    /**
     * Returns the value of a form field.
     * @param string $field_id The HTML id of the field and string $default_value The default value
     * @return mixed $default_value The value of the field (the type depends of the field)
     * @throws FormBuilderException
     */
    public function get_value(string $field_id, mixed $default_value = null): mixed
    {
        try
        {
            $field = $this->get_field_by_id($field_id);

            if ($field->is_disabled() && $default_value !== null)
            {
                $field->set_value($default_value);
            }
            elseif ($field->is_disabled() && $default_value == null)
            {
                throw new FormBuilderDisabledFieldException($field->get_id(), $field->get_value());
            }
            return $field->get_value();
        }
        catch (FormBuilderException $e)
        {
            return $default_value;
        }
    }

    /**
     * Returns true if field is disabled
     * @param string $field_id The HTML id of the field and string $default_value The default value
     * @return bool true if field is disabled
     */
    public function field_is_disabled(string $field_id): bool
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
     * @return bool true if the $field_id is in the form, false otherwise
     */
    public function has_field(string $field_id): bool
    {
        try
        {
            $this->field_is_disabled($field_id);
        }
        catch (FormBuilderException $ex)
        {
            return false;
        }
        return true;
    }

    public function get_field_by_id(string $field_id): mixed
    {
        foreach ($this->fieldsets as $fieldset)
        {
            if ($fieldset->has_field($field_id))
            {
                return $fieldset->get_field($field_id);
            }
        }
        throw new FormBuilderException('The field "' . $field_id . '" doesn\'t exists in the "' . $this->html_id . '" form');
    }

    public function get_fieldset_by_id(mixed $fieldset_id): mixed
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
    public function display(): Template
    {
        if ($this->enable_captcha_protection)
            $this->move_captcha_protection_in_last_position();

        $template = $this->get_template_to_use();

        $template->put_all([
            'C_JS_NOT_ALREADY_INCLUDED' => !self::$js_already_included,
            'C_HAS_REQUIRED_FIELDS'     => (self::$instance_id == 1) ? $this->has_required_fields() : false,
            'C_FORM_CLASS'              => !empty($this->css_class),
            'C_TARGET'                  => !empty($this->target),
            'C_LAYOUT'                  => !empty($this->layout_title),
            'C_VALIDATION_ERROR'        => count($this->validation_error_messages),

            'LAYOUT_TITLE' => $this->layout_title,
            'MODULE_ID'    => Environment::get_running_module_name(),
            'FORM_CLASS'   => $this->css_class,
            'TARGET'       => $this->target,
            'HTML_ID'      => $this->html_id,
            'METHOD'       => $this->method,

            'TITLE_VALIDATION_ERROR_MESSAGE' => LangLoader::get_message('warning.validation.error', 'warning-lang'),
            'L_REQUIRED_FIELDS'              => LangLoader::get_message('form.required.fields', 'form-lang'),
        ]);

        foreach ($this->validation_error_messages as $error_message)
        {
            if (!empty($error_message))
            {
                $template->assign_block_vars('validation_error_messages', [
                    'ERROR_MESSAGE' => $error_message
                ]);
            }
        }

        self::$js_already_included = true;

        foreach ($this->fieldsets as $fieldset)
        {
            $template->assign_block_vars('fieldsets', [], [
                'C_CAPTCHA' => $this->enable_captcha_protection,

                'FIELDSET' => $fieldset->display()
            ]);

            //Onsubmit constraints
            foreach ($fieldset->get_onsubmit_validations() as $constraints)
            {
                foreach ($constraints as $constraint)
                {
                    $template->assign_block_vars('check_constraints', [
                        'ONSUBMIT_CONSTRAINTS' => $constraint
                    ]);
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
            $template->assign_block_vars('fieldsets', [], [
                'FIELDSET' => $buttons_fieldset->display()
            ]);
        }

        return $template;
    }

    private function get_template_to_use(): Template
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

    private function has_required_fields(): bool
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
     * @return bool true if the form is valid, false otherwise
     */
    public function validate(): bool
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
    public function set_html_id(string $html_id): void
    {
        $this->html_id = $html_id;
    }

    public function get_html_id(): string
    {
        return $this->html_id;
    }

    /**
     * Sets the form's target
     * @param string $target The URL at which the form will be submited
     */
    public function set_target(string $target): void
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
     * @param string $css_class The CSS class 
     */
    public function set_css_class(string $css_class): void
    {
        $this->css_class = $css_class;
    }

    /**
     * Sets the h1 title of the layout
     * @param string $layout_title
     */
    public function set_layout_title(string $layout_title): void
    {
        $this->layout_title = $layout_title;
    }

    /**
     * Sets the HTTP method with which the form will be submited
     * @param string $method The method name (HTMLForm::HTTP_METHOD_POST or HTMLForm::HTTP_METHOD_POST).
     */
    public function set_method(string $method): void
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
    public function set_template(Template $template): void
    {
        $this->template = $template;
    }

    private function handle_disabled_fields(): void
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
                try
                {
                    $fieldset = $this->get_fieldset_by_id(str_replace($this->html_id . '_', '', $fieldset_id));
                    $fieldset->disable();
                }
                catch (FormBuilderException $e) {}
            }
        }

        $disabled_fields_str = $request->get_string($this->html_id . '_disabled_fields');
        $disabled_fields_str = trim($disabled_fields_str, '|');
        if ($disabled_fields_str != '')
        {
            $disabled_fields = explode('|', $disabled_fields_str);
            foreach ($disabled_fields as $field_id)
            {
                try
                {
                    $field = $this->get_field_by_id(str_replace($this->html_id . '_', '', $field_id));
                    $field->disable();
                }
                catch (FormBuilderException $e) {}
            }
        }
    }

    private function enable_all_fields(): void
    {
        foreach ($this->fieldsets as $fieldset)
        {
            $fieldset->enable();
        }
    }
}
?>
