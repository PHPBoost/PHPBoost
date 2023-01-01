<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldSelectSources extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$view = new FileTemplate('framework/builder/form/fieldelements/FormFieldSelectSources.tpl');
		$view->add_lang(LangLoader::get_all_langs());

		$view->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled()
		));


		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $name => $value)
		{
			$view->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'VALUE' => $value,
				'NAME' => $name
			));
			$i++;
		}

		if ($i == 0)
		{
			$view->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'VALUE' => '',
				'NAME' => ''
			));
		}

		$view->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
			'NBR_FIELDS' => $i == 0 ? 1 : $i,
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $view->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_name_id = 'field_name_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_name_id))
			{
				$field_value_id = 'field_value_' . $this->get_html_id() . '_' . $i;
				$field_name = $request->get_poststring($field_name_id);
				$field_value = $request->get_poststring($field_value_id);

				if (!empty($field_name) && !empty($field_value))
					$values[$field_name] = $field_value;
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
				case 'max_input':
					$this->max_input = $value;
					unset($field_options['max_input']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
