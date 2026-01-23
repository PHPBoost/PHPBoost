<?php
/**
 * This class is used to time a process easily. You choose when to start and when to stop.
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2016 10 24
 * @since       PHPBoost 1.4 - 2006 03 14
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Bench
{
    /**
     * @var bool
     */
    private bool $started = false;

    /**
     * @var float Start microtime
     */
    private float $start = 0.0;

    /**
     * @var float Duration microtime
     */
    private float $duration = 0.0;

    /**
     * Returns the number formatted with $digits floating numbers.
     *
     * @param int $digits The desired display precision
     * @return string The formatted duration
     */
    public function to_string(int $digits = 3): string
    {
        if ($this->started)
        {
            $this->duration += $this->get_delta_duration();
            $this->start();
        }
        return number_format($this->duration, $digits);
    }

    /**
     * Stops the bench.
     */
    public function stop(): void
    {
        $this->duration += $this->get_delta_duration();
        $this->started = false;
    }

    /**
     * Starts the bench.
     */
    public function start(): void
    {
        $this->start = self::get_microtime();
        $this->started = true;
    }

    /**
     * Returns the amount of memory that's currently being allocated to the PHP script.
     *
     * @return string The formatted memory usage
     */
    public function get_memory_php_used(): string
    {
        $size = memory_get_usage(true);
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $i = (int)floor(log($size, 1024));
        return round($size / pow(1024, $i), 2) . ' ' . $unit[$i];
    }

    /**
     * Gets the delta duration.
     *
     * @return float The delta duration
     */
    private function get_delta_duration(): float
    {
        return self::get_microtime() - $this->start;
    }

    /**
     * Computes the time with microsecond precision.
     *
     * @return float The current microtime
     */
    private static function get_microtime(): float
    {
        $time = explode(" ", microtime());
        return (float)$time[0] + (float)$time[1];
    }
}
?>
