<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 4.0 - 2014 05 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonSubmitCssImg extends FormButtonSubmit
{
	public function __construct(mixed $value, mixed $css_class_image, string $name, string $onclick_action = '', string $data_confirmation = '', string $form_id = '')
	{
		$new_value = '<i class="' . $css_class_image . '" aria-label="' . $value . '" aria-hidden="true"></i><span class="sr-only">' . $value . '</span>';
		parent::__construct($new_value, $name, $onclick_action, 'image', $data_confirmation, $form_id);
	}
}
?>
