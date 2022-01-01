<?php
/**
 * @package     Builder
 * @subpackage  Form
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2009 12 19
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormBuilderException extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
?>
