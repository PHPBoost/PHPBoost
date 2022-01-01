<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 07
 * @since       PHPBoost 4.0 - 2015 02 04
*/

class MediaCategory extends RichCategory
{
	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('content_type', array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0, 'attribute_field_parameters' => array(
			'field_class' => 'FormFieldSimpleSelectChoice',
			'label' => LangLoader::get_message('media.content.type', 'common', 'media'),
			'options' => array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('media.content.type.music.and.video', 'common', 'media'), MediaConfig::CONTENT_TYPE_MUSIC_AND_VIDEO),
				new FormFieldSelectChoiceOption(LangLoader::get_message('media.content.type.music', 'common', 'media'), MediaConfig::CONTENT_TYPE_MUSIC),
				new FormFieldSelectChoiceOption(LangLoader::get_message('media.content.type.video', 'common', 'media'), MediaConfig::CONTENT_TYPE_VIDEO)
				)
			)
		));
	}

	public function get_content_type()
	{
		return $this->get_additional_property('content_type');
	}
}
?>
