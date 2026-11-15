<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 6.1 - 2026 11 15
*/

class AdminAddonsConfigDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct(View $view, string $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['configuration.title']);
		$this->add_link($lang['form.configuration'], AdminAddonsUrlBuilder::addons_config());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
