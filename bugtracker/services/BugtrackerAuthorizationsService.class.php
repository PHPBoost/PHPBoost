<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 04 29
*/

class BugtrackerAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const ADVANCED_WRITE_AUTHORIZATIONS = 4;
	const MODERATION_AUTHORIZATIONS = 8;

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

	public function advanced_write()
	{
		return $this->get_authorizations(self::ADVANCED_WRITE_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->get_authorizations(self::MODERATION_AUTHORIZATIONS);
	}

	private function get_authorizations($bit)
	{
		return AppContext::get_current_user()->check_auth(BugtrackerConfig::load()->get_authorizations(), $bit);
	}
}
?>
