<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 31
 * @since       PHPBoost 5.0 - 2017 04 18
*/

abstract class HTMLTableAjaxUserAutoCompleteFilter extends AbstractHTMLTableFilter
{
	private $match_regex;

	public function __construct($name, $label, $match_regex = null)
	{
		$this->match_regex = $match_regex;
		$input = new FormFieldAjaxUserAutoComplete($name, $label, '', array(), array(new FormFieldConstraintUserExist()));
		parent::__construct($name, $input);
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
