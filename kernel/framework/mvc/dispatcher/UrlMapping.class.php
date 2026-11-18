<?php
/**
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2010 10 06
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UrlMapping
{
    private string $to = '';
    private string $from = '';
    private string $options = '';

	public function __construct(string $from, string $to, string $options = 'L,NC,QSA')
	{
        $this->to = $to;
        $this->from = $from;
        $this->options = $options;
	}

    public function from(): string
    {
        return $this->from;
    }

    public function to(): string
    {
        return $this->to;
    }

    public function options(): string
    {
        return $this->options;
    }
}
?>
