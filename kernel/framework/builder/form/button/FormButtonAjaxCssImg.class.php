<?php
/*##################################################
 *                          FormButtonAjaxCssImg.class.php
 *                            -------------------
 *   begin                : May 08, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 *
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @package {@package}
 */
class FormButtonAjaxCssImg extends AbstractFormButton
{
    public function __construct($label, AjaxRequest $request, $css_class_image = '', array $fields, $condition = null)
    {
    	$full_label = '';
    	if (!empty($css_class_image))
    	{
    		$full_label = '<i class="' . $css_class_image . '" title="' . $label . '"></i>';
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