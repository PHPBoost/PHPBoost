<?php
/*##################################################
 *                               FaqCategoriesCache.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class FaqCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return FaqSetup::$faq_cats_table;
	}
	
	public function get_category_class()
	{
		return CategoriesManager::RICH_CATEGORY_CLASS;
	}
	
	public function get_module_identifier()
	{
		return 'faq';
	}
	
	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(FaqConfig::load()->get_authorizations());
		$root->set_description(FaqConfig::load()->get_root_category_description());
		return $root;
	}
}
?>
