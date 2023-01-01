<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 4.1 - 2015 05 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminFilesDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['upload.files.management']);

		$this->add_link($lang['upload.files.management'], AdminFilesUrlBuilder::management());
		$this->add_link($lang['upload.files.config'], AdminFilesUrlBuilder::configuration());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
