<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 06 17
 * @since       PHPBoost 3.0 - 2012 05 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsletterSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(NewsletterService::get_streams_manager());
	}

	protected function get_category_url(Category $category)
	{
		return NewsletterUrlBuilder::archives($category->get_id(), $category->get_rewrited_name());
	}
}
?>
