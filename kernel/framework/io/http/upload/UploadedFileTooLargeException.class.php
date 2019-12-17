<?php
/**
 * Represents a HTTP uploaded file
 * @package     IO
 * @subpackage  HTTP\upload
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 01 24
*/

class UploadedFileTooLargeException extends Exception
{
	public function __construct($varname, $filename)
	{
		parent::__construct('The file ' . $filename . ' is couldn\'t be uploaded because it\'s too large');
	}
}
?>
