<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 19
 * @since   	PHPBoost 3.0 - 2013 02 25
*/

class CalendarCategoriesFormController extends AbstractCategoriesFormController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return CalendarService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return CalendarUrlBuilder::manage_categories();
	}

	protected function get_add_category_url()
	{
		return CalendarUrlBuilder::add_category();
	}

	protected function get_edit_category_url(Category $category)
	{
		return CalendarUrlBuilder::edit_category($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return CalendarUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module_title', 'common', 'calendar');
	}

	protected function get_options_fields(FormFieldset $fieldset)
	{
		parent::get_options_fields($fieldset);
		$fieldset->add_field(new FormFieldColorPicker('color', LangLoader::get_message('calendar.config.category.color', 'common', 'calendar'), $this->get_category()->get_color()));
	}

	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_color($this->form->get_value('color'));
	}

	protected function check_authorizations()
	{
		if (!CalendarAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
