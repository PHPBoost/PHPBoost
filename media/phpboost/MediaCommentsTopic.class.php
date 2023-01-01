<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 3.0 - 2011 09 23
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('media');
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_id_category(), 'media')->read());
		return $authorizations;
	}

	public function is_displayed()
	{
		return true;
	}

	private function get_id_category()
	{
		return PersistenceContext::get_querier()->get_column_value(MediaSetup::$media_table, 'id_category', 'WHERE id = :id', array('id' => $this->get_id_in_module()));
	}
}
?>
