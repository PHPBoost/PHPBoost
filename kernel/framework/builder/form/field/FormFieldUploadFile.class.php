<?php
/**
 * This class manage single-line text fields with a link to access the upload modal form.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 17
 * @since       PHPBoost 4.0 - 2013 12 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldUploadFile extends AbstractFormField
{
	protected $authorized_extensions = '';
	/**
	 * Constructs a FormFieldUploadFile.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		$constraints[] = new FormFieldConstraintUrlExists(LangLoader::get_message('form.unexisting_file', 'status-messages-common'));
		if (isset($field_options['authorized_extensions']))
			$constraints[] = new FormFieldConstraintFileExtension($field_options['authorized_extensions']);
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$file_type = new FileType(new File($this->get_value()));

		$template->put_all(array(
			'C_PREVIEW_HIDDEN' => !$file_type->is_picture(),
			'C_AUTH_UPLOAD' => FileUploadConfig::load()->is_authorized_to_access_interface_files(),
			'FILE_PATH' => Url::to_rel($this->get_value()),
		));

		return $template;
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'authorized_extensions':
					$this->authorized_extensions = $value;
					unset($field_options['authorized_extensions']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldUploadFile.tpl');
	}
}
?>
