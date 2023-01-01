<?php
/**
 * @package     Builder
 * @subpackage  Element
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 20
 * @since       PHPBoost 4.1 - 2015 05 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class DivHTMLElement extends AbstractHTMLElement
{
	private $content;
	private $attributes = array();

	public function __construct($content, $attributes = array(), $css_class = '')
	{
		$this->content = $content;
		$this->attributes = $attributes;
		$this->css_class = $css_class;
	}

	public function display()
	{
		$tpl = new FileTemplate('framework/builder/element/DivHTMLElement.tpl');

		$tpl->put_all(array(
			'C_HAS_CSS_CLASSES' => $this->has_css_class(),
			'CSS_CLASSES' => $this->get_css_class(),
			'CONTENT' => $this->content
		));

		foreach ($this->attributes as $type => $value)
		{
			$tpl->assign_block_vars('attributes', array(
				'TYPE' => $type,
				'VALUE' => $value
			));
		}

		return $tpl->render();
	}
}
?>
