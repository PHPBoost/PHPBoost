<?php
/**
 * This class manage file input fields.
 * It provides you additionnal field options :
 * <ul>
 *  <li>size : The size for the field</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldFilePicker extends AbstractFormField
{
	private $multiple = false;
	private $max_file_size = 0;
	private $max_files_size = 0;
	private $authorized_extensions = '';

	public function __construct($id, $label, array $field_options = array(), array $constraints = array())
	{
		$constraints[] = new FormFieldConstraintFileMaxSize(isset($field_options['max_file_size']) ? $field_options['max_file_size'] : $this->get_max_file_size());
		if (isset($field_options['authorized_extensions']))
			$constraints[] = new FormFieldConstraintFileExtension($field_options['authorized_extensions']);
		parent::__construct($id, $label, null, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-file');
	}

	/**
	 * @return Template The html code for the file input.
	 */
	function display()
	{
		$template = $this->get_template_to_use();

		$file_field_tpl = $this->get_file_field_template();
		$file_field_tpl->add_lang(LangLoader::get_all_langs());
		$file_field_tpl->put_all(array(
			'C_DISABLED' => $this->is_disabled(),
			'C_MULTIPLE' => $this->is_multiple(),

			'NAME'                => $this->get_html_id(),
			'ID'                  => $this->get_id(),
			'HTML_ID'             => $this->get_html_id(),
			'MAX_FILE_SIZE'       => $this->get_max_file_size(),
			'MAX_FILE_SIZE_TEXT'  => File::get_formated_size($this->get_max_file_size()),
			'MAX_FILES_SIZE'      => $this->get_max_files_size(),
			'MAX_FILES_SIZE_TEXT' => File::get_formated_size($this->get_max_files_size()),
			'ALLOWED_EXTENSIONS'  => $this->get_authorized_extensions()
		));

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $file_field_tpl->render()
		));

		return $template;
	}

	protected function get_file_field_template()
	{
		return new FileTemplate('framework/builder/form/fieldelements/FormFieldFilePicker.tpl');
	}

	protected function get_max_file_size()
	{
		return ($this->max_file_size > 0) ?  $this->max_file_size : ServerConfiguration::get_upload_max_filesize();
	}

	protected function get_max_files_size()
	{
		if ($this->is_multiple() && $this->max_files_size > 0)
			return $this->max_files_size;
		else if ($this->max_file_size > 0)
			return $this->max_file_size;
		else
		{
			if (AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
				return '-1';
			else if (AppContext::get_current_user()->is_guest())
				return ServerConfiguration::get_upload_max_filesize();
			else
				return AppContext::get_current_user()->check_max_value(DATA_GROUP_LIMIT, FileUploadConfig::load()->get_maximum_size_upload()) - Uploads::Member_memory_used(AppContext::get_current_user()->get_id());
		}
	}

	protected function get_authorized_extensions()
	{
		return $this->authorized_extensions ? str_replace('|', '", "', $this->authorized_extensions) : implode('", "',FileUploadConfig::load()->get_authorized_extensions());
	}

	public function is_multiple()
	{
		return $this->multiple;
	}

	protected function set_multiple($multiple)
	{
		$this->multiple = $multiple;
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'multiple':
					$this->multiple = $value;
					unset($field_options['multiple']);
					break;
				case 'max_file_size':
					$this->max_file_size = $value;
					unset($field_options['max_file_size']);
					break;
				case 'max_files_size':
					$this->max_files_size = $value;
					unset($field_options['max_files_size']);
					break;
				case 'authorized_extensions':
					$this->authorized_extensions = $value;
					unset($field_options['authorized_extensions']);
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
			return $this->is_required() ? false : true;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$this->set_value(AppContext::get_request()->get_file($this->get_html_id()));
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
