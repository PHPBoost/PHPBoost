<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 3.0 - 2012 04 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GalleryCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('gallery');
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_id_category(), 'gallery')->read());
		return $authorizations;
	}

	public function is_displayed()
	{
		return (bool)PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'aprob', 'WHERE id = :id', array('id' => $this->get_id_in_module()));
	}

	private function get_id_category()
	{
		return PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'id_category', 'WHERE id = :id', array('id' => $this->get_id_in_module()));
	}
}
?>
