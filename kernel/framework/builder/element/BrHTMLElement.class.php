<?php
/**
 * @package     Builder
 * @subpackage  Element
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 4.1 - 2015 01 19
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BrHTMLElement extends AbstractHTMLElement
{
	public function display(): mixed
	{
		return '<br/>';
	}
}
?>
