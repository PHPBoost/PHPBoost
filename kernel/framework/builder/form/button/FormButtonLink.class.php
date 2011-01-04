<?php
/*##################################################
 *                          FormButtonLink.class.php
 *                            -------------------
 *   begin                : October 30, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
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
 * @author Loic Rouchon <horn@phpboost.com>
 * @package {@package}
 */
class FormButtonLink extends FormButtonButton
{
    public function __construct($label, $link, $img = '', $field_options = array())
    {
    	$full_label = '';
    	if (!empty($img))
    	{
    		$full_label = '<img src="' . Url::to_rel($img) . '" alt="' . $label . '" title="' . $label . '" />';
    	}
    	else
    	{
    		$full_label = $label;
    	}
        parent::__construct($full_label, 'window.location=' . TextHelper::to_js_string(Url::to_absolute($link)), '', $field_options);
    }
}
?>