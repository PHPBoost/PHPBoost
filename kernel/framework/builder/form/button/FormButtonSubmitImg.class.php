<?php
/*##################################################
 *                        FormButtonSubmitImg.class.php
 *                            -------------------
 *   begin                : October 03, 2010
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
class FormButtonSubmitImg extends FormButtonSubmit
{
    public function __construct($label, $image, $id, $onclick_action = '', $css_class = 'img_submit')
    {
    	$full_label = '<img src="' . $image . '" alt="' . $label . '" title="' . $label . '" />';
        parent::__construct($full_label, $id, $id, $onclick_action);
    }
}
?>