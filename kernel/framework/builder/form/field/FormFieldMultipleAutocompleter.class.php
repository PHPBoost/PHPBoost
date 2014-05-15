<?php
/*##################################################
 *                          FormFieldMultipleAutocompleter.class.php
 *                            -------------------
 *   begin                : January 9, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class represents a mulitple ajax completer fields
 * @package {@package}
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

		$tpl = new FileTemplate('framework/builder/form/FormFieldMultipleAutocompleter.tpl');
        $this->assign_common_template_variables($template);

    	if (empty($this->file))
		{
			throw new Exception('Add file options containing file url');
		}
		
        $i = 0;
   		foreach ($this->get_value() as $value)
		{			
	        $tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
	        	'VALUE' => $value
	        ));
	        $i++;
		}
		
		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
	        	'VALUE' => ''
	        ));
		}
		
		 $tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
        	'MAX_INPUT' => $this->max_input,
		 	'NBR_FIELDS' => $i == 0 ? 1 : $i,
		 	'SIZE' => $this->size,
		 	'METHOD' =>  $this->method,
			'NAME_PARAMETER' =>  $this->name_parameter,
		 	'FILE' =>  $this->file,
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
            $attribute = strtolower($attribute);
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