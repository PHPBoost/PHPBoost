<?php
/**
 * This class represents a filter based on string comparison
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2011 03 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class MenuStringFilter extends Filter
{
	public function __construct($pattern)
	{
		parent::__construct($pattern);
	}

	public function match()
	{
		if (TextHelper::substr($this->pattern, -10) == '/index.php')
		{
			return Url::is_current_url('/' . TextHelper::substr($this->pattern, 0, -9), true) || Url::is_current_url($this->pattern);
		}
		else
			return Url::is_current_url($this->pattern);
	}
}
?>
