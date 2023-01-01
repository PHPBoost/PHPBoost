<?php
/**
 * This class represents a mulitple ajax completer fields
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2010 01 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldMultipleAutocompleter extends AbstractFormField
{
	private $max_input = 20;
	private $size = 30;
	private $method = 'post';
	private $file;
	private $name_parameter = 'value';

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $field_options, $constraints);
    }

	public function display()
    {
        $template = $this->get_template_to_use();

		$view = new FileTemplate('framework/builder/form/FormFieldMultipleAutocompleter.tpl');
		$view->add_lang(LangLoader::get_all_langs());
        $this->assign_common_template_variables($template);

		if (empty($this->file))
		{
			throw new Exception('Add file options containing file url');
		}

		$i = 0;
		foreach ($this->get_value() as $value)
		{
			$view->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'VALUE' => $value
			));
			$i++;
		}

		if ($i == 0)
		{
			$view->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'VALUE' => ''
			));
		}

		$view->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
			'FIELDS_NUMBER' => $i == 0 ? 1 : $i,
			'SIZE' => $this->size,
			'METHOD' =>  $this->method,
			'NAME_PARAMETER' =>  $this->name_parameter,
			'FILE' =>  $this->file,
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
			$id = 'field_' . $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($id))
			{
				$values[] = $request->get_poststring($id);
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
				case 'size':
					$this->size = $value;
					unset($field_options['size']);
					break;
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
