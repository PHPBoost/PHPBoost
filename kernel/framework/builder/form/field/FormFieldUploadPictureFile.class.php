<?php
/**
 * This class manage single-line text fields with a link to access the upload modal form.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 06 03
 * @since       PHPBoost 4.1 - 2015 06 12
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldUploadPictureFile extends FormFieldUploadFile
{
	/**
	 * Constructs a FormFieldUploadPictureFile.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		$constraints[] = new FormFieldConstraintPictureFile();
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}
}
?>
