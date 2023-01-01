<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 30
 * @since       PHPBoost 4.1 - 2015 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ForumAuthorizationsService extends CategoriesAuthorizationsService
{
	const FLOOD_AUTHORIZATIONS                     = 16;
	const HIDE_EDITION_MARK_AUTHORIZATIONS         = 32;
	const UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS = 64;
	const READ_TOPICS_CONTENT_AUTHORIZATIONS       = 128;
	const CATEGORIES_MANAGEMENT_AUTHORIZATIONS     = 256;
	const MULTIPLE_POSTS_AUTHORIZATIONS            = 512;

	public function flood()
	{
		return $this->is_authorized(self::FLOOD_AUTHORIZATIONS);
	}

	public function hide_edition_mark()
	{
		return $this->is_authorized(self::HIDE_EDITION_MARK_AUTHORIZATIONS);
	}

	public function unlimited_topics_tracking()
	{
		return $this->is_authorized(self::UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS);
	}

	public function read_topics_content()
	{
		return $this->is_authorized(self::READ_TOPICS_CONTENT_AUTHORIZATIONS);
	}

	public function multiple_posts()
	{
		return $this->is_authorized(self::MULTIPLE_POSTS_AUTHORIZATIONS);
	}

	protected function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		if (!in_array($bit, array(Category::READ_AUTHORIZATIONS, Category::WRITE_AUTHORIZATIONS, Category::MODERATION_AUTHORIZATIONS)))
			$auth = ForumConfig::load()->get_authorizations();
		else
			$auth = CategoriesService::get_categories_manager('forum')->get_heritated_authorizations($this->id_category, $bit, $mode);

		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
