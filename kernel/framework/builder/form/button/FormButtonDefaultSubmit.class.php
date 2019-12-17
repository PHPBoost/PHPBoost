<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2010 02 16
 * @since       PHPBoost 3.0 - 2010 02 16
*/

class FormButtonDefaultSubmit extends FormButtonSubmit
{
	public function __construct($value = '')
	{
		if (empty($value))
			$value = LangLoader::get_message('submit', 'main');

		parent::__construct($value, 'submit');
	}
}
?>
