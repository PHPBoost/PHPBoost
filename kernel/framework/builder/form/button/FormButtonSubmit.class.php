<?php
/*##################################################
 *                        FormButtonSubmit.class.php
 *                            -------------------
 *   begin                : February 16, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class FormButtonSubmit extends AbstractFormButton
{
    public function __construct($value, $name, $onclick_action = '', $css_class = 'submit', $data_confirmation = '')
    {
        parent::__construct('submit', $value, $name, $onclick_action, $css_class, $data_confirmation);
    }

    public function has_been_submited()
    {
        $request = AppContext::get_request();
        $button_attribute = $request->get_string($this->get_html_name(), '');
        return !empty($button_attribute);
    }
}
?>