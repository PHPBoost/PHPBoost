<?php
/**
 * @package     Builder
 * @subpackage  Element
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 22
 * @since       PHPBoost 6.0 - 2019 12 20
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EditLinkHTMLElement extends LinkHTMLElement
{
	public function __construct($url, $content = '', $attributes = array(), $css_class = '')
	{
		parent::__construct($url, $content, array_merge(array('aria-label' => LangLoader::get_message('common.edit', 'common-lang')), $attributes), 'far fa-fw fa-edit' . ($css_class ? ' ' . $css_class : ''), true);
	}
}
?>
