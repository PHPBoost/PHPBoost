<?php
/**
 * @package     Content
 * @subpackage  Item
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 6.0 - 2020 01 23
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class RichItem extends Item
{
	protected $summary_field_enabled            = true;
	protected $author_custom_name_field_enabled = true;
	protected $thumbnail_field_enabled          = true;

	const THUMBNAIL_URL = '/templates/__default__/images/default_item.webp';

	protected function set_kernel_additional_attributes_list()
	{
		$this->add_additional_attribute('views_number', array('type' => 'integer', 'length' => 11, 'default' => 0));

		if ($this->content_field_enabled && $this->summary_field_enabled)
			$this->add_additional_attribute('summary', array('type' => 'text', 'length' => 65000, 'fulltext' => true));

		if ($this->author_custom_name_field_enabled)
			$this->add_additional_attribute('author_custom_name', array('type' => 'string', 'length' => 255, 'default' => "''"));

		if ($this->thumbnail_field_enabled)
			$this->add_additional_attribute('thumbnail', array('type' => 'string', 'length' => 255, 'notnull' => 1, 'default' => "''", 'attribute_options_field_parameters' => array(
				'field_class'     => 'FormFieldThumbnail',
				'label'           => LangLoader::get_message('common.image', 'common-lang'),
				'value'           => FormFieldThumbnail::DEFAULT_VALUE,
				'default_picture' => self::THUMBNAIL_URL
				)
			));
	}

	protected function disable_summary_field()
	{
		$this->summary_field_enabled = false;
	}

	public function summary_field_enabled()
	{
		return $this->summary_field_enabled;
	}

	protected function disable_author_custom_name_field()
	{
		$this->author_custom_name_field_enabled = false;
	}

	public function author_custom_name_field_enabled()
	{
		return $this->author_custom_name_field_enabled;
	}

	protected function disable_thumbnail_field()
	{
		$this->thumbnail_field_enabled = false;
	}

	public function thumbnail_field_enabled()
	{
		return $this->thumbnail_field_enabled;
	}

	public function get_views_number()
	{
		return $this->get_additional_property('views_number');
	}

	public function set_views_number(int $number)
	{
		$this->set_additional_property('views_number', $number);
	}

	public function get_summary()
	{
		return $this->get_additional_property('summary');
	}

	public function set_summary($value)
	{
		$this->set_additional_property('summary', $value);
	}

	public function is_summary_enabled()
	{
		$summary = $this->get_additional_property('summary');
		return !empty($summary);
	}

	public function get_real_summary($parsed_content = '')
	{
		if ($this->summary_field_enabled)
		{
			$summary = $this->get_additional_property('summary');

			if (!empty($summary))
				return FormatingHelper::second_parse($summary);
		}

		$summary = TextHelper::strip_content_tags($parsed_content);
		$summary = TextHelper::cut_string(@strip_tags($summary ? $summary : FormatingHelper::second_parse($this->content), '<br><br/>'), self::$module->get_configuration()->get_configuration_parameters()->get_auto_cut_characters_number());
		$summary = TextHelper::strip_content_extra_line_break($summary);
		return $summary;
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

	protected function kernel_default_properties()
	{
		if ($this->content_field_enabled)
		{
			$this->set_content(self::$module->get_configuration()->get_configuration_parameters()->get_default_content());
			if ($this->summary_field_enabled)
				$this->set_summary('');
		}

		$this->set_views_number(0);

		if ($this->author_custom_name_field_enabled)
			$this->set_author_custom_name('');

		if ($this->thumbnail_field_enabled)
			$this->set_thumbnail(FormFieldThumbnail::DEFAULT_VALUE);
	}

	protected function get_kernel_additional_sorting_fields()
	{
		return array('views' => array('database_field' => 'views_number', 'label' => LangLoader::get_message('common.sort.by.views.number', 'common-lang'), 'icon' => 'fa fa-eye'));
	}

	protected function get_kernel_additional_template_vars($parsed_content = '')
	{
		$content = $parsed_content ? $parsed_content : FormatingHelper::second_parse($this->content);
		$summary = $this->content_field_enabled && $this->summary_field_enabled ? $this->get_real_summary($content) : '';

		return array(
			// Conditions
			'C_HAS_THUMBNAIL'      => $this->thumbnail_field_enabled && $this->has_thumbnail(),
			'C_AUTHOR_CUSTOM_NAME' => $this->author_custom_name_field_enabled && $this->is_author_custom_name_enabled(),
			'C_READ_MORE'          => $this->content_field_enabled && $this->summary_field_enabled && !$this->get_additional_property('summary') && TextHelper::strlen($content) > self::$module->get_configuration()->get_configuration_parameters()->get_auto_cut_characters_number() && $summary != @strip_tags($content, '<br><br/>'),
			'C_SEVERAL_VIEWS'      => $this->get_views_number() > 1,

			// Item parameters
			'SUMMARY'              => $summary,
			'AUTHOR_CUSTOM_NAME'   => $this->author_custom_name_field_enabled ? $this->get_author_custom_name() : '',
			'VIEWS_NUMBER'         => $this->get_views_number(),

			// Links
			'U_THUMBNAIL'          => $this->thumbnail_field_enabled ? $this->get_thumbnail()->rel() : ''
		);
	}
}
?>
