<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2010 10 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldsetSubmit extends FormFieldsetHTML
{
	public function __construct(string $id, array $options = [])
	{
		if (!isset($options['css_class']))
		{
			$options['css_class'] = 'fieldset-submit';
		}
		parent::__construct($id, '', $options);
	}
}

?>
