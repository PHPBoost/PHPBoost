<?php
/**
 * This class manage action links.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2010 04 14
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldActionLinkElement
{
	private string $title = '';
	private mixed $url = null;
	private string $css_class = '';
	private string $active_module = '';
	private mixed $img = null;
	private string $fa_icon = '';

	/**
	 * build an action link
	 * @param string $title the action title
	 * @param Url $url the action url
	 * @param string $css_class the action font awesome css class
	 * @param Url $img the action icon url
	 * @param string $active_module the action active module
	 */
	public function __construct(string $title, mixed $url, string $css_class = '', mixed $img = '', string $active_module = '', string $fa_icon = '')
	{
		$this->title = $title;
		$this->url = $this->convert_url($url);
		$this->css_class = $css_class;
		$this->img = !empty($img) ? $this->convert_url($img) : $img;
		$this->active_module = $active_module;
		$this->fa_icon = $fa_icon;
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function get_url(): Url
	{
		return $this->url;
	}

	public function has_css_class(): bool
	{
		return !empty($this->css_class);
	}

	public function get_css_class(): string
	{
		return $this->css_class;
	}

	public function is_active_module(): bool
	{
		return !empty($this->active_module);
	}

	public function get_active_module(): string
	{
		return $this->active_module;
	}

	public function has_img(): bool
	{
		return !empty($this->img);
	}

	public function get_img(): Url
	{
		return $this->img;
	}

	public function has_fa_icon(): bool
	{
		return !empty($this->fa_icon);
	}

	public function get_fa_icon(): string
	{
		return $this->fa_icon;
	}

	private function convert_url(mixed $url): mixed
	{
		return $url instanceof Url ? $url : new Url($url);
	}
}
?>
