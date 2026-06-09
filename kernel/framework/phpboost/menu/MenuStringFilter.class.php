<?php
/**
 * This class represents a filter based on string comparison
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 03 06
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class MenuStringFilter extends Filter
{
	public function __construct($pattern)
	{
		parent::__construct($pattern);
	}

	public function match()
	{
        if ($this->pattern === null)
        {
            return false;
        }

        if (TextHelper::substr($this->pattern, -10) == '/index.php')
		{
			return Url::is_current_url('/' . TextHelper::substr($this->pattern, 0, -9), true) || Url::is_current_url($this->pattern);
		}
		else
			return Url::is_current_url($this->pattern);
	}
}
?>
