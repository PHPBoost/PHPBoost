<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 04 09
*/

class WikiCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('wiki');
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(true);
		return $authorizations;
	}

	public function is_display()
	{
		return true;
	}
}
?>
