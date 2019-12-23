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
	private $status;
	private $description;
	private $last_topic_id;
	private $url;

	const TYPE_CATEGORY = 0;
	const TYPE_FORUM = 1;
	const TYPE_URL = 2;

	const STATUS_UNLOCKED = 0;
	const STATUS_LOCKED = 1;

	public function __construct()
	{
		$this->type = self::TYPE_CATEGORY;
		$this->status = self::STATUS_UNLOCKED;
		$this->last_topic_id = 0;
	}

	public function set_type($type)
	{
		$this->type = $type;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_status($status)
	{
		$this->status = $status;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_description($description)
	{
		$this->description = $description;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_last_topic_id($last_topic_id)
	{
		$this->last_topic_id = $last_topic_id;
	}

	public function get_last_topic_id()
	{
		return $this->last_topic_id;
	}

	public function set_url($url)
	{
		$this->url = $url;
	}

	public function get_url()
	{
		return $this->url;
	}

	public function get_additional_properties()
	{
		return array(
			'status' => $this->get_status(),
			'description' => $this->get_description(),
			'last_topic_id' => $this->get_last_topic_id(),
			'url' => $this->get_url()
		));
	}

	public function set_additional_properties(array $properties)
	{
		if (!empty($properties['url']))
			$this->set_type(self::TYPE_URL);
		else if ($properties['id_parent'] != Category::ROOT_CATEGORY)
			$this->set_type(self::TYPE_FORUM);
		else
			$this->set_type(self::TYPE_CATEGORY);

		$this->set_status($properties['status']);
		$this->set_description($properties['description']);
		$this->set_last_topic_id($properties['last_topic_id']);
		$this->set_url($properties['url']);
	}

	public static function get_categories_table_additional_fields()
	{
		return array(
			'description'   => array('type' => 'text', 'length' => 65000),
			'last_topic_id' => array('type' => 'integer', 'length' => 11, 'notnull' => 1, 'default' => 0),
			'url'           => array('type' => 'string', 'length' => 255, 'default' => "''")
		);
	}

	public static function get_categories_table_additional_options()
	{
		return array(
			'last_topic_id' => array('type' => 'key', 'fields' => 'last_topic_id')
		);
	}
}
?>
