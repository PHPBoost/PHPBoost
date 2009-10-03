<?php
/*##################################################
*                                bench.class.php
*                            -------------------
*   begin                : March 14, 2006
*   copyright            : (C) 2005 Régis Viarre, Loïc Rouchon
*   email                : crowkait@phpboost.com, horn@phpboost.com
*
###################################################
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
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
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc This class is done to time a process easily. You choose when to start and when to stop.
 * @package util
 */
class Bench
{
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
	 * @desc starts the bench now
	 */
    public function __construct()
    {
    	$this->start = $this->get_microtime();
    }
    
    /**
	 * @desc returns the number formatted with $digits floating numbers
	 * @param int $digits the desired display precision
	 * @return string the formatted duration
	 */
    public function to_string($digits = 3) 
    {
    	$this->stop();
    	return number_round($this->duration, $digits); 
    }

    /**
     * @desc stops the bench now
     */
    private function stop()
    {
        $this->duration = Bench::get_microtime() - $this->start; 
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
