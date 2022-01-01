<?php
/**
* This class allows you to manage easily html tables.
 * @package     Builder
 * @subpackage  Table
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 02 25
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class HTMLTableColumn extends HTMLTableRowCell
{
	private $sortable_parameter = '';
	private $name_sr_only = false;

	public function __construct($name, $sortable_parameter = '', $options = array())
	{
		$this->sortable_parameter = $sortable_parameter;
		$css_class = isset($options['css_class']) ? $options['css_class'] : '';
		$id = isset($options['id']) ? $options['id'] : '';
		$this->name_sr_only = isset($options['sr-only']) ? $options['sr-only'] : $this->name_sr_only;
		parent::__construct($name, $css_class, $id);
	}

	public function is_sortable()
	{
		return !empty($this->sortable_parameter);
	}

	public function get_sortable_parameter()
	{
		return $this->sortable_parameter;
	}

	public function is_name_sr_only()
	{
		return $this->name_sr_only;
	}
}

?>
