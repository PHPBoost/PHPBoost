<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 23
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

	public function __construct()
	{
		$this->type = self::TYPE_CATEGORY;
		$this->add_additional_attribute('status', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		$this->set_additional_property('status', self::STATUS_UNLOCKED);
		$this->add_additional_attribute('description', array('type' => 'text', 'length' => 65000));
		$this->add_additional_attribute('last_topic_id', array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0, 'key' => true));
		$this->set_additional_property('last_topic_id', 0);
		$this->add_additional_attribute('url', array('type' => 'string', 'length' => 255, 'default' => "''"));
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
		return $this->additional_attributes_values['status'];
	}

	public function get_description()
	{
		return $this->additional_attributes_values['description'];
	}

	public function get_last_topic_id()
	{
		return $this->additional_attributes_values['last_topic_id'];
	}

	public function get_url()
	{
		return $this->additional_attributes_values['url'];
	}
}
?>
