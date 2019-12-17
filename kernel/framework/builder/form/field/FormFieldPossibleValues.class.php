<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 18
 * @since       PHPBoost 4.0 - 2013 09 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldPossibleValues extends AbstractFormField
{
	private $max_input = 100;

	public function __construct($id, $label = '', array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();
		$lang = LangLoader::get('admin-user-common');

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
					'ID' => $i,
					'NAME' => $name,
					'IS_DEFAULT' => (int) $options['is_default'],
					'TITLE' => stripslashes($options['title'])
				));
				$i++;
			}
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'NAME' => '',
				'IS_DEFAULT' => 0,
				'TITLE' => ''
			));
			$i++;
		}

		$tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
		 	'NBR_FIELDS' => $i,
			'C_HAS_DEFAULT_VALUE' => $has_default
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
