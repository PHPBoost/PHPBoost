<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 04 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminConfigDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct(View $view, string $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['configuration.title']);

		$this->add_link($lang['configuration.general'], AdminConfigUrlBuilder::general_config());
		$this->add_link($lang['configuration.advanced'], AdminConfigUrlBuilder::advanced_config());
		$this->add_link($lang['configuration.email'], AdminConfigUrlBuilder::mail_config());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
