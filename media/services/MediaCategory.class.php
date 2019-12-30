<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 4.0 - 2015 02 04
*/

class MediaCategory extends RichCategory
{
	public static function __static()
	{
		parent::__static();
		self::add_additional_attribute('content_type', array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0, 'attribute_field_parameters' => array(
			'field_class' => 'FormFieldSimpleSelectChoice',
			'label' => LangLoader::get_message('content_type', 'common', 'media'),
			'options' => array(
				new FormFieldSelectChoiceOption(LangLoader::get_message('content_type.music_and_video', 'common', 'media'), MediaConfig::CONTENT_TYPE_MUSIC_AND_VIDEO),
				new FormFieldSelectChoiceOption(LangLoader::get_message('content_type.music', 'common', 'media'), MediaConfig::CONTENT_TYPE_MUSIC),
				new FormFieldSelectChoiceOption(LangLoader::get_message('content_type.video', 'common', 'media'), MediaConfig::CONTENT_TYPE_VIDEO)
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
