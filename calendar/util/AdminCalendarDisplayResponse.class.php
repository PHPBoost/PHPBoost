<?php
/*##################################################
 *                           AdminCalendarDisplayResponse.class.php
 *                            -------------------
 *   begin                : November 20, 2012
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
 * @desc AdminMenuDisplayResponse of the calendar module
 */
class AdminCalendarDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);
		
		$lang = LangLoader::get('calendar_common', 'calendar');
		$picture = '/calendar/calendar.png';
		$this->set_title($lang['calendar.module_title']);
		$this->add_link($lang['calendar.config.category.manage'], CalendarUrlBuilder::manage_categories(), $picture);
		$this->add_link($lang['calendar.config.category.add'], CalendarUrlBuilder::add_category(), $picture);
		$this->add_link($lang['calendar.titles.admin.config'], CalendarUrlBuilder::configuration(), $picture);
		
		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>