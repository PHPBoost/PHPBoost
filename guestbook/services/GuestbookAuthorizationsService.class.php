<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 06 27
*/

class GuestbookAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const MODERATION_AUTHORIZATIONS = 4;

	public static function check_authorizations()
	{
		$instance = new self();
		return $instance;
	}

	public function read()
	{
		return $this->get_authorizations(self::READ_AUTHORIZATIONS);
	}

	public function write()
	{
		return $this->get_authorizations(self::WRITE_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->get_authorizations(self::MODERATION_AUTHORIZATIONS);
	}

	private function get_authorizations($bit)
	{
		return AppContext::get_current_user()->check_auth(GuestbookConfig::load()->get_authorizations(), $bit);
	}
}
?>
