<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 08
*/

class StringOutputStream
{
	private $stream;
	private $index = 0;
	private $length;

	public function __construct($string = '')
	{
		$this->stream = $string;
	}

	public function write($string)
	{
		$this->stream .= $string;
	}

	public function to_string()
	{
		return $this->stream;
	}
}
?>
