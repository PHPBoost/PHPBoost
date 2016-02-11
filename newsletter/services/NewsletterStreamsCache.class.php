<?php
/*##################################################
 *                        NewsletterStreamsCache.class.php
 *                            -------------------
 *   begin                : May 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class NewsletterStreamsCache extends CategoriesCache
{
	public function get_table_name()
	{
		return NewsletterSetup::$newsletter_table_streams;
	}
	
	public function get_category_class()
	{
		return 'NewsletterStream';
	}
	
	public function get_module_identifier()
	{
		return 'newsletter';
	}
	
	public function get_root_category()
	{
		$root = new RichRootCategory();
		$root->set_authorizations(NewsletterConfig::load()->get_authorizations());
		return $root;
	}
	
	public function get_streams()
	{
		return $this->get_categories();
	}
	
	public function stream_exists($id)
	{
		return array_key_exists($id, $this->get_categories());
	}
	
	public function get_stream($id)
	{
		return $this->get_category($id);
	}
}
?>