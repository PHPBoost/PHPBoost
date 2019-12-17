<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 11 07
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminCacheMenuDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);

		$lang = LangLoader::get('admin-cache-common');
		$this->set_title($lang['cache']);

		$this->add_link($lang['cache'], AdminCacheUrlBuilder::clear_cache());
		$this->add_link($lang['syndication_cache'], AdminCacheUrlBuilder::clear_syndication_cache());
		$this->add_link($lang['css_cache'], AdminCacheUrlBuilder::clear_css_cache());
		$this->add_link($lang['cache_configuration'], AdminCacheUrlBuilder::configuration());

		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
