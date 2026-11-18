<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 5.1 - 2017 11 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonButtonCssImg extends AbstractFormButton
{
	public function __construct(mixed $value, string $onclick_action = '', string $name = '', string $css_class_image = '', string $text_button = '', string $css_class = '', string $data_confirmation = '', string $form_id = '')
	{
		$full_label = '';
		if (!empty($css_class_image))
		{
			$full_label = '<i class="' . $css_class_image . '" aria-label="' . $value . '" aria-hidden="true"></i><span class="sr-only">' . $value . '</span>' . $text_button;
		}
		else
		{
			$full_label = $value;
		}
		parent::__construct('button', $full_label, $name, $onclick_action, $css_class, $data_confirmation, $form_id);
	}
}
?>
