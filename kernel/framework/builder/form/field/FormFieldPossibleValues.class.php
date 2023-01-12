<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 12
 * @since       PHPBoost 4.0 - 2013 09 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class FormFieldPossibleValues extends AbstractFormField
{
	private $min_input = 1;
	private $max_input = 1000;
	private $display_default = true;
	protected $placeholder = '';
	private $unique_input_value = false;

	public function __construct($id, $label = '', array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		if ($this->is_required())
		{
			$this->add_constraint(new FormFieldConstraintPossibleValuesMin());
			$this->add_constraint(new FormFieldConstraintPossibleValuesMax());
		}
		if ($this->unique_input_value)
			$this->add_constraint(new FormFieldConstraintPossibleValuesUnique());

	}

	function display()
	{
		$template = $this->get_template_to_use();
		$lang = LangLoader::get_all_langs();

		$tpl = new FileTemplate('framework/builder/form/FormFieldPossibleValues.tpl');
		$tpl->add_lang($lang);

		$this->assign_common_template_variables($template);

		$has_default = false;
		$i = 0;
		foreach ($this->get_value() as $name => $options)
		{
			if (!empty($options))
			{
				$has_default = $options['is_default'] ? true : $has_default;
				$tpl->assign_block_vars('fieldelements', array(
					'ID'         => $i,
					'NAME'       => $name,
					'IS_DEFAULT' => (int) $options['is_default'],
					'TITLE'      => stripslashes($options['title'])
				));
				$i++;
			}
		}

		if ($i == 0)
		{
			for ($i = 0 ; $i < $this->min_input ; $i++)
			{
				$tpl->assign_block_vars('fieldelements', array(
					'ID'         => $i,
					'NAME'       => '',
					'IS_DEFAULT' => 0,
					'TITLE'      => ''
				));
			}
		}

		$tpl->put_all(array(
			'C_HAS_DEFAULT_VALUE'     => $has_default,
			'C_DELETE'				  => $i > $this->min_input,
			'C_REQUIRED'              => $this->is_required(),
			'C_DISPLAY_DEFAULT_RADIO' => $this->display_default,
			'C_UNIQUE_INPUT_VALUE'    => $this->unique_input_value,
			'C_DISABLED'              => $this->is_disabled(),

			'NAME'          => $this->get_html_id(),
			'ID'            => $this->get_id(),
			'HTML_ID'       => $this->get_html_id(),
			'MIN_INPUT'     => $this->min_input,
			'MAX_INPUT'     => $this->max_input,
			'PLACEHOLDER'   => $this->placeholder ? $this->placeholder : $lang['form.name'],
			'FIELDS_NUMBER' => $i,
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		$field_is_default = 'field_is_default_' . $this->get_html_id();
		$default_field = $request->get_postint($field_is_default, -1);
		for ($i = 0; $i <= $this->max_input; $i++)
		{
			$field_name = 'field_name_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_name))
			{
				if ($request->get_poststring($field_name))
				{
					$values[preg_replace('/\s+/u', '', $request->get_poststring($field_name))] = array(
						'is_default' => $default_field == $i,
						'title' => addslashes($request->get_poststring($field_name))
					);
				}
			}
		}
		$this->set_value($values);
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'min_input':
					$this->min_input = $value;
					unset($field_options['min_input']);
					break;
				case 'max_input':
					$this->max_input = $value;
					unset($field_options['max_input']);
					break;
				case 'display_default':
					$this->display_default = (bool)$value;
					unset($field_options['display_default']);
					break;
				case 'placeholder':
					$this->placeholder = $value;
					unset($field_options['placeholder']);
					break;
				case 'unique_input_value':
					$this->unique_input_value = (bool)$value;
					$this->unique_input_value ? $this->add_constraint(new FormFieldConstraintPossibleValuesUnique()) : '';
					unset($field_options['unique_input_value']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	public function get_min_input()
	{
		return $this->min_input;
	}

	public function get_max_input()
	{
		return $this->max_input;
	}

	public function get_unique_input_value()
	{
		return $this->unique_input_value;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
