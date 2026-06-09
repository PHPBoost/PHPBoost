<?php
/**
 * @package     Content
 * @subpackage  Comments\controllers
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2011 09 23
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AjaxCommentsDisplayController extends AbstractCommentsController
{
	public function execute(HTTPRequestCustom $request)
	{
		parent::execute($request);

		$view = CommentsService::display_comments($this->get_module_id(), $this->get_id_in_module(), $this->get_topic_identifier(),
		$this->get_comments_number_display(), $this->get_authorizations(), true);

		return new SiteNodisplayResponse($view);
	}

	private function get_comments_number_display()
	{
		return !empty($this->provider) ? $this->provider->get_comments_number_display() : 0;
	}
}
?>
