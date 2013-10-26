<?php
/*##################################################
 *                               Bench.class.php
 *                            -------------------
 *   begin                : March 14, 2006
 *   copyright            : (C) 2005 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc This class is done to time a process easily. You choose when to start and when to stop.
 * @package {@package}
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
	 * @desc returns the number formatted with $digits floating numbers
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
	 * @desc stops the bench
	 */
	public function stop()
	{
		$this->duration += $this->get_delta_duration();
		$this->started = false;
	}

	/**
	 * @desc starts the bench
	 */
	public function start()
	{
		$this->start = Bench::get_microtime();
		$this->started = true;
	}
	
	/**
	 * @desc Returns the amount of memory, that's currently being allocated to PHP script.
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
	 * @desc computes the time with a microsecond precision
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