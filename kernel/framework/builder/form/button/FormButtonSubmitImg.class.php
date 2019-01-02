<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2018 11 30
 * @since       PHPBoost 3.0 - 2010 10 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormButtonSubmitImg extends FormButtonSubmit
{
	public function __construct($value, $image, $name, $onclick_action = '', $data_confirmation = '', $form_id = '')
	{
		$new_value = '<img src="' . $image . '" alt="' . $value . '" title="' . $value . '" />';
		parent::__construct($new_value, $name, $onclick_action, 'image', $data_confirmation, $form_id);
	}
}
?>
