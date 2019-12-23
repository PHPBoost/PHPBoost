<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 23
 * @since       PHPBoost 4.0 - 2015 02 04
*/

class MediaCategory extends RichCategory
{
	private $content_type;

	public function set_content_type($content_type)
	{
		$this->content_type = $content_type;
	}

	public function get_content_type()
	{
		return $this->content_type;
	}

	public function get_additional_properties()
	{
		return array('content_type' => $this->get_content_type());
	}

	public function set_additional_properties(array $properties)
	{
		$this->set_content_type($properties['content_type']);
	}

	public static function get_categories_table_rich_additional_fields()
	{
		return array(
			'content_type' => array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0)
		);
	}
}
?>
