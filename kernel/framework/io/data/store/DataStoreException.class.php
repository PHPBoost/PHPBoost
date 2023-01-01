<?php
/**
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 20
 * @contributor Loic ROUCHON <horn@phpboost.com>
*/

class DataStoreException extends Exception
{
	public function __construct($id)
	{
		parent::__construct('The data store doesn\'t contains element "' . $id . '"');
	}
}
?>
