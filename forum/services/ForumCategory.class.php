<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 17
 * @since       PHPBoost 4.1 - 2015 02 25
*/

class ForumCategory extends Category
{
	private $type;

	const TYPE_CATEGORY = 0;
	const TYPE_FORUM = 1;
	const TYPE_URL = 2;

	const STATUS_UNLOCKED = 0;
	const STATUS_LOCKED = 1;

	public static function __static()
	{
		parent::__static();
		self::add_additional_attribute('status', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		self::add_additional_attribute('description', array('type' => 'text', 'length' => 65000));
		self::add_additional_attribute('last_topic_id', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'key' => true));
		self::add_additional_attribute('url', array('type' => 'string', 'length' => 255, 'default' => "''"));
	}

	public function __construct()
	{
		parent::__construct();
		$this->type = self::TYPE_CATEGORY;
		$this->set_additional_property('description', '');
		$this->set_additional_property('status', self::STATUS_UNLOCKED);
		$this->set_additional_property('last_topic_id', 0);
		$this->set_additional_property('url', '');
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
