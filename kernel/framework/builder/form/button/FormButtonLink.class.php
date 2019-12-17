<?php
/**
 * @package     Builder
 * @subpackage  Form
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 3.0 - 2010 10 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormButtonLink extends AbstractFormButton
{
	public function __construct($label, $link, $img = '', $css_class = '', $data_confirmation = '')
	{
		$full_label = $action = '';
		if (!empty($img))
		{
			$full_label = '<img src="' . $img . '" alt="' . $label . '" />';
		}
		else
		{
			$full_label = $label;
		}

		if ($data_confirmation)
			$action = 'javascript:if(confirm(\'' . $data_confirmation . '\')){window.location=' . TextHelper::to_js_string(Url::to_rel($link)) . ';return false;}';
		else
			$action = 'window.location=' . TextHelper::to_js_string(Url::to_rel($link));

		parent::__construct('button', $full_label, '', $action, !empty($img) ? 'image' : $css_class);
	}
}
?>
