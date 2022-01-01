<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 16
*/

class FormFieldsetSubmit extends FormFieldsetHTML
{
	/**
	 * @var Template
	 */
	private $template;
	/**
	 * @var FormButton[]
	 */
	private $buttons;

	public function __construct($id, $options = array())
	{
		if (!isset($options['css_class']))
		{
			$options['css_class'] = 'fieldset-submit';
		}
		parent::__construct($id, '', $options);
	}
}

?>
