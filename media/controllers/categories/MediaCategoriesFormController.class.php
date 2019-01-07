<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 15
 * @since   	PHPBoost 4.1 - 2015 02 04
*/

class MediaCategoriesFormController extends AbstractRichCategoriesFormController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return MediaService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return MediaUrlBuilder::manage_categories();
	}

	protected function get_add_category_url()
	{
		return MediaUrlBuilder::add_category();
	}

	protected function get_edit_category_url(Category $category)
	{
		return MediaUrlBuilder::edit_category($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return MediaUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module_title', 'common', 'media');
	}

	protected function get_options_fields(FormFieldset $fieldset)
	{
		parent::get_options_fields($fieldset);
		$fieldset->add_field(new FormFieldSimpleSelectChoice('content_type', LangLoader::get_message('content_type', 'common', 'media'), $this->get_category()->get_content_type(),
			array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('content_type.music_and_video', 'common', 'media'), MediaConfig::CONTENT_TYPE_MUSIC_AND_VIDEO),
				new FormFieldSelectChoiceOption(LangLoader::get_message('content_type.music', 'common', 'media'), MediaConfig::CONTENT_TYPE_MUSIC),
				new FormFieldSelectChoiceOption(LangLoader::get_message('content_type.video', 'common', 'media'), MediaConfig::CONTENT_TYPE_VIDEO)
			)
		));
	}

	protected function set_properties()
	{
		parent::set_properties();
		$this->get_category()->set_content_type($this->form->get_value('content_type')->get_raw_value());
	}

	protected function check_authorizations()
	{
		if (!MediaAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
