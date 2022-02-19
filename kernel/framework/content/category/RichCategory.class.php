<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 4.0 - 2013 01 29
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class RichCategory extends Category
{
	const THUMBNAIL_URL = '/templates/__default__/images/default_category.webp';

	protected function set_kernel_additional_attributes_list()
	{
		$this->add_additional_attribute('description', array('type' => 'text', 'length' => 65000, 'attribute_field_parameters' => array(
			'field_class' => 'FormFieldRichTextEditor',
			'label'       => LangLoader::get_message('form.description', 'form-lang')
			)
		));
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

	public function set_description($description)
	{
		$this->set_additional_property('description', $description);
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

	public function set_thumbnail($thumbnail)
	{
		$this->set_additional_property('thumbnail', $thumbnail);
	}
}
?>
