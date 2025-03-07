<?php
/**
 * @package     Core
 * @subpackage  Lang
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 29
*/

class LangNotFoundException extends Exception
{
	public function __construct($folder, $filename)
	{
        $folder = trim($folder, '/');
		if (empty($folder))
		{
			$folder = 'lang';
		}
		parent::__construct('Unable to find language file "' . $filename . '" in: "/' . $folder . '"');
	}
}
?>
