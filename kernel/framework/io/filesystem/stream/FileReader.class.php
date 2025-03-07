<?php
/**
 * @package     IO
 * @subpackage  Filesystem\stream
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 05 29
*/

interface FileReader
{
	function read_all();

	function read_line();
}
?>
