<?php
/*##################################################
 *                              CalendarCategoriesFormController.class.php
 *                            -------------------
 *   begin                : February 25, 2013
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

class CalendarCategoriesFormController extends AbstractRichCategoriesFormController
{
	protected function generate_response(View $view)
	{
		$page_name = $this->get_id_category() == 0 ? LangLoader::get_message('calendar.config.category.add', 'calendar_common', 'calendar') : LangLoader::get_message('calendar.config.category.edit', 'calendar_common', 'calendar');
		return new AdminCalendarDisplayResponse($view, $page_name);
	}

	protected function get_categories_manager()
	{
		return CalendarService::get_categories_manager();
	}

	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_management_url()
	{
		return CalendarUrlBuilder::manage_categories();
	}
	
	protected function get_options_fields(FormFieldset $fieldset)
	{
		parent::get_options_fields($fieldset);
		$fieldset->add_field(new FormFieldColorPicker('color', LangLoader::get_message('calendar.config.category.color', 'calendar_common', 'calendar'), $this->get_category()->get_color()));
	}

	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_color($this->form->get_value('color'));
	}
}
?>