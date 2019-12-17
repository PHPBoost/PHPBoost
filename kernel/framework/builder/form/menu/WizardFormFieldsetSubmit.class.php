<?php
/**
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 31
 * @since       PHPBoost 5.2 - 2019 07 31
*/

class WizardFormFieldsetSubmit extends FormFieldsetHTML
{
	/**
	 * @var FormButton[]
	 */
	private $buttons;

	public function __construct($id, $options = array())
	{
		if (!isset($options['css_class']))
		{
			$options['css_class'] = 'fieldset-submit wizard-step';
		}
		parent::__construct($id, '', $options);
	}
}

?>
