<?php
/**
 * This class allows you to manage easily html elements.
 * @package     Builder
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 05 27
 * @since       PHPBoost 3.0 - 2009 12 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class AbstractHTMLElement implements HTMLElement
{
	protected $css_class = '';
	protected $css_style = '';
	protected $id = '';

	public function has_css_style()
	{
		return !empty($this->css_style);
	}

	public function get_css_style()
	{
		return $this->css_style;
	}

	public function set_css_style($style)
	{
		$this->css_style = $style;
	}

	public function add_css_style($style)
	{
		$this->css_style .= ' ' . $style;
	}

	public function has_css_class()
	{
		return !empty($this->css_class);
	}

	public function get_css_class()
	{
		return $this->css_class;
	}

	public function set_css_class($class)
	{
		$this->css_class = $class;
	}

	public function add_css_class($class)
	{
		$this->css_class .= ' ' . $class;
	}

	public function has_id()
	{
		return !empty($this->id);
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function display(){}
}
?>
