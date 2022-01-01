<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 08 02
 * @since       PHPBoost 4.0 - 2014 05 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonSubmitCssImg extends FormButtonSubmit
{
	public function __construct($value, $css_class_image, $name, $onclick_action = '', $data_confirmation = '', $form_id = '')
	{
		$new_value = '<i class="' . $css_class_image . '" aria-label="' . $value . '" aria-hidden="true"></i><span class="sr-only">' . $value . '</span>';
		parent::__construct($new_value, $name, $onclick_action, 'image', $data_confirmation, $form_id);
	}
}
?>
