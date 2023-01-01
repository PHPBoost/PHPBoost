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

class DeleteLinkHTMLElement extends LinkHTMLElement
{
	public function __construct($url, $content = '', $attributes = array(), $css_class = '')
	{
		$local_attributes = array();

		if (isset($attributes['disabled']) && $attributes['disabled'] == true)
		{
			$css_class = $css_class . ($css_class ? ' ' : '') . 'icon-disabled';
			unset($attributes['disabled']);
		}
		else
			$local_attributes['data-confirmation'] = 'delete-element';

		parent::__construct($url, $content, array_merge(array('aria-label' => LangLoader::get_message('common.delete', 'common-lang')), $local_attributes, $attributes), 'far fa-fw fa-trash-alt' . ($css_class ? ' ' . $css_class : ''), true);
	}
}
?>
