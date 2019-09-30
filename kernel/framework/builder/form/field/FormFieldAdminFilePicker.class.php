<?php
/**
 * This class manage file input fields.
 * It provides you additionnal field options :
 * <ul>
 *  <li>size : The size for the field</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2019 04 04
 * @since       PHPBoost 2.0 - 2009 04 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldAdminFilePicker extends FormFieldFilePicker
{
	protected function get_file_field_template()
	{
		return new FileTemplate('framework/builder/form/fieldelements/FormFieldFilePicker.tpl');
	}
}
?>
