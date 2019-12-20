<?php
/**
 * @package     Builder
 * @subpackage  Element
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 20
 * @since       PHPBoost 5.3 - 2019 12 20
*/

class EditLinkHTMLElement extends LinkHTMLElement
{
	public function __construct($url, $content = '', $attributes = array(), $css_class = '')
	{
		parent::__construct($url, $content, array_merge(array('aria-label' => LangLoader::get_message('edit', 'common')), $attributes), 'far fa-fw fa-edit' . ($css_class ? ' ' . $css_class : ''), true);
	}
}
?>
