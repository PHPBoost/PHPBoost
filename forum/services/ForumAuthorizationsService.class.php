<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 15
 * @since   	PHPBoost 4.1 - 2015 02 25
*/

class ForumAuthorizationsService
{
	const FLOOD_AUTHORIZATIONS = 16;
	const HIDE_EDITION_MARK_AUTHORIZATIONS = 32;
	const UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS = 64;
	const READ_TOPICS_CONTENT_AUTHORIZATIONS = 128;
	const CATEGORIES_MANAGEMENT_AUTHORIZATIONS = 256;

	public static function check_authorizations($id_category = Category::ROOT_CATEGORY)
	{
		$instance = new self();
		$instance->id_category = $id_category;
		return $instance;
	}

	public function read()
	{
		return $this->is_authorized(Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY);
	}

	public function write()
	{
		return $this->is_authorized(Category::WRITE_AUTHORIZATIONS);
	}

	public function moderation()
	{
		return $this->is_authorized(Category::MODERATION_AUTHORIZATIONS);
	}

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

	public function manage_categories()
	{
		return $this->is_authorized(self::CATEGORIES_MANAGEMENT_AUTHORIZATIONS);
	}

	private function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		if (!in_array($bit, array(Category::READ_AUTHORIZATIONS, Category::WRITE_AUTHORIZATIONS, Category::MODERATION_AUTHORIZATIONS)))
			$auth = ForumConfig::load()->get_authorizations();
		else
			$auth = ForumService::get_categories_manager()->get_heritated_authorizations($this->id_category, $bit, $mode);

		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
