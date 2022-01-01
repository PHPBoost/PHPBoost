<?php
/**
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 09 05
*/

class FileTemplateRenderingException extends Exception
{
	public function __construct($file_identifier, Exception $exception)
	{
        $message = 'template ' . $file_identifier . "\n" . $exception->getMessage();
		parent::__construct($message);
	}
}
?>
