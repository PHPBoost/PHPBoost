<?php
/*##################################################
 *                             FormFieldMultipleFilePicker.class.php
 *                            -------------------
 *   begin                : March 10, 2011
 *   copyright            : (C) 2011 MASSY Kevin
 *   email                : kevin.massy@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author MASSY Kevin <kevin.massy@phpboost.com>
 * @desc This class manage multiple file input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The multiple size for the field</li>
 * </ul>
 * @package {@package}
 */
class FormFieldMultipleFilePicker extends AbstractFormField
{
    private $max_size = 0;
	private $max_input = 5;

    public function __construct($id, $label, array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, null, $field_options, $constraints);
    }

    /**
     * @return Template The html code for the file input.
     */
    function display()
    {
        $template = $this->get_template_to_use();

		$tpl = new FileTemplate('framework/builder/form/FormFieldMultipleFilePicker.tpl');
        $tpl->put_all(array(
			'MAX_FILE_SIZE' => $this->get_max_file_size(),
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input
        ));

        $this->assign_common_template_variables($template);

        $template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
        ));

        return $template;
    }

    private function get_max_file_size()
    {
        if ($this->max_size > 0)
        {
            return $this->max_size;
        }
        else
        {
            return 10000000000;
        }
    }

    protected function compute_options(array &$field_options)
    {
        foreach($field_options as $attribute => $value)
        {
            $attribute = strtolower($attribute);
            switch ($attribute)
            {
                case 'max_size':
                    $this->max_size = $value;
                    unset($field_options['max_size']);
                    // TODO add max size constraint
                    break;
				 case 'max_input':
                    $this->max_input = $value;
                    unset($field_options['max_input']);
                    break;
            }
        }
        parent::compute_options($field_options);
    }

    /**
     * {@inheritdoc}
     */
    public function validate()
    {
        try
        {
            $this->retrieve_value();
            return true;
        }
        catch(Exception $ex)
        {
            if ($this->is_required())
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve_value()
    {
		$request = AppContext::get_request();
		$array_file = array();
		for ($i = 1; $i <= $this->max_input; $i++) 
		{
			$id = $this->get_html_id() . '_' . $i;
			if (isset($_FILES[$id]))
			{
				$array_file[] = $request->get_file($id);
			}
		}
		$this->set_value($array_file);
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormField.tpl');
    }
}
?>