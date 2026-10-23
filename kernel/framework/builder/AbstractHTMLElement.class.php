<?php
/**
 * This class allows you to manage easily html elements.
 * @package     Builder
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 23
 * @since       PHPBoost 3.0 - 2009 12 21
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractHTMLElement implements HTMLElement
{
	protected string $css_class = '';
	protected string $css_style = '';
	protected string $id = '';

	public function has_css_style(): bool
	{
		return !empty($this->css_style);
	}

	public function get_css_style(): string
	{
		return $this->css_style;
	}

	public function set_css_style(string $style): void
	{
		$this->css_style = $style;
	}

	public function add_css_style(string $style): void
	{
		$this->css_style .= ' ' . $style;
	}

	public function has_css_class(): bool
	{
		return !empty($this->css_class);
	}

	public function get_css_class(): string
	{
		return $this->css_class;
	}

	public function set_css_class(string $class): void
	{
		$this->css_class = $class;
	}

	public function add_css_class(string $class): void
	{
		$this->css_class .= ' ' . $class;
	}

	public function has_id(): bool
	{
		return !empty($this->id);
	}

	public function get_id(): string
	{
		return $this->id;
	}

	public function set_id(string $id): void
	{
		$this->id = $id;
	}

	public function display(): mixed
    {
        return null;
    }
}
?>
