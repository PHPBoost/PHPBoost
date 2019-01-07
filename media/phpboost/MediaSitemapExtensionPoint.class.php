<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 02 11
 * @since   	PHPBoost 3.0 - 2012 02 07
*/

class MediaSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(MediaService::get_categories_manager());
	}

	protected function get_category_url(Category $category)
	{
		return MediaUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}
}
?>
