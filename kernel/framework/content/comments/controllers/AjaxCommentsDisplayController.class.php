<?php
/**
 * @package     Content
 * @subpackage  Comments\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 22
 * @since       PHPBoost 3.0 - 2011 09 23
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AjaxCommentsDisplayController extends AbstractCommentsController
{
	public function execute(HTTPRequestCustom $request)
	{
		parent::execute($request);

		$view = CommentsService::display_comments($this->get_module_id(), $this->get_id_in_module(), $this->get_topic_identifier(),
		$this->get_number_comments_display(), $this->get_authorizations(), true);

		return new SiteNodisplayResponse($view);
	}

	private function get_number_comments_display()
	{
		return !empty($this->provider) ? $this->provider->get_number_comments_display() : 0;
	}
}
?>
