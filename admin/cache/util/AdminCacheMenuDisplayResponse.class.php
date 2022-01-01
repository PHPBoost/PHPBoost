<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCacheMenuDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get_all_langs();
		$this->set_title($lang['admin.cache']);

		$this->add_link($lang['admin.cache'], AdminCacheUrlBuilder::clear_cache());
		$this->add_link($lang['admin.cache.syndication'], AdminCacheUrlBuilder::clear_syndication_cache());
		$this->add_link($lang['admin.cache.css'], AdminCacheUrlBuilder::clear_css_cache());
		$this->add_link($lang['admin.cache.configuration'], AdminCacheUrlBuilder::configuration());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
