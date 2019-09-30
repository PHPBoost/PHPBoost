<?php
/**
 * This class manage multiple file input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The multiple size for the field</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2018 11 19
 * @since       PHPBoost 3.0 - 2010 03 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		$tpl->add_lang(LangLoader::get('common'));
		$tpl->add_lang(LangLoader::get('main'));
        $tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,

			'MAX_FILES_SIZE_TEXT' => ($this->get_max_file_size() / 1000000) . ' Mo',
    		'MAX_FILE_SIZE' => ServerConfiguration::get_upload_max_filesize(),
    		'ALLOWED_EXTENSIONS' => implode('", "',FileUploadConfig::load()->get_authorized_extensions()),
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
            $attribute = TextHelper::strtolower($attribute);
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
