<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 4.0 - 2013 07 08
*/

class AdminContentDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-contents-common');
		$this->set_title($lang['content.config']);

		$this->add_link($lang['content.config'], AdminContentUrlBuilder::content_configuration());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
