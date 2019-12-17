<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 02
*/

class TemplateSyntaxParserContext
{
	private $loops = array();

	public function enter_loop($name)
	{
		$this->loops[] = $name;
	}

	public function exit_loop()
	{
		array_pop($this->loops);
	}

	public function is_in_loop($name)
	{
		return in_array($name, $this->loops);
	}

	public function loops_scopes()
	{
		return $this->loops;
	}
}

?>
