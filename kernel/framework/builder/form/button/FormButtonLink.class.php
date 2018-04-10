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
class FormButtonLink extends AbstractFormButton
{
	public function __construct($label, $link, $img = '', $css_class = '', $data_confirmation = '')
	{
		$full_label = $action = '';
		if (!empty($img))
		{
			$full_label = '<img src="' . $img . '" alt="' . $label . '" title="' . $label . '" />';
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