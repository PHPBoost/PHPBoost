<?php
/**
 * This class represents an abstract filter
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2011 03 06
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class Filter
{
	protected $pattern;

	/**
	 * Build a filter based on the given pattern
	 * @param string $pattern
	 */
    public function __construct($pattern)
    {
       $this->pattern = $pattern;
    }

	function get_pattern()
	{
		return $this->pattern;
	}

	function set_pattern($pattern)
	{
		return $this->pattern = $pattern;
	}

	abstract function match();
}
?>
