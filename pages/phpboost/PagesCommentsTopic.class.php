<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 29
 * @since       PHPBoost 3.0 - 2012 04 25
*/

class PagesCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('pages');
	}

	public function get_authorizations()
	{
		require_once(PATH_TO_ROOT .'/'. $this->get_module_id() . '/pages_defines.php');

		$page_authorizations = TextHelper::unserialize($this->get_page_authorizations());

		$authorizations = new CommentsAuthorizations();
		if (!empty($page_authorizations))
		{
			$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth($page_authorizations, READ_PAGE));

		}
		else
		{
			$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth(PagesConfig::load()->get_authorizations(), READ_PAGE));

		}
		return $authorizations;
	}

	public function is_display()
	{
		return true;
	}

	private function get_page_authorizations()
	{
		$columns = 'auth';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $this->get_id_in_module());
		return PersistenceContext::get_querier()->get_column_value(PREFIX . 'pages', $columns, $condition, $parameters);
	}
}
?>
