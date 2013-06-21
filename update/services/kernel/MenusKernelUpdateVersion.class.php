<?php
/*##################################################
 *                       MenusKernelUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 07, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class MenusKernelUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('menus');
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function execute()
	{
		$this->update_menus();
		$this->update_mini_module();
	}
	
	private function update_menus()
	{
		$this->querier->delete(PREFIX .'menus', 'WHERE title=:title', array('title' => 'themeswitcher/themeswitcher'));
		$this->querier->delete(PREFIX .'menus', 'WHERE class=:class', array('class' => 'langswitcher/langswitcher'));
	}
	
	private function update_mini_module()
	{
		$result = $this->querier->select_rows(PREFIX .'menus', array('*'));
		while ($row = $result->fetch())
		{
			if ($row['class'] == 'contentmenu')
			{
				$object = str_replace('contentmenu', 'ContentMenu', $row['object']);
				$this->querier->update(PREFIX .'menus', array('class' => 'ContentMenu', 'object' => $object), 'WHERE id=:id', array('id' => $row['id']));
			}
			elseif ($row['class'] == 'linksmenu')
			{
				$object = str_replace('linksmenu', 'LinksMenu', $row['object']);
				$object = str_replace('LinksMenulink', 'LinksMenuLink', $object);
				$this->querier->update(PREFIX .'menus', array('class' => 'LinksMenu', 'object' => $object), 'WHERE id=:id', array('id' => $row['id']));
			}
			elseif ($row['class'] == 'feedmenu')
			{
				$object = str_replace('feedmenu', 'FeedMenu', $row['object']);
				$this->querier->update(PREFIX .'menus', array('class' => 'FeedMenu', 'object' => $object), 'WHERE id=:id', array('id' => $row['id']));
			}
			elseif($row['class'] == 'moduleminimenu')
			{
				$menu_info = $this->convert_title_and_class($row['title']);
				if ($menu_info)
				{
					$class = $menu_info['class'];
					$menu = new $class();
					$menu->enabled((bool)$row['enabled']);
					$menu->set_block($row['block']);
					$menu->set_block_position($row['position']);
					
					$menu = array_merge($menu_info, array('object' => serialize($menu)));
					$this->querier->update(PREFIX .'menus', $menu, 'WHERE id=:id', array('id' => $row['id']));
				}
			}
		}
	}
	
	private function convert_title_and_class($title_menu)
	{
		$menus = array(
			'connect/connect_mini' => array(
				'title' => 'connect/ConnectModuleMiniMenu', 'class' => 'ConnectModuleMiniMenu'
			),
			'faq/faq_mini' => array(
				'title' => 'faq/FaqModuleMiniMenu', 'class' => 'FaqModuleMiniMenu'
			),
			'gallery/gallery_mini' => array(
				'title' => 'gallery/GalleryModuleMiniMenu', 'class' => 'GalleryModuleMiniMenu'
			),
			'guestbook/guestbook_mini' => array(
				'title' => 'guestbook/GuestbookModuleMiniMenu', 'class' => 'GuestbookModuleMiniMenu'
			),
			'newsletter/newsletter_mini' => array(
				'title' => 'newsletter/NewsletterModuleMiniMenu', 'class' => 'NewsletterModuleMiniMenu'
			),
			'online/online_mini' => array(
				'title' => 'online/OnlineModuleMiniMenu', 'class' => 'OnlineModuleMiniMenu'
			),
			'poll/poll_mini' => array(
				'title' => 'poll/PollModuleMiniMenu', 'class' => 'PollModuleMiniMenu'
			),
			'search/search_mini' => array(
				'title' => 'search/SearchModuleMiniMenu', 'class' => 'SearchModuleMiniMenu'
			),
			'shoutbox/shoutbox_mini' => array(
				'title' => 'shoutbox/ShoutboxModuleMiniMenu', 'class' => 'ShoutboxModuleMiniMenu'
			),
			'stats/stats_mini' => array(
				'title' => 'stats/StatsModuleMiniMenu', 'class' => 'StatsModuleMiniMenu'
			)
		);
		
		if (array_key_exists($title_menu, $menus))
		{
			return $menus[$title_menu];
		}
		return false;
	}
}
?>