<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 14
 * @since       PHPBoost 3.0 - 2010 02 25
*/

abstract class HTMLTableTextFilter extends AbstractHTMLTableFilter
{
	private $match_regex;

	public function __construct($name, $label, $match_regex = null)
	{
		$this->match_regex = $match_regex;
		$input_text = new FormFieldTextEditor($name, $label, '');
		parent::__construct($name, $input_text);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
	{
		if (empty($this->match_regex) || preg_match($this->match_regex, $value))
		{
			$this->set_value($value);
			return true;
		}
		return false;
	}
}

?>
