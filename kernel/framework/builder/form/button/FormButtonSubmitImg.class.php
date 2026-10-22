<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2010 10 03
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonSubmitImg extends FormButtonSubmit
{
	public function __construct(mixed $value, string $image, string $name, string $onclick_action = '', string $data_confirmation = '', string $form_id = '')
	{
		$new_value = '<img src="' . $image . '" alt="' . $value . '" aria-label="' . $value . '" />';
		parent::__construct($new_value, $name, $onclick_action, 'image', $data_confirmation, $form_id);
	}
}
?>
