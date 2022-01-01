<?php
/**
 * This class manage select fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>multiple : Type of select field, mutiple allow you to check several options.</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldMultipleSelectChoice extends AbstractFormField
{
	private $selected_options;
	private $options = array();
	private $size = 4;

	/**
	 * Constructs a FormFieldMultipleSelectChoice.
	 * @param string $id Field id
	 * @param string $label Field label
	 * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
	 * @param FormFieldEnumOption[] $options Enumeration of the possible values
	 * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
	 * @param FormFieldConstraint List of the constraints
	 */
	public function __construct($id, $label, array $selected_options, array $available_options, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $selected_options, $field_options, $constraints);

		$this->set_css_form_field_class('form-field-multi-select');

		foreach ($available_options as $option)
		{
			$this->add_option($option);
		}

		$this->set_selected_options($selected_options);
	}

	public function set_selected_options(array $selected_options)
	{
		$value = array();

		foreach ($selected_options as $option)
		{
			if (is_string($option))
			{
				$value[] = $this->get_option($option);
			}
			else if ($option instanceof FormFieldSelectChoiceOption)
			{
				$value[] = $option;
			}
			else
			{
				throw new FormBuilderException('option ' . $option . ' isn\'t recognized');
			}
		}
		$this->selected_options = $value;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();

		if ($request->has_parameter($this->get_html_id()))
		{
			$selected_options = $request->get_array($this->get_html_id());

			$value = array();
			foreach ($this->get_options() as $option)
			{
				if (in_array($option->get_raw_value(), $selected_options))
				{
					$value[] = $option;
				}
			}
			$this->set_value($value);
		}
		else
		{
			$this->set_value(array());
		}
	}

	/**
	 * @return string The html code for the select.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->get_html_code()->render(),
		));

		return $template;
	}

	private function get_html_code()
	{
		$lang = LangLoader::get_all_langs();
		$view = new FileTemplate('framework/builder/form/FormFieldMultipleSelectChoice.tpl');
		$view->add_lang($lang);

		$view->put_all(array(
			'C_DISABLED' => $this->is_disabled(),
			'C_REQUIRED' => $this->is_required(),
			'C_MULTIPLE_SELECT_TO_LIST' => $this->is_multiple_select_to_list(),

			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'FORM_ID' => $this->get_form_id(),
			'SIZE' => $this->size,
			'CSS_CLASS' => $this->get_css_class(),
			'L_SELECT_ALL' => $lang['form.select.all'],
			'L_UNSELECT_ALL' => $lang['form.select.none'],
			'L_SELECT_EXPLAIN' => $lang['form.select.multiple.clue']
		));

		foreach ($this->get_options() as $multiple_select_option)
		{
			if ($multiple_select_option instanceof FormFieldSelectChoiceGroupOption)
			{
				foreach ($multiple_select_option->get_options() as $option)
				{
					$select = $this->is_selected($option);
					if ($select)
					{
						$option->set_active();
					}
				}
			}
			else
			{
				$select = $this->is_selected($multiple_select_option);
				if ($select)
				{
					$multiple_select_option->set_active();
				}
			}

			$view->assign_block_vars('options', array(
				'OPTION' => $multiple_select_option->display()
			));
		}
		return $view;
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'size':
					$this->size = $value;
					unset($field_options['size']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	private function is_selected($option)
	{
		return in_array($option, $this->selected_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}

	private function add_option(FormFieldEnumOption $option)
	{
		$option->set_field($this);
		$this->options[] = $option;
	}

	private function get_options()
	{
		return $this->options;
	}

	private function get_option($identifier)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_raw_value() == $identifier || $option->get_label() == $identifier)
			{
				return $option;
			}
		}
		return null;
	}

	protected function get_js_specialization_code()
	{
		return 'field.getValue = function()
		{
			return jQuery("#'.$this->get_html_id().' :selected").length;
		}';
	}
}
?>
