<?php
/**
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 12 20
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DataStoreException extends Exception
{
	public function __construct(string $id)
	{
		parent::__construct('The data store doesn\'t contains element "' . $id . '"');
	}
}
?>
