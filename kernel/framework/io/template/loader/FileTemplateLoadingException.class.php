<?php
/**
 * This exception is thrown when the template cannot been loaded.
 * @package     IO
 * @subpackage  Template\loader
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 06 18
*/

class FileTemplateLoadingException extends TemplateLoadingException
{
	/**
	 * Constructs
	 * @param $template_identifier
	 * @param $reason
	 * @return unknown_type
	 */
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
?>
