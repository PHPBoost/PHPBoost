<?php
/*##################################################
 *                               AdminFaqDisplayResponse.class.php
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

class AdminFaqDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);
		
		$lang = LangLoader::get('common', 'faq');
		$picture = '/faq/faq.png';
		$this->set_title($lang['module_title']);
		
		$this->add_link(LangLoader::get_message('categories.management', 'categories-common'), FaqUrlBuilder::manage_categories(), $picture);
		$this->add_link(LangLoader::get_message('category.add', 'categories-common'), FaqUrlBuilder::add_category(), $picture);
		$this->add_link($lang['faq.management'], FaqUrlBuilder::manage(), $picture);
		$this->add_link($lang['faq.actions.add'], FaqUrlBuilder::add(), $picture);
		$this->add_link(LangLoader::get_message('configuration', 'admin-common'), FaqUrlBuilder::configuration(), $picture);
		
		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page, $lang['module_title']);
	}
}
?>
