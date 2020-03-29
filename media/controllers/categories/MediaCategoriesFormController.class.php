<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 07
 * @since       PHPBoost 4.1 - 2015 02 04
*/

class MediaCategoriesFormController extends DefaultRichCategoriesFormController
{
	protected function get_categories_manager()
	{
		return CategoriesService::get_categories_manager('media', 'idcat');
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
}
?>
