<?php
/**
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 09 05
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FileTemplateRenderingException extends Exception
{
	public function __construct(string $file_identifier, Exception $exception)
	{
        $message = 'template ' . $file_identifier . "\n" . $exception->getMessage();
		parent::__construct($message);
	}
}
?>
