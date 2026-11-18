<?php
/**
 * This class could be used to specified comments authorizations (access, read, post, moderation)
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 3.0 - 2010 04 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsAuthorizations
{
	private bool $authorized_access_module = true;

	const READ_AUTHORIZATIONS     = 1;
	const POST_AUTHORIZATIONS     = 2;
	const MODERATE_AUTHORIZATIONS = 4;

	public function is_authorized_access_module(): bool
	{
		return $this->authorized_access_module;
	}

	public function is_authorized_read(): bool
	{
		return $this->check_authorizations(self::READ_AUTHORIZATIONS);
	}

	public function is_authorized_post(): bool
	{
		return $this->check_authorizations(self::POST_AUTHORIZATIONS);
	}

	public function is_authorized_moderation(): bool
	{
		return $this->check_authorizations(self::MODERATE_AUTHORIZATIONS);
	}

	public function set_authorized_access_module(bool $authorized): void
	{
		$this->authorized_access_module = $authorized;
	}

	private function check_authorizations(int $global_bit): bool
	{
		return AppContext::get_current_user()->check_auth(CommentsConfig::load()->get_authorizations(), $global_bit);
	}
}
?>
