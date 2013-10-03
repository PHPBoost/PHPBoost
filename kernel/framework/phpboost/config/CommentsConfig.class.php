<?php
/*##################################################
 *		             CommentsConfig.class.php
 *                            -------------------
 *   begin                : July 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class CommentsConfig extends AbstractConfigData
{
	const AUTHORIZATIONS = 'authorizations';
	const NUMBER_COMMENTS_DISPLAY = 'number_comments_per_page';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const MAX_LINKS_COMMENT = 'max_links_comment';
	const ORDER_DISPLAY_COMMENTS = 'order_display_comments';
	
	const ASC_ORDER = 'ASC';
	const DESC_ORDER = 'DESC';
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_number_comments_display()
	{
		return $this->get_property(self::NUMBER_COMMENTS_DISPLAY);
	}
	
	public function set_number_comments_display($number)
	{
		$this->set_property(self::NUMBER_COMMENTS_DISPLAY, $number);
	}
	
	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}
	
	public function set_forbidden_tags(array $forbidden_tags)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $forbidden_tags);
	}
	
	public function get_max_links_comment()
	{
		return $this->get_property(self::MAX_LINKS_COMMENT);
	}
	
	public function set_max_links_comment($number)
	{
		$this->set_property(self::MAX_LINKS_COMMENT, $number);
	}
	
	public function get_order_display_comments()
	{
		$order_display_comments = $this->get_property(self::ORDER_DISPLAY_COMMENTS);
		switch ($order_display_comments) {
			case self::ASC_ORDER:
			case self::DESC_ORDER:
				$valid_order = $order_display_comments;
			break;
			default:
				$valid_order = self::ASC_ORDER;
			break;
		}
		return $valid_order;
	}
	
	public function set_order_display_comments($order)
	{
		$this->set_property(self::ORDER_DISPLAY_COMMENTS, $order);
	}
	
	public function get_approbation_comments()
	{
		return $this->get_property(self::APPROBATION_COMMENTS);
	}
	
	public function set_approbation_comments($approbation)
	{
		$this->set_property(self::APPROBATION_COMMENTS, $approbation);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHORIZATIONS => array('r1' => 7, 'r0' => 3, 'r-1' => 3),
			self::NUMBER_COMMENTS_DISPLAY => 15,
			self::FORBIDDEN_TAGS => array(),
			self::MAX_LINKS_COMMENT => 2,
			self::ORDER_DISPLAY_COMMENTS => self::DESC_ORDER
		);
	}

	/**
	 * Returns the configuration.
	 * @return CommentsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'comments-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'comments-config');
	}
}
?>