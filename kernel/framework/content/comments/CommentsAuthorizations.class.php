<?php
/**
 * This class could be used to specified comments authorizations (access, read, post, moderation)
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 04 01
*/

class CommentsAuthorizations
{
	private $authorized_access_module = true;

	const READ_AUTHORIZATIONS = 1;
	const POST_AUTHORIZATIONS = 2;
	const MODERATE_AUTHORIZATIONS = 4;

	public function is_authorized_access_module()
	{
		return $this->authorized_access_module;
	}

	public function is_authorized_read()
	{
		return $this->check_authorizations(self::READ_AUTHORIZATIONS);
	}

	public function is_authorized_post()
	{
		return $this->check_authorizations(self::POST_AUTHORIZATIONS);
	}

	public function is_authorized_moderation()
	{
		return $this->check_authorizations(self::MODERATE_AUTHORIZATIONS);
	}

	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_access_module($authorized)
	{
		$this->authorized_access_module = $authorized;
	}

	private function check_authorizations($global_bit)
	{
		return AppContext::get_current_user()->check_auth(CommentsConfig::load()->get_authorizations(), $global_bit);
	}
}
?>
