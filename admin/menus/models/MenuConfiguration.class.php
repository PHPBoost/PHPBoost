<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 25
*/

class MenuConfiguration extends BusinessObject
{
    private $id;
    private $name;
    private $match_regex;
    private $priority;

	public function __construct()
	{
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_match_regex()
	{
		return $this->match_regex;
	}

	public function get_priority()
	{
		return $this->priority;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function set_match_regex($value)
	{
		$this->match_regex = $value;
	}

	public function set_priority($value)
	{
		$this->priority = $value;
	}
}
?>
