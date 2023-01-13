<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 10
 * @since       PHPBoost 6.0 - 2023 01 10
*/

class DownloadCategory extends Category
{
	const THUMBNAIL_URL = '/templates/__default__/images/default_category.webp';

	public function __construct()
	{
		parent::__construct();
		$this->set_additional_property('description', '');
		$this->set_additional_property('thumbnail', FormFieldThumbnail::DEFAULT_VALUE);
	}

	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('description', array('type' => 'text', 'length' => 65000));
		$this->add_additional_attribute('thumbnail', array(
			'type'    => 'string',
			'length'  => 255,
			'notnull' => 1,
			'default' => "''",
			'attribute_field_parameters' => array(
				'field_class'     => 'FormFieldThumbnail',
				'label'           => LangLoader::get_message('form.thumbnail', 'form-lang'),
				'default_value'   => FormFieldThumbnail::DEFAULT_VALUE,
				'default_picture' => self::THUMBNAIL_URL
			)
		));
	}

	public function get_description()
	{
		return $this->get_additional_property('description');
	}

	public function get_thumbnail()
	{
		if (!$this->get_additional_property('thumbnail') instanceof Url)
			return new Url($this->get_additional_property('thumbnail') == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->get_additional_property('thumbnail'));

		return $this->get_additional_property('thumbnail');
	}

	protected function set_additional_properties(array $properties)
	{
		parent::set_additional_properties($properties);
	}
}
?>
