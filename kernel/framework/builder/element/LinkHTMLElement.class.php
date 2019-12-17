<?php
/**
 * @package     Builder
 * @subpackage  Element
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 19
 * @since       PHPBoost 4.1 - 2015 01 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class LinkHTMLElement extends AbstractHTMLElement
{
	private $url;
	private $content;
	private $attributs = array();

	public function __construct($url, $content, $attributs = array(), $css_class = '')
	{
		if ($url instanceof Url)
		{
			$url = $url->rel();
		}

		$this->url = $url;
		$this->content = $content;
		$this->attributs = $attributs;
		$this->css_class = $css_class;
	}

	public function display()
	{
		$tpl = new FileTemplate('framework/builder/element/LinkHTMLElement.tpl');

		$tpl->put_all(array(
			'C_HAS_CSS_CLASSES' => $this->has_css_class(),
			'CSS_CLASSES' => $this->get_css_class(),
			'URL' => $this->url,
			'CONTENT' => $this->content,
		));

		foreach ($this->attributs as $type => $value)
		{
			$tpl->assign_block_vars('attributs', array(
				'TYPE' => $type,
				'VALUE' => $value
			));
		}

		return $tpl->render();
	}
}
?>
