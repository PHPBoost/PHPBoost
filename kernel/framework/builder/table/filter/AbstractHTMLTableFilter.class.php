<?php
/**
 * @package     Builder
 * @subpackage  Table\filter
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 22
*/

abstract class AbstractHTMLTableFilter implements HTMLTableFilter
{
	private $id;
	/**
	 * @var FormField
	 */
	private $field;

	public function __construct($id, FormField $field)
	{
		$this->id = $id;
		$this->field = $field;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function get_form_field()
	{
		return $this->field;
	}

	protected function set_value($value)
	{
		$this->field->set_value($value);
	}

	protected function get_value()
	{
		return $this->field->get_value();
	}
}

?>
