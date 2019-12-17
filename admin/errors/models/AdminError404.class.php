<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 13
*/

class AdminError404 extends BusinessObject
{
    private $id;
    private $requested_url;
    private $from_url;
    private $times = 1;

    public function __construct($requested_url = '', $from_url = '')
    {
        $this->requested_url = $requested_url;
        $this->from_url = $from_url;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_requested_url()
    {
        return $this->requested_url;
    }

    public function get_from_url()
    {
        return $this->from_url;
    }

	public function get_times()
	{
		return $this->times;
	}

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function set_requested_url($value)
    {
        $this->requested_url = $value;
    }

    public function set_from_url($value)
    {
        $this->from_url = $value;
    }

	public function set_times($value)
	{
		$this->times = $value;
	}

	public function increment()
	{
		$this->times++;
	}
}
?>
