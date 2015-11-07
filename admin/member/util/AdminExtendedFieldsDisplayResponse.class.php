<?php
/*##################################################
 *                           AdminExtendedFieldsDisplayResponse.class.php
 *                            -------------------
 *   begin                : April 18, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
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

class AdminExtendedFieldsDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-user-common');
		$this->set_title($lang['extended-field']);
		
		$this->add_link($lang['extended-fields-management'], AdminExtendedFieldsUrlBuilder::fields_list());
		$this->add_link($lang['extended-field-add'], AdminExtendedFieldsUrlBuilder::add());
		
		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>