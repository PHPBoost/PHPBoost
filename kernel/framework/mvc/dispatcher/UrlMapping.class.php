<?php
/**
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 06
*/

class UrlMapping
{
    private $to;
    private $from;
    private $options;

	/**
	 * @param UrlMapping[] $mappings
	 */
	public function __construct($from, $to, $options = 'L,QSA')
	{
        $this->to = $to;
        $this->from = $from;
        $this->options = $options;
	}

    /**
     * {@inheritdoc}
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function to()
    {
        return $this->to;
    }

	/**
     * {@inheritdoc}
     */
    public function options()
    {
        return $this->options;
    }
}
?>
