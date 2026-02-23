<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2026 02 23
 * @since       PHPBoost 6.0 - 2026 02 23
*/

class HighPriorityUrlMapping extends DispatcherUrlMapping
{
    private string $from;
    private string $to;
    private string $options;

    public function __construct(string $from, string $to, string $options = 'L,QSA')
    {
        $this->from = $from;
        $this->to = $to;
        $this->options = $options;
    }

    public function from(): string    { return $this->from; }
    public function to(): string      { return $this->to; }
    public function options(): string { return $this->options; }

    public function is_high_priority(): bool { return true; }
    public function is_low_priority(): bool  { return false; }
}