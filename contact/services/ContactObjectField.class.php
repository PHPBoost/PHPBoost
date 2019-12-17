<?php
/**
 * This class represents the contact object field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 09 24
*/

class ContactObjectField extends ContactField
{
	private $recipients;

	public function set_recipients($value)
	{
		$this->recipients = $recipients;
	}

	public function get_recipients()
	{
		return $this->recipients;
	}
}
?>
