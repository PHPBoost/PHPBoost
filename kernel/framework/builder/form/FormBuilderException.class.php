<?php
/**
 * @package     Builder
 * @subpackage  Form
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2009 12 19
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormBuilderException extends Exception
{
	public function __construct(string $message)
	{
		parent::__construct($message);
	}
}
?>
