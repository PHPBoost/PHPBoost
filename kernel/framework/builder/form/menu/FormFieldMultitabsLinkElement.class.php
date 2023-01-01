<?php
/**
 * This class manage action links.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 03 13
 * @since       PHPBoost 5.2 - 2019 10 11
*/

class FormFieldMultitabsLinkElement
{
	private $title;
	private $url;
	private $trigger;
	private $target;
	private $class;
	private $css_class;
	private $active_module;
	private $img;

	/**
	 * build an action link
	 * @param string $title the action title
	 * @param string $target, the id of the target (don't use with #, it's sent to a data attribute)
	 * @param string $css_class the action font awesome css class
	 * @param Url $img the action icon url
	 * @param string $active_module the action active module
	 */
	public function __construct($title, $trigger = '', $target = '', $css_class = '', $img = '', $active_module = '', $class = '')
	{
		$this->title = $title;
		$this->trigger = $trigger;
		$this->target = $target;
		$this->class = $class;
		$this->css_class = $css_class;
		$this->img = !empty($img) ? $this->convert_url($img) : $img;
		$this->active_module = $active_module;
	}

	/**
	 * @return string
	 */
	public function get_title()
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function get_trigger()
	{
		return $this->trigger;
	}

	/**
	 * @return string
	 */
	public function get_target()
	{
		return $this->target;
	}

	/**
	 * @return bool
	 */
	public function has_class()
	{
		return !empty($this->class);
	}

	/**
	 * @return string
	 */
	public function get_class()
	{
		return $this->class;
	}

	/**
	 * @return bool
	 */
	public function has_css_class()
	{
		return !empty($this->css_class);
	}

	/**
	 * @return string
	 */
	public function get_css_class()
	{
		return $this->css_class;
	}

	/**
	 * @return bool
	 */
	public function is_active_module()
	{
		return !empty($this->active_module);
	}

	/**
	 * @return string
	 */
	public function get_active_module()
	{
		return $this->active_module;
	}

	/**
	 * @return bool
	 */
	public function has_img()
	{
		return !empty($this->img);
	}

	/**
	 * @return Url
	 */
	public function get_img()
	{
		return $this->img;
	}

	private function convert_url($url)
	{
		return $url instanceof Url ? $url : new Url($url);
	}
}
?>
