<?php
/*##################################################
 *                           AdminContactDisplayResponse.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class AdminContactDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);
		
		$lang = LangLoader::get('contact_common', 'contact');
		$picture = '/contact/contact.png';
		$this->set_title($lang['title_contact']);
		$this->add_link($lang['contact_config'], ContactUrlBuilder::configuration(), $picture);
		$this->add_link($lang['contact_config.fields.manage'], ContactUrlBuilder::manage_fields(), $picture);
		$this->add_link($lang['contact_config.fields.add'], ContactUrlBuilder::add_field(), $picture);
		
		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>