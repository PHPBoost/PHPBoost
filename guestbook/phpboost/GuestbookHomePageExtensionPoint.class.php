<?php
/*##################################################
 *                     GuestbookHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 08, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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

/**
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 * @desc Home page of the guestbook module
 */
class GuestbookHomePageExtensionPoint implements HomePageExtensionPoint
{
	 /**
	 * @method Get the module home page
	 */
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	 /**
	 * @method Get the module title
	 */
	private function get_title()
	{
		return LangLoader::get_message('guestbook.module_title', 'guestbook_common', 'guestbook');
	}
	
	 /**
	 * @method Get the module view
	 */
	private function get_view()
	{
		return GuestbookModuleHomePage::get_view();
	}
}
?>