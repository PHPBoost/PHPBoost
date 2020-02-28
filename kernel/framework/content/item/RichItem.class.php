<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 28
 * @since       PHPBoost 5.3 - 2020 01 23
*/

class RichItem extends Item
{
	const THUMBNAIL_URL = '/templates/default/images/default_item_thumbnail.png';
	
	public static function __static()
	{
		parent::__static();
		self::add_additional_attribute('summary', array('type' => 'text', 'length' => 65000, 'fulltext' => true));
		self::add_additional_attribute('thumbnail', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''"));
		self::add_additional_attribute('author_custom_name', array('type' =>  'string', 'length' => 255, 'default' => "''"));
		self::add_additional_attribute('views_number', array('type' => 'integer', 'length' => 11, 'default' => 0));
	}

	public function get_summary()
	{
		return $this->get_additional_property('summary');
	}

	public function set_summary($value)
	{
		$this->set_additional_property('summary', $value);
	}

	public function get_real_summary($parsed_content = '')
	{
		$summary = $this->get_additional_property('summary');
		
		if (!empty($summary))
		{
			return FormatingHelper::second_parse($summary);
		}
		return TextHelper::cut_string(@strip_tags($parsed_content ? $parsed_content : FormatingHelper::second_parse($this->content), '<br><br/>'), self::$module->get_configuration()->get_configuration_parameters()->get_auto_cut_characters_number());
	}

	public function get_thumbnail()
	{
		if (!$this->get_additional_property('thumbnail') instanceof Url)
			return new Url($this->get_additional_property('thumbnail') == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->get_additional_property('thumbnail'));

		return $this->get_additional_property('thumbnail');
	}

	public function has_thumbnail()
	{
		$thumbnail = ($this->get_additional_property('thumbnail') instanceof Url) ? $this->get_additional_property('thumbnail')->rel() : $this->get_additional_property('thumbnail');
		return !empty($thumbnail);
	}

	public function set_thumbnail($url)
	{
		$this->set_additional_property('thumbnail', $url);
	}

	public function get_author_custom_name()
	{
		return $this->get_additional_property('author_custom_name');
	}

	public function set_author_custom_name($value)
	{
		$this->set_additional_property('author_custom_name', $value);
	}

	public function is_author_custom_name_enabled()
	{
		$custom_name = $this->get_additional_property('author_custom_name');
		return !empty($custom_name) && $custom_name != $this->get_author_user()->get_display_name();
	}

	public function get_views_number()
	{
		return $this->get_additional_property('views_number');
	}

	public function set_views_number(int $number)
	{
		$this->set_additional_property('views_number', $number);
	}

	protected function kernel_default_properties()
	{
		$this->set_summary('');
		$this->set_thumbnail(FormFieldThumbnail::DEFAULT_VALUE);
		$this->set_author_custom_name('');
		$this->set_views_number(0);
	}

	protected static function get_kernel_additional_sorting_fields()
	{
		return array('views' => array('database_field' => 'views_number', 'label' => LangLoader::get_message('sort_by.views.number', 'common'), 'icon' => 'fa fa-eye'));
	}

	protected function get_kernel_additional_template_vars($parsed_content = '')
	{
		$content = $parsed_content ? $parsed_content : FormatingHelper::second_parse($this->content);
		$summary = $this->get_real_summary($content);
		
		return array(
			// Conditions
			'C_HAS_THUMBNAIL'      => $this->has_thumbnail(),
			'C_AUTHOR_CUSTOM_NAME' => $this->is_author_custom_name_enabled(),
			'C_READ_MORE'          => !$this->get_additional_property('summary') && TextHelper::strlen($content) > self::$module->get_configuration()->get_configuration_parameters()->get_auto_cut_characters_number() && $summary != @strip_tags($content, '<br><br/>'),
			
			// Item parameters
			'SUMMARY'              => $summary,
			'AUTHOR_CUSTOM_NAME'   => $this->get_author_custom_name(),
			'VIEWS_NUMBER'         => $this->get_views_number(),
			
			// Links
			'U_THUMBNAIL'          => $this->get_thumbnail()->rel(),
		);
	}
}
?>
