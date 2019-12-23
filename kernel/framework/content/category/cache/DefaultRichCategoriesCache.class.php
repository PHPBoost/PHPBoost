<?php
/**
 * @package     Content
 * @subpackage  Category\cache
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 23
 * @since       PHPBoost 5.3 - 2019 12 23
*/

class DefaultRichCategoriesCache extends DefaultCategoriesCache
{
	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}

	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_description($this->get_root_category_description());
		$root->set_authorizations($this->get_root_category_authorizations());
		return $root;
	}
	
	protected function get_root_category_description()
	{
		return '';
	}
}
?>
