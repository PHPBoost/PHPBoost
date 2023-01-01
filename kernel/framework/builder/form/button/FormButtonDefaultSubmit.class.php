<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 3.0 - 2010 02 16
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonDefaultSubmit extends FormButtonSubmit
{
	public function __construct($value = '')
	{
		if (empty($value))
			$value = LangLoader::get_message('form.submit', 'form-lang');

		parent::__construct($value, 'submit');
	}
}
?>
