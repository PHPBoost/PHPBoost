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
	public function __construct()
	{
		parent::__construct();
		$this->add_additional_attribute('content_type', array('type' => 'integer', 'length' => 1, 'notnull' => 1, 'default' => 0));
	}
	
	public function get_content_type()
	{
		return $this->additional_attributes_values['content_type'];
	}
}
?>
