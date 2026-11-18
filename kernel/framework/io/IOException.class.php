<?php
/**
 * @package     IO
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 17
 * @since       PHPBoost 3.0 - 2009 12 13
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class IOException extends Exception
{
	public function __construct(string $message)
	{
		parent::__construct($message);
	}
}
?>
