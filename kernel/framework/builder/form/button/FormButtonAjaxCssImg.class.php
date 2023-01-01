<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 18
 * @since       PHPBoost 4.0 - 2014 05 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class FormButtonAjaxCssImg extends AbstractFormButton
{
    public function __construct($label, AjaxRequest $request, array $fields, $css_class_image = '', $condition = null)
    {
		$full_label = '';
		if (!empty($css_class_image))
		{
			$full_label = '<i class="' . $css_class_image . '" aria-label="' . $label . '" aria-hidden="true"></i><span class="sr-only">' . $label . '</span>';
		}
		else
		{
			$full_label = $label;
		}
        parent::__construct('button', $full_label, '', $this->build_ajax_request($request, $fields, $condition), 'image');
    }

    private function build_ajax_request(AjaxRequest $request, array $fields, $condition)
    {
		if (is_array($fields))
		{
			foreach ($fields as $field)
			{
				$request->add_param($field->get_id(), '$FF(\'' . $field->get_id() . '\').getValue()');
			}
		}
		if (!empty($condition))
		{
			return 'if (' . $condition . '){' . $request->render() . '}';
		}
		return $request->render();
    }
}
?>
