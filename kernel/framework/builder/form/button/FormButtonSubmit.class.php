<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 30
 * @since       PHPBoost 3.0 - 2010 02 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormButtonSubmit extends AbstractFormButton
{
	public function __construct($value, $name, $onclick_action = '', $css_class = 'submit', $data_confirmation = '', $form_id = '')
	{
		parent::__construct('submit', $value, $name, $onclick_action, $css_class, $data_confirmation, $form_id);
	}

	public function has_been_submited()
	{
		$request = AppContext::get_request();
		$button_attribute = $request->get_string($this->get_html_name(), '');
		return !empty($button_attribute);
	}
}
?>
