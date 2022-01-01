<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 08 14
 * @since       PHPBoost 4.1 - 2014 05 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonLinkCssImg extends AbstractFormButton
{
	public function __construct($label, $link, $css_class_image = '', $text_button = '')
	{
		$full_label = '';
		if (!empty($css_class_image))
		{
			$full_label = '<i class="' . $css_class_image . '" aria-label="' . $label . '" aria-hidden="true"></i><span class="sr-only">' . $label . '</span> ' . $text_button;
		}
		else
		{
			$full_label = $label;
		}
		parent::__construct('button', $full_label, '', 'window.location=' . TextHelper::to_js_string(Url::to_rel($link)), 'image');
	}
}
?>
