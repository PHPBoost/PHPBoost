<?php
/**
 * This class is done to time a process easily. You choose when to start and when to stop.
 * @package     Util
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 1.4 - 2006 03 14
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class Bench
{
	/**
	 * @var bool
	 */
	private $started = false;

	/**
	 * @access protected
	 * @var int start microtime
	 */
	private $start = 0;
	/**
	 * @access protected
	 * @var int duration microtime
	 */
	private $duration = 0;

	/**
	 * returns the number formatted with $digits floating numbers
	 * @param int $digits the desired display precision
	 * @return string the formatted duration
	 */
	public function to_string($digits = 3)
	{
		if ($this->started)
		{
			$this->duration += $this->get_delta_duration();
			$this->start();
		}
		return number_format($this->duration, $digits);
	}

	/**
	 * stops the bench
	 */
	public function stop()
	{
		$this->duration += $this->get_delta_duration();
		$this->started = false;
	}

	/**
	 * starts the bench
	 */
	public function start()
	{
		$this->start = Bench::get_microtime();
		$this->started = true;
	}

	/**
	 * Returns the amount of memory, that's currently being allocated to PHP script.
	 */
	public function get_memory_php_used()
	{
		$size = memory_get_usage(true);
		$unit = array('B','KB','MB','GB','TB','PB');
		return @round($size/pow(1024,($i = floor(log($size,1024)))), 2) . ' ' . $unit[$i];
	}

	private function get_delta_duration()
	{
		return Bench::get_microtime() - $this->start;
	}

	/**
	 * @static
	 * computes the time with a microsecond precision
	 * @access protected
	 * @return float
	 */
	private function get_microtime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}
?>
