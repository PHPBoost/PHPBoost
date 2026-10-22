<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2010 02 16
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonSubmit extends AbstractFormButton
{
	public function __construct(string $value, string $name, string $onclick_action = '', string $css_class = 'submit', string $data_confirmation = '', string $form_id = '')
	{
		parent::__construct('submit', $value, $name, $onclick_action, $css_class, $data_confirmation, $form_id);
	}

	public function has_been_submited(): bool
	{
		$request = AppContext::get_request();
		$button_attribute = $request->get_string($this->get_html_name(), '');
		return !empty($button_attribute);
	}
}
?>
