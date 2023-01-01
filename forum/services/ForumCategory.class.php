<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 23
 * @since       PHPBoost 4.1 - 2015 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ForumCategory extends Category
{
	private $type;

	const TYPE_CATEGORY   = 0;
	const TYPE_FORUM      = 1;
	const TYPE_URL        = 2;

	const STATUS_UNLOCKED = 0;
	const STATUS_LOCKED   = 1;
	const THUMBNAIL_URL   = '/templates/__default__/images/default_category.webp';

	public function __construct()
	{
		parent::__construct();
		$this->type = self::TYPE_CATEGORY;
		$this->set_additional_property('description', '');
		$this->set_additional_property('status', self::STATUS_UNLOCKED);
		$this->set_additional_property('last_topic_id', 0);
		$this->set_additional_property('url', '');
		$this->set_additional_property('icon', '');
		$this->set_additional_property('color', '');
		$this->set_additional_property('thumbnail', FormFieldThumbnail::DEFAULT_VALUE);
	}

	protected function set_additional_attributes_list()
	{
		$this->add_additional_attribute('status', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		$this->add_additional_attribute('description', array('type' => 'text', 'length' => 65000));
		$this->add_additional_attribute('last_topic_id', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'key' => true));
		$this->add_additional_attribute('url', array('type' => 'string', 'length' => 255, 'default' => "''"));
		$this->add_additional_attribute('icon', array('type' => 'string', 'length' => 255, 'default' => "''"));
		$this->add_additional_attribute('color', array('type' => 'string', 'length' => 250, 'default' => "''"));
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

	public function set_type($type)
	{
		$this->type = $type;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function get_status()
	{
		return $this->get_additional_property('status');
	}

	public function get_description()
	{
		return $this->get_additional_property('description');
	}

	public function get_last_topic_id()
	{
		return $this->get_additional_property('last_topic_id');
	}

	public function get_url()
	{
		return $this->get_additional_property('url');
	}

	public function get_icon()
	{
		return $this->get_additional_property('icon');
	}

	public function get_thumbnail()
	{
		if (!$this->get_additional_property('thumbnail') instanceof Url)
			return new Url($this->get_additional_property('thumbnail') == FormFieldThumbnail::DEFAULT_VALUE ? FormFieldThumbnail::get_default_thumbnail_url(self::THUMBNAIL_URL) : $this->get_additional_property('thumbnail'));

		return $this->get_additional_property('thumbnail');
	}

	public function get_color()
	{
		return !empty($this->get_additional_property('color')) ? $this->get_additional_property('color') : '#366493';
	}

	protected function set_additional_properties(array $properties)
	{
		if (!empty($properties['url']))
			$this->set_type(self::TYPE_URL);
		else if ($properties['id_parent'] != Category::ROOT_CATEGORY)
			$this->set_type(self::TYPE_FORUM);
		else
			$this->set_type(self::TYPE_CATEGORY);

		parent::set_additional_properties($properties);
	}
}
?>
