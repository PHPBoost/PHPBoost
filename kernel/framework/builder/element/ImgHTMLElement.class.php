<?php
/**
 * @package     Builder
 * @subpackage  Element
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.1 - 2015 01 19
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ImgHTMLElement extends AbstractHTMLElement
{
	private $url;
	private $attributes = [];

	public function __construct($url, $attributes = [], $css_class = '')
	{
		if ($url instanceof Url)
		{
			$url = $url->rel();
		}

		$this->url = $url;
		$this->attributes = $attributes;
		$this->css_class = $css_class;
	}

	public function display()
	{
		$tpl = new FileTemplate('framework/builder/element/ImgHTMLElement.tpl');

		$tpl->put_all([
			'C_HAS_CSS_CLASSES' => $this->has_css_class(),
			'CSS_CLASSES' => $this->get_css_class(),
			'URL' => $this->url
		]);

		foreach ($this->attributes as $type => $value)
		{
			$tpl->assign_block_vars('attributes', [
				'TYPE' => $type,
				'VALUE' => $value
			]);
		}

		return $tpl->render();
	}
}
?>
