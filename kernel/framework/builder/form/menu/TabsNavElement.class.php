<?php
/**
 * This class manage action links.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 6.0 - 2025 03 07
*/

class TabsNavElement
{
	private string $title = '';
	private string $target = '';
	private string $class = '';
	private string $css_class = '';
	private string $active_module = '';
	private string|Url $img = '';

	/**
	 * build an action link
	 * @param string $title the action title
	 * @param string $target, the id of the target (don't use with #, it's sent to a data attribute)
	 * @param string $css_class the action font awesome css class
	 * @param string|Url $img the action icon url
	 * @param string $active_module the action active module
	 */
	public function __construct(string $title, string $target = '', string $css_class = '', string|Url $img = '', string $active_module = '', string $class = '')
	{
		$this->title = $title;
		$this->target = $target;
		$this->class = $class;
		$this->css_class = $css_class;
		$this->img = !empty($img) ? $this->convert_url($img) : $img;
		$this->active_module = $active_module;
	}

	public function get_title(): string
	{
		return $this->title;
	}

	public function get_target(): string
	{
		return $this->target;
	}

	public function has_class(): bool
	{
		return !empty($this->class);
	}

	public function get_class(): string
	{
		return $this->class;
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

	public function get_img(): mixed
	{
		return $this->img;
	}

	private function convert_url(string $url): mixed
	{
		return $url instanceof Url ? $url : new Url($url);
	}
}
?>
